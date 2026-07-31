/**
 * CORS WebCare - Site configuration listing
 *
 * Replaces the classic ExtJS grid under "Settings > Webcare Settings". Every
 * Pimcore site (plus the main site) is listed; rows without an id have no
 * configuration yet and are created on first save.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @license    GPLv3 and PCL
 */

import React, { useEffect, useState } from 'react'
import { createColumnHelper } from '@tanstack/react-table'
import {
  ButtonGroup,
  Content,
  Grid,
  IconButton,
  IconTextButton,
  Tag,
  Toolbar,
  useFormModal,
  useMessage
} from '@pimcore/studio-ui-bundle/components'
import { ApiError, GeneralError, isApiErrorData, trackError } from '@pimcore/studio-ui-bundle/modules/app'
import { useTranslation } from 'react-i18next'
import {
  useWebCareSiteCreateMutation,
  useWebCareSiteDeleteMutation,
  useWebCareSiteGetCollectionQuery,
  useWebCareSiteUpdateMutation
} from '../services/api'
import type { WebCareSite } from '../types'
import { WebCareSiteEditModal, type WebCareSiteFormValues } from './WebCareSiteEditModal'

const reportError = (requestError: unknown): void => {
  trackError(
    isApiErrorData(requestError)
      ? new ApiError(requestError)
      : new GeneralError(requestError instanceof Error ? requestError.message : String(requestError))
  )
}

const emptyValue = (value: string | null): string | null => {
  const trimmed = value?.trim() ?? ''

  return trimmed === '' ? null : trimmed
}

export const WebCareSiteManager = (): React.JSX.Element => {
  const { t } = useTranslation()
  const modal = useFormModal()
  const message = useMessage()

  const { data, isFetching, error, refetch } = useWebCareSiteGetCollectionQuery()
  const [createSite, { isLoading: isCreating }] = useWebCareSiteCreateMutation()
  const [updateSite, { isLoading: isUpdating }] = useWebCareSiteUpdateMutation()
  const [deleteSite] = useWebCareSiteDeleteMutation()

  const [editedSite, setEditedSite] = useState<WebCareSite | null>(null)

  useEffect(() => {
    if (error !== undefined) {
      trackError(new ApiError(error))
    }
  }, [error])

  const handleSubmit = (values: WebCareSiteFormValues): void => {
    if (editedSite === null) {
      return
    }

    const payload = {
      active: values.active === true,
      clientId: emptyValue(values.clientId),
      organizationId: emptyValue(values.organizationId),
      websiteId: emptyValue(values.websiteId)
    }

    const request = editedSite.id === null
      ? createSite({ siteId: editedSite.siteId, ...payload })
      : updateSite({ id: editedSite.id, body: payload })

    request.unwrap()
      .then(() => {
        message.success(t('web_care.notification.saved'))
        setEditedSite(null)
      })
      .catch(reportError)
  }

  const handleDelete = (site: WebCareSite): void => {
    if (site.id === null) {
      return
    }

    const id = site.id

    modal.confirm({
      title: t('web_care.delete.confirm.title'),
      content: t('web_care.delete.confirm.text'),
      okText: t('web_care.action.delete'),
      okButtonProps: { color: 'danger' },
      onOk: async () => {
        try {
          await deleteSite({ id }).unwrap()
          message.success(t('web_care.notification.deleted'))
        } catch (requestError: unknown) {
          reportError(requestError)
        }
      }
    })
  }

  const columnHelper = createColumnHelper<WebCareSite>()

  const columns = [
    columnHelper.accessor('siteDomain', {
      header: t('web_care.column.site'),
      size: 260
    }),
    columnHelper.accessor('active', {
      header: t('web_care.column.active'),
      size: 120,
      cell: (info) => (
        <Tag color={ info.getValue() ? 'success' : 'default' }>
          {info.getValue() ? t('web_care.column.active') : t('web_care.not_configured')}
        </Tag>
      )
    }),
    columnHelper.accessor('clientId', {
      header: t('web_care.column.client_id'),
      size: 300,
      cell: (info) => info.getValue() ?? '-'
    }),
    columnHelper.accessor('organizationId', {
      header: t('web_care.column.organization_id'),
      size: 200,
      cell: (info) => info.getValue() ?? '-'
    }),
    columnHelper.accessor('websiteId', {
      header: t('web_care.column.website_id'),
      size: 160,
      cell: (info) => info.getValue() ?? '-'
    }),
    columnHelper.display({
      id: 'actions',
      header: t('web_care.column.actions'),
      size: 110,
      cell: (info) => (
        <ButtonGroup
          items={ [
            <IconButton
              icon={ { value: 'edit' } }
              key="edit"
              onClick={ () => { setEditedSite(info.row.original) } }
              tooltip={ { title: t('web_care.action.edit') } }
            />,
            <IconButton
              disabled={ info.row.original.id === null }
              icon={ { value: 'trash' } }
              key="delete"
              onClick={ () => { handleDelete(info.row.original) } }
              tooltip={ { title: t('web_care.action.delete') } }
            />
          ] }
        />
      )
    })
  ]

  return (
    <Content padded>
      <Toolbar>
        <IconTextButton
          icon={ { value: 'refresh' } }
          onClick={ () => { void refetch() } }
        >
          {t('web_care.action.reload')}
        </IconTextButton>
      </Toolbar>

      <Grid
        columns={ columns }
        data={ data?.items ?? [] }
        isLoading={ isFetching }
        resizable
      />

      <WebCareSiteEditModal
        isSaving={ isCreating || isUpdating }
        onCancel={ () => { setEditedSite(null) } }
        onSubmit={ handleSubmit }
        site={ editedSite }
      />
    </Content>
  )
}
