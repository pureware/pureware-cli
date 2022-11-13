import template from './{{moduleName}}-el-config-{{elementName|u.snake|slug}}.html.twig';

Shopware.Component.register('{{moduleName}}-el-config-{{elementName|u.snake|slug}}', {
    template,

    mixins: [
        'cms-element'
    ],

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('{{elementName|u.snake|slug}}');
        },
    }
});
