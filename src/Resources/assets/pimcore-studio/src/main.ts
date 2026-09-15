/**
 * CORS WebCare - Pimcore Studio Plugin
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @license    MIT
 */

import { type IAbstractPlugin } from '@pimcore/studio-ui-bundle'
import { WebCareIconModule } from './modules/icon-library'
import { WebCareMenuModule } from './modules/menu'

const WebCarePlugin: IAbstractPlugin = {
  name: 'web-care',

  onStartup ({ moduleSystem }): void {
    moduleSystem.registerModule(WebCareIconModule)
    moduleSystem.registerModule(WebCareMenuModule)
  }
}

export default WebCarePlugin
