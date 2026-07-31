/**
 * CORS WebCare - Studio API service
 *
 * The endpoints are injected into Studio's shared RTK Query API instance, so they
 * reuse its auth and error handling. The shared base query carries no path prefix,
 * so every url has to spell out `/pimcore-studio/api` — same as Data Hub does in
 * its generated slices.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @license    GPLv3 and PCL
 */

import { api } from '@pimcore/studio-ui-bundle/api'
import type {
  CreateWebCareSitePayload,
  UpdateWebCareSitePayload,
  WebCareSite,
  WebCareSiteCollection
} from '../types'

const WEB_CARE_TAG = 'WebCareSite'

export const webCareApi = api
  .enhanceEndpoints({ addTagTypes: [WEB_CARE_TAG] })
  .injectEndpoints({
    endpoints: (builder) => ({
      webCareSiteGetCollection: builder.query<WebCareSiteCollection, void>({
        query: () => ({
          url: '/pimcore-studio/api/bundle/web-care/sites',
          method: 'GET'
        }),
        providesTags: [{ type: WEB_CARE_TAG, id: 'LIST' }]
      }),

      webCareSiteCreate: builder.mutation<WebCareSite, CreateWebCareSitePayload>({
        query: (body) => ({
          url: '/pimcore-studio/api/bundle/web-care/sites',
          method: 'POST',
          body
        }),
        invalidatesTags: [{ type: WEB_CARE_TAG, id: 'LIST' }]
      }),

      webCareSiteUpdate: builder.mutation<WebCareSite, { id: number, body: UpdateWebCareSitePayload }>({
        query: ({ id, body }) => ({
          url: `/pimcore-studio/api/bundle/web-care/sites/${id}`,
          method: 'PUT',
          body
        }),
        invalidatesTags: [{ type: WEB_CARE_TAG, id: 'LIST' }]
      }),

      webCareSiteDelete: builder.mutation<void, { id: number }>({
        query: ({ id }) => ({
          url: `/pimcore-studio/api/bundle/web-care/sites/${id}`,
          method: 'DELETE'
        }),
        invalidatesTags: [{ type: WEB_CARE_TAG, id: 'LIST' }]
      })
    }),
    overrideExisting: false
  })

export const {
  useWebCareSiteGetCollectionQuery,
  useWebCareSiteCreateMutation,
  useWebCareSiteUpdateMutation,
  useWebCareSiteDeleteMutation
} = webCareApi
