import './page/{{prefixedModuleName}}-list';
import './page/{{prefixedModuleName}}-detail';
import './page/{{prefixedModuleName}}-create';
{% for locale in snippetLanguages %}
import {{locale|u.camel}} from './snippet/{{locale}}.json';
{% endfor %}

Shopware.Module.register('{{prefixedModuleName}}', {
    type: 'plugin',
    name: '{{moduleName|u.camel.title}}',
    title: '{{prefixedModuleName}}.general.mainMenuItemGeneral',
    description: 'sw-property.general.descriptionTextModule',
    color: '{{moduleColor}}',
    icon: 'default-shopping-paper-bag-product',

    snippets: {
        {%- for locale in snippetLanguages -%}

        '{{locale|slug}}': {{locale|u.camel}}{% if not loop.last %}, {% endif %}

        {%- endfor -%}
    },
    routes: {
        list: {
            component: '{{prefixedModuleName}}-list',
            path: 'list'
        },
        detail: {
            component: '{{prefixedModuleName}}-detail',
            path: 'detail/:id',
            meta: {
                parentPath: '{{prefix|u.snake|slug}}.{{moduleName|u.snake|slug}}.list'
            }
        },
        create: {
            component: '{{prefixedModuleName}}-create',
            path: 'create',
            meta: {
                parentPath: '{{prefix|u.snake|slug}}.{{moduleName|u.snake|slug}}.list'
            }
        }
    },

    navigation: [{
        label: '{{prefixedModuleName}}.general.mainMenuItemGeneral',
        color: '{{moduleColor}}',
        path: '{{prefix|u.snake|slug}}.{{moduleName|u.snake|slug}}.list',
        icon: 'default-shopping-paper-bag-product',
        parent: '{{navigationParent}}',
        position: 100
    }]
});
