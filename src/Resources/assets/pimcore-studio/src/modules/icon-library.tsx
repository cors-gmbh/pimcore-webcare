/**
 * CORS WebCare - Icon Library Module
 *
 * Registers the cookie icon used by the navigation entry.
 */

import { container } from '@pimcore/studio-ui-bundle'
import { serviceIds } from '@pimcore/studio-ui-bundle/app'
import { type IconLibrary } from '@pimcore/studio-ui-bundle/modules/icon-library'
import React from 'react'

const WebCareIcon = (): React.JSX.Element => (
  <svg
    fill="currentColor"
    height="100%"
    viewBox="0 0 24 24"
    width="100%"
  >
    <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-4-4 4 4 0 0 1-4-4 2 2 0 0 0-2-2zm-2.5 6a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3zm7 5a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3zm-6 2a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z" />
  </svg>
)

export const WebCareIconModule = {
  onInit (): void {
    const iconLibrary = container.get<IconLibrary>(serviceIds.iconLibrary)

    iconLibrary.register({
      name: 'web_care_icon',
      component: WebCareIcon
    })
  }
}
