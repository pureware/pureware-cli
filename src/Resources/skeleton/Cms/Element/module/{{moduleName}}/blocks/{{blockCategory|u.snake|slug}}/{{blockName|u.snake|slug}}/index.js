import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: '{{blockName|u.snake|slug}}',
    category: '{{blockCategory|u.snake|slug}}',
    label: '{{moduleName}}.blocks.{{blockName|u.camel}}.label',
    component: '{{moduleName}}-block-{{blockName|u.snake|slug}}',
    previewComponent: '{{moduleName}}-preview-{{blockName|u.snake|slug}}',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed'
    },
    slots: {
        main: 'text'
    }
});
