define(function() {

    'use strict';

    var defaults = {},

        constants = {
            formSelector: '#file-form',
            nameSelector: '#file-name',
            contentSelector: '#file-content'
        },

        template = function(add, data) {
            return [
                '<form id="file-form">',
                '   <div class="form-group">',
                '       <label for="file-name">Name</label>',
                '       <input class="form-element" id="file-name" ' + (add ? 'placeholder' : 'readonly="readonly" value') + '="' + data.name + '"/>',
                '   </div>',
                '   <div class="form-group" style="margin-top: 20px;">',
                '       <label for="file-content">Inhalt</label>',
                '       <textarea class="form-element" id="file-content">' + data.content + '</textarea>',
                '   </div>',
                '</form>'
            ].join('');
        };

    return {

        view: true,

        layout: {
            navigation: {
                collapsed: true
            }
        },

        /**
         * Initializes the collections list
         */
        initialize: function() {
            // extend defaults with options
            this.options = this.sandbox.util.extend(true, {}, defaults, this.options);
            this.saved = true;

            this.bindCustomEvents();
            this.render();

            this.bindDomEvents();
        },

        /**
         * Binds custom related events
         */
        bindCustomEvents: function() {
            this.sandbox.on('sulu.header.back', function() {
                // TODO back
            }.bind(this));

            this.sandbox.on('sulu.header.toolbar.save', this.save.bind(this));
        },

        /**
         * Renders the component
         */
        render: function() {
            this.setHeaderInfos();

            this.html(template(!!this.options.add, this.options.data));
        },

        /**
         * Binds DOM-Events
         */
        bindDomEvents: function() {
            // activate save-button on key input
            this.sandbox.dom.on(constants.formSelector, 'change keyup', function() {
                if (this.saved === true) {
                    this.sandbox.emit('sulu.header.toolbar.state.change', 'edit', false);
                    this.saved = false;
                }
            }.bind(this));
        },

        /**
         * Sets all the Info contained in the header
         * like breadcrumb or title
         */
        setHeaderInfos: function() {
            // TODO breadcrumb

            this.sandbox.emit('sulu.header.set-title', this.options.data.name);
            this.sandbox.emit('sulu.header.set-breadcrumb', [
                {title: 'symcloud.files'},
                {title: this.options.data.name}
            ]);
        },

        /**
         * Saves the details-tab
         */
        save: function() {
            // TODO save
            var content = this.sandbox.dom.val(constants.formSelector + ' ' + constants.contentSelector),
                path = this.options.path || '',
                url;

            if (!!this.options.add) {
                path = path + '/' + this.sandbox.dom.val(constants.formSelector + ' ' + constants.nameSelector);
            }

            url = '/admin/api/file/' + this.options.reference + path;

            this.sandbox.emit('sulu.header.toolbar.item.loading', 'save-button');
            this.sandbox.util.save(url, 'POST', {content: content}).then(function() {
                this.saved = true;
                this.sandbox.emit('husky.toolbar.header.item.disable', 'save-button');
            }.bind(this));
        }
    };
});
