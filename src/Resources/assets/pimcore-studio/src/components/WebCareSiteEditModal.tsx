/**
 * CORS WebCare - Edit modal for a single site configuration
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @license    GPLv3 and PCL
 */

import React, { useEffect } from 'react'
import { Button, Form, Input, Modal, Switch } from '@pimcore/studio-ui-bundle/components'
import { useTranslation } from 'react-i18next'
import type { WebCareSite } from '../types'

export interface WebCareSiteFormValues {
  active: boolean
  clientId: string
  organizationId: string
  websiteId: string
}

export interface WebCareSiteEditModalProps {
  site: WebCareSite | null
  isSaving: boolean
  onCancel: () => void
  onSubmit: (values: WebCareSiteFormValues) => void
}

export const WebCareSiteEditModal = (props: WebCareSiteEditModalProps): React.JSX.Element => {
  const { site, isSaving, onCancel, onSubmit } = props
  const { t } = useTranslation()
  const [form] = Form.useForm<WebCareSiteFormValues>()

  useEffect(() => {
    if (site === null) {
      return
    }

    form.setFieldsValue({
      active: site.active,
      clientId: site.clientId ?? '',
      organizationId: site.organizationId ?? '',
      websiteId: site.websiteId ?? ''
    })
  }, [site, form])

  return (
    <Modal
      footer={ [
        <Button
          key="cancel"
          onClick={ onCancel }
        >
          {t('web_care.action.cancel')}
        </Button>,
        <Button
          key="submit"
          loading={ isSaving }
          onClick={ () => { form.submit() } }
          type="primary"
        >
          {t('web_care.action.save')}
        </Button>
      ] }
      onCancel={ onCancel }
      open={ site !== null }
      title={ `${t('web_care.edit.title')} — ${site?.siteDomain ?? ''}` }
    >
      <Form
        form={ form }
        layout="vertical"
        onFinish={ onSubmit }
      >
        <Form.Item
          label={ t('web_care.column.active') }
          name="active"
          valuePropName="checked"
        >
          <Switch />
        </Form.Item>
        <Form.Item
          label={ t('web_care.column.client_id') }
          name="clientId"
        >
          <Input />
        </Form.Item>
        <Form.Item
          label={ t('web_care.column.organization_id') }
          name="organizationId"
        >
          <Input />
        </Form.Item>
        <Form.Item
          label={ t('web_care.column.website_id') }
          name="websiteId"
        >
          <Input />
        </Form.Item>
      </Form>
    </Modal>
  )
}
