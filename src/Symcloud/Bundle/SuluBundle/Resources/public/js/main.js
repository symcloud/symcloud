/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require.config({
    paths: {
        symcloudsulu: '../../symcloudsulu/js'
    }
});

define(function() {
    return {

        name: "Symcloud Sulu Bundle",

        initialize: function(app) {

            'use strict';

            var sandbox = app.sandbox;

            app.components.addSource('symcloudsulu', '/bundles/symcloudsulu/js/components');

            sandbox.mvc.routes.push({
                route: 'symcloud/path::reference*path',
                callback: function(reference, path) {
                    this.html('<div data-aura-component="file-list@symcloudsulu" data-aura-reference="' + reference + '" data-aura-path="' + path + '"/>');
                }
            });

            sandbox.mvc.routes.push({
                route: 'symcloud/path::reference*path/add',
                callback: function(reference, path) {
                    this.html('<div data-aura-component="file-edit/content@symcloudsulu" data-aura-reference="' + reference + '" data-aura-path="' + path + '" data-aura-content="details" data-aura-add="true"/>');
                }
            });

            sandbox.mvc.routes.push({
                route: 'symcloud/path::reference*path/edit/:content',
                callback: function(reference, path, content) {
                    this.html('<div data-aura-component="file-edit/content@symcloudsulu" data-aura-reference="' + reference + '" data-aura-path="' + path + '" data-aura-content="' + content + '" data-aura-add="false"/>');
                }
            });
        }
    };
});
