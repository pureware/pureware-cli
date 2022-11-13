import template from './{{moduleName}}-block-{{blockName|u.snake|slug}}.html.twig';
import './{{moduleName}}-block-{{blockName|u.snake|slug}}.scss';

Shopware.Component.register('{{moduleName}}-block-{{blockName|u.snake|slug}}', {
    template
});
