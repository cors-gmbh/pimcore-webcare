/**
 * CORS WebCare - Menu Module
 *
 * Registers the "Webcare Settings" entry below System — the Studio counterpart of
 * the classic ExtJS settings menu. Gated on `web_care_settings`, einer eigenen
 * Permission des Bundles — das klassische `plugins` gibt es in Pimcore 12 nicht mehr.
 */

import { container } from '@pimcore/studio-ui-bundle'
import { serviceIds } from '@pimcore/studio-ui-bundle/app'
import { type IMainNavItem, type MainNavRegistry } from '@pimcore/studio-ui-bundle/modules/app'
import { type WidgetRegistry } from '@pimcore/studio-ui-bundle/modules/widget-manager'
import { WebCareSiteManager } from '../components/WebCareSiteManager'

export const WebCareMenuModule = {
  onInit (): void {
    const mainNavRegistry = container.get<MainNavRegistry>(serviceIds.mainNavRegistry)
    const widgetRegistry = container.get<WidgetRegistry>(serviceIds.widgetManager)

    widgetRegistry.registerWidget({
      name: 'web-care-settings',
      component: WebCareSiteManager
    })

    const webCareNav: IMainNavItem = {
      path: 'System/Webcare Settings',
      label: 'web_care.menu.settings',
      className: 'item-style-modifier',
      order: 650,
      permission: 'web_care_settings',
      // no nav icon on purpose — the neighbouring entries under System have none
      widgetConfig: {
        name: 'Webcare Settings',
        id: 'web-care-settings',
        component: 'web-care-settings',
        config: {
          translationKey: 'web_care.menu.settings',
          icon: {
            type: 'name',
            value: 'web_care_icon'
          }
        }
      }
    }

    mainNavRegistry.registerMainNavItem(webCareNav)
  }
}
