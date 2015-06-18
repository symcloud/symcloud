define(function() {

    'use strict';

    var constants = {
            formContainerId: 'symcloud-file'
        },

        namespace = 'symcloud.files.',

        /**
         * listens on and navigates to file list of reference at parent folder
         * @event symcloud.files.list
         */
        NAVIGATE_FILE_LIST = function() {
            return createEventName.call(this, 'list');
        },

        /**
         * listens on and navigates to category form
         * @event symcloud.files.form
         */
        NAVIGATE_FILE_FORM = function() {
            return createEventName.call(this, 'form');
        },

        /**
         * listens on and navigates to category form for adding
         * @event symcloud.files.form-add
         */
        NAVIGATE_FILE_FORM_ADD = function() {
            return createEventName.call(this, 'form-add');
        },

        /** returns normalized event names */
        createEventName = function(postFix) {
            return namespace + postFix;
        };

    return {

        /**
         * Initializes the component
         */
        initialize: function() {
            this.bindCustomEvents();
            this.render();
        },

        /**
         * Renderes the component
         */
        render: function() {
            if (this.options.display === 'form') {
                this.renderForm();
            } else {
                throw 'display type wrong';
            }
        },

        /**
         * Binds custom related events
         */
        bindCustomEvents: function() {
            // navigate to list
            this.sandbox.on(NAVIGATE_FILE_LIST.call(this), this.navigateToList.bind(this));
            // navigate to form
            this.sandbox.on(NAVIGATE_FILE_FORM.call(this), this.navigateToForm.bind(this));
            // navigate to form for adding
            this.sandbox.on(NAVIGATE_FILE_FORM_ADD.call(this), this.navigateToAddForm.bind(this));
        },

        /**
         * Navigates to list
         */
        navigateToList: function() {
            // TODO route
            // this.sandbox.emit('sulu.router.navigate', 'settings/categories', true, true);
        },

        /**
         * Navigates to form
         * @param categoryId {Number|String} the id of model to edit
         * @param tab {String} the tab to route to
         */
        navigateToForm: function(categoryId, tab) {
            // TODO route
            // default tab is details
            // tab = (!!tab) ? tab : 'details';
            // this.sandbox.emit('sulu.router.navigate', 'settings/categories/edit:' + categoryId + '/' + tab, true, true);
        },

        /**
         * Navigates to the form for adding a new category
         * @param path {String} of the parent-category
         * @param tab {String} the tab to route to
         */
        navigateToAddForm: function(path, tab) {
            // default tab is details
            // tab = (!!tab) ? tab : 'details';
            // var route = 'settings/categories/new';
            // route = ((!!parentId) ? route + '/' + parentId : route) + '/' + tab;
            // this.sandbox.emit('sulu.router.navigate', route, true, true);
        },

        /**
         * Renders the from for add and edit
         */
        renderForm: function() {
            this.sandbox.stop('#' + constants.formContainerId);

            var $form = this.sandbox.dom.createElement('<div id="' + constants.formContainerId + '"/>'),
                action = function(data) {
                    this.sandbox.start([
                        {
                            name: 'file-edit/form@symcloudsulu',
                            options: {
                                el: $form,
                                data: data,
                                add: this.options.add,
                                reference: this.options.reference,
                                path: this.options.path
                            }
                        }
                    ]);
                }.bind(this), url;

            this.html($form);

            if (!!this.options.add) {
                return action({name: this.sandbox.translate('symcloud.new-file'), content: ''});
            }

            url = '/admin/api/file/' + this.options.reference + this.options.path + '?content';

            this.sandbox.util.load(url, {}, 'text').then(function(content) {
                action({name: this.options.path.split('/').reverse()[0], content: content});
            }.bind(this));
        }
    };
});
