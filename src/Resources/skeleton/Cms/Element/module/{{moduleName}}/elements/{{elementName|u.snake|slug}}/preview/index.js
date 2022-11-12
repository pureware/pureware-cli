import template from './{{moduleName}}-el-preview-{{elementName|u.snake|slug}}.html.twig';
import './{{moduleName}}-el-preview-{{elementName|u.snake|slug}}.scss';

Shopware.Component.register('{{moduleName}}-el-preview-{{elementName|u.snake|slug}}', {
    template
});
