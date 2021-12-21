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

pimcore.registerNS('pimcore.plugin.cors_webcare.settings');

pimcore.plugin.cors_webcare.settings = Class.create({
    layoutId: 'cors_webcare_settings',
    iconCls: 'cors_webcare_settings',

    url: {
        add: 'cors_webcaresite_create',
        delete: 'cors_webcaresite_delete',
        list: 'cors_webcaresite_list',
        update: 'cors_webcaresite_update'
    },

    store: null,

    initialize: function () {
        this.store = new Ext.data.Store({
            proxy: {
                type: 'ajax',
                url: Routing.generate(this.url.list),
                reader: {
                    type: 'json',
                }
            },
            fields: [
                {name: 'webCareId'},
                {name: 'active'},
                {name: 'siteId'},
                {name: 'clientId'},
                {name: 'organizationId'},
                {name: 'websiteId'},
                {name: 'siteDomain'},
            ],
            autoLoad: true,
            remoteSort: false,
            remoteFilter: false
        });

        // create layout
        this.getLayout();

        this.panels = [];
    },

    activate: function () {
        var tabPanel = Ext.getCmp('pimcore_panel_tabs');
        tabPanel.setActiveItem(this.layoutId);
    },

    getLayout: function () {
        if (!this.layout) {

            // create new panel
            this.layout = new Ext.Panel({
                id: this.layoutId,
                title: this.getTitle(),
                iconCls: this.iconCls,
                border: false,
                layout: 'border',
                closable: true,
                items: this.getItems()
            });

            // add event listener
            var layoutId = this.layoutId;
            this.layout.on('destroy', function () {
                pimcore.globalmanager.remove(layoutId);
            }.bind(this));

            // add panel to pimcore panel tabs
            var tabPanel = Ext.getCmp('pimcore_panel_tabs');
            tabPanel.add(this.layout);
            tabPanel.setActiveItem(this.layoutId);

            // update layout
            pimcore.layout.refresh();
        }

        return this.layout;
    },

    getTitle: function () {
        return 'Webcare Settings';
    },

    refresh: function () {
        this.store.load();
    },

    getItems: function () {
        return [this.getGrid()];
    },

    getGrid: function () {
        this.grid = Ext.create('Ext.grid.Panel', {
            store: this.store,
            region: 'center',
            columns: [
                {
                    header: 'Site Domain',
                    flex: 1,
                    dataIndex: 'siteDomain'
                },
                {
                    header: 'Active',
                    width: 100,
                    dataIndex: 'active',
                    editor: {
                        xtype: 'checkbox',
                        required: true
                    }
                },
                {
                    header: 'Client ID',
                    width: 300,
                    dataIndex: 'clientId',
                    editor: {
                        xtype: 'textfield',
                        required: true
                    }
                },
                {
                    header: 'Organization ID',
                    width: 300,
                    dataIndex: 'organizationId',
                    editor: {
                        xtype: 'textfield',
                        required: true
                    }
                },
                {
                    header:'Website ID',
                    width: 300,
                    dataIndex: 'websiteId',
                    editor: {
                        xtype: 'textfield',
                        required: false
                    }
                },
                {
                    xtype: 'actioncolumn',
                    width: 40,
                    items: [{
                        tooltip: t('delete'),
                        isDisabled: function (grid, rowIndex, colIndex, items, record) {
                            return record.data.webCareId === null;
                        },
                        getClass: function (v, meta, record) {
                            if (record.data.webCareId === null) {
                                return '';
                            }

                            return 'pimcore_icon_delete';
                        },
                        handler: function (grid, rowIndex, colIndex) {
                            var rec = grid.getStore().getAt(rowIndex);

                            if (rec.data.webCareId) {
                                Ext.Ajax.request({
                                    url: Routing.generate(this.url.delete, {'id': rec.data.webCareId}),
                                    method: 'post',
                                    success: function (response) {
                                        this.refresh();
                                    }.bind(this)
                                });
                            }
                        }.bind(this)
                    }]
                }
            ],
            selModel: 'rowmodel',
            plugins: Ext.create('Ext.grid.plugin.RowEditing', {
                clicksToEdit: 1,
                listeners: {}
            })
        });

        this.grid.on('edit', function (editor, e) {
            Ext.Ajax.request({
                url: e.record.data.webCareId ? Routing.generate(this.url.update, {'id': e.record.data.webCareId}) : Routing.generate(this.url.add),
                jsonData: {
                    'siteId': e.record.data.siteId,
                    'active': e.record.data.active,
                    'clientId': e.record.data.clientId,
                    'organizationId': e.record.data.organizationId,
                    'websiteId': e.record.data.websiteId,
                },
                method: 'post',
                success: function (response) {
                    var res = Ext.decode(response.responseText);

                    if (res.success) {
                        e.record.set({'webCareId': res.id});
                        e.record.commit();
                    } else {
                        e.record.erase();
                        pimcore.helpers.showNotification(
                            t('error'),
                            t('save_error'),
                            'error',
                            res.message
                        );
                    }
                }.bind(this)
            });
        }.bind(this));

        return this.grid;
    }
});
