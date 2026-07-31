/**
 * CORS WebCare - Pimcore Studio Plugin
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @license    GPLv3 and PCL
 */

/**
 * Mirrors CORS\Bundle\WebCareBundle\Schema\WebCareSite.
 *
 * `id` is null for Pimcore sites that do not have a WebCare configuration yet —
 * those rows are placeholders and turn into a real record on first save.
 */
export interface WebCareSite {
  id: number | null
  siteId: number
  siteDomain: string
  active: boolean
  clientId: string | null
  organizationId: string | null
  websiteId: string | null
}

export interface WebCareSiteCollection {
  items: WebCareSite[]
}

export interface CreateWebCareSitePayload {
  siteId: number
  active: boolean
  clientId: string | null
  organizationId: string | null
  websiteId: string | null
}

export interface UpdateWebCareSitePayload {
  active: boolean
  clientId: string | null
  organizationId: string | null
  websiteId: string | null
}
