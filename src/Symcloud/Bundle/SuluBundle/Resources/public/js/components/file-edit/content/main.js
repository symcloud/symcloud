define(function () {

    'use strict';

    return {

        header: function () {
            return {
                tabs: {
                    url: '/admin/content-navigations?alias=symcloud-file'
                },
                toolbar: {
                    template: 'default',
                    languageChanger: true
                }
            };
        }

    };
});
