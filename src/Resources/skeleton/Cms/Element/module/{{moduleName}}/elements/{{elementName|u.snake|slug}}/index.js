import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement(
    {
        name: '{{elementName|u.snake|slug}}',
        label: '{{moduleName}}.elements.{{elementName|u.camel}}.label',
        component: '{{moduleName}}-el-{{elementName|u.snake|slug}}',
        configComponent: '{{moduleName}}-el-config-{{elementName|u.snake|slug}}',
        previewComponent: '{{moduleName}}-el-preview-{{elementName|u.snake|slug}}',
        defaultConfig: {
            yourCustomConfigKey: {
                source: 'static',
                value: ''
            }
        }
    }
);
