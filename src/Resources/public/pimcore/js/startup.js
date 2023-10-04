/**
 * CORS GmbH.
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @license    https://www.cors.gmbh/license     GPLv3 and PCL
 */

pimcore.registerNS('pimcore.plugin.cors_webcare');

pimcore.plugin.cors_webcare = Class.create({
    initialize: function () {
        document.addEventListener(pimcore.events.pimcoreReady, this.pimcoreReady.bind(this));
    },

    pimcoreReady: function () {

        var user = pimcore.globalmanager.get('user');

        if (user.isAllowed('plugins')) {

            var importMenu = new Ext.Action({
                text: 'Webcare Settings',
                iconCls: 'cors_nav_webcare_settings',
                handler: this.openSettings
            });

            layoutToolbar.settingsMenu.add(importMenu);
        }
    },

    openSettings: function () {
        try {
            pimcore.globalmanager.get('cors_webcare_settings').activate();
        } catch (e) {
            pimcore.globalmanager.add('cors_webcare_settings', new pimcore.plugin.cors_webcare.settings());
        }
    },
});

new pimcore.plugin.cors_webcare();

