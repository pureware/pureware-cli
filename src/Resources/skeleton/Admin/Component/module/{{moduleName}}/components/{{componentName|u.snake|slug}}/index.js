import template from './{{componentName|u.snake|slug}}.html.twig';
import './{{componentName|u.snake|slug}}.scss';

Shopware.Component.register('{{componentName|u.snake|slug}}', {
    template
});
