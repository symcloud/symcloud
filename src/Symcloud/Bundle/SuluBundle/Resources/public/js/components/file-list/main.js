/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

define(function() {

    'use strict';

    var constants = {
            toolbarSelector: '#list-toolbar-container',
            listSelector: '#file-list',
            listViewStorageKey: 'symcloudFilesListView'
        },

        listViews = {
            table: {
                itemId: 'table',
                name: 'table'
            },
            thumbnailSmall: {
                itemId: 'small-thumbnails',
                name: 'thumbnail',
                thViewOptions: {
                    large: false,
                    selectable: false
                }
            },
            thumbnailLarge: {
                itemId: 'big-thumbnails',
                name: 'thumbnail',
                thViewOptions: {
                    large: true,
                    selectable: false
                }
            }
        };

    return {

        view: true,

        layout: {
            navigation: {
                collapsed: true
            },
            content: {
                width: 'max'
            }
        },

        header: function() {
            // TODO breadcrumb

            return {
                title: 'symcloud.file',
                noBack: true,

                toolbar: {template: 'empty'},

                breadcrumb: [
                    {title: 'navigation.symcloud'},
                    {title: 'navigation.files'}
                ]
            };
        },

        initialize: function() {
            this.listView = this.sandbox.sulu.getUserSetting(constants.listViewStorageKey) || 'thumbnailSmall';

            this.bindCustomEvents();
            this.render();
        },

        bindCustomEvents: function() {
            // change datagrid to table
            this.sandbox.on('sulu.list-toolbar.change.table', function() {
                this.sandbox.emit('husky.datagrid.view.change', 'table');
                this.sandbox.sulu.saveUserSetting(constants.listViewStorageKey, 'table');
            }.bind(this));

            // change datagrid to thumbnail small
            this.sandbox.on('sulu.list-toolbar.change.thumbnail-small', function() {
                this.sandbox.emit('husky.datagrid.view.change', 'thumbnail', listViews['thumbnailSmall']['thViewOptions']);
                this.sandbox.sulu.saveUserSetting(constants.listViewStorageKey, 'thumbnailSmall');
            }.bind(this));

            // change datagrid to thumbnail large
            this.sandbox.on('sulu.list-toolbar.change.thumbnail-large', function() {
                this.sandbox.emit('husky.datagrid.view.change', 'thumbnail', listViews['thumbnailLarge']['thViewOptions']);
                this.sandbox.sulu.saveUserSetting(constants.listViewStorageKey, 'thumbnailLarge');
            }.bind(this));
        },

        deleteFile: function(path) {
            this.sandbox.util.save(
                '/admin/api/file/' + this.options.reference + path,
                'DELETE'
            ).then(
                function() {
                    this.sandbox.emit('husky.datagrid.record.remove', path);
                }.bind(this)
            );
        },

        /**
         * Renderes the component
         */
        render: function() {
            var url = '/admin/api/directory/' + this.options.reference + (!!this.options.path ? '/' + this.options.path : '');

            this.sandbox.dom.html(this.$el, '<div id="list-toolbar-container"></div><div id="file-list"></div>');

            // init list-toolbar and datagrid
            this.sandbox.sulu.initListToolbarAndList.call(this, 'files', '/admin/api/reference/fields',
                {
                    el: this.$find(constants.toolbarSelector),
                    parentTemplate: 'onlyAdd',
                    template: [
                        {
                            id: 'change',
                            icon: 'th-large',
                            position: 30,
                            itemsOption: {
                                markable: true
                            },
                            items: [
                                {
                                    id: 'small-thumbnails',
                                    title: this.sandbox.translate('sulu.list-toolbar.small-thumbnails'),
                                    callback: function() {
                                        this.sandbox.emit('sulu.list-toolbar.change.thumbnail-small');
                                    }.bind(this)
                                },
                                {
                                    id: 'big-thumbnails',
                                    title: this.sandbox.translate('sulu.list-toolbar.big-thumbnails'),
                                    callback: function() {
                                        this.sandbox.emit('sulu.list-toolbar.change.thumbnail-large');
                                    }.bind(this)
                                },
                                {
                                    id: 'table',
                                    title: this.sandbox.translate('sulu.list-toolbar.table'),
                                    callback: function() {
                                        this.sandbox.emit('sulu.list-toolbar.change.table');
                                    }.bind(this)
                                }
                            ]
                        },
                        {
                            id: 'settings',
                            icon: 'gear',
                            position: 40,
                            items: [
                                {
                                    title: this.sandbox.translate('symcloud.reference.edit'),
                                    disabled: true
                                },
                                {
                                    type: 'columnOptions'
                                }
                            ]
                        }
                    ],
                    instanceName: this.instanceName,
                    inHeader: true,
                    hasSearch: false
                },
                {
                    el: this.$find(constants.listSelector),
                    url: url + '?only-files=true&name-as-key=true',
                    resultKey: 'children',
                    searchFields: ['name'],
                    pagination: false,
                    sortable: false,
                    viewOptions: {
                        table: {
                            icons: [
                                {
                                    column: 'name',
                                    icon: 'pencil',
                                    rowClickSelect: false,
                                    callback: function() {
                                    }.bind(this)
                                },
                                {
                                    column: 'name',
                                    icon: 'trash-o',
                                    rowClickSelect: false,
                                    callback: this.deleteFile.bind(this)
                                }
                            ]
                        },
                        thumbnail: listViews[this.listView].thViewOptions || {}
                    },
                    view: listViews[this.listView].name
                }
            );
        }
    };
});
