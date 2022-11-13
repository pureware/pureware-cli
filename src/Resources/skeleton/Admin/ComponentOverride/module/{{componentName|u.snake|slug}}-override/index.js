import template from './{{componentName|u.snake|slug}}.html.twig';
import './{{componentName|u.snake|slug}}.scss';

Shopware.Component.override('{{componentName|u.snake|slug}}', {
    template
});
