import template from './{{moduleName}}-preview-{{blockName|u.snake|slug}}.html.twig';
import './{{moduleName}}-preview-{{blockName|u.snake|slug}}.scss';

Shopware.Component.register('{{moduleName}}-preview-{{blockName|u.snake|slug}}', {
    template
});
