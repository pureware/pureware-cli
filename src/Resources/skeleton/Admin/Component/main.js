{{mainJsContent | raw}}
{%- if componentName is defined %}
{% set path = '/module/' ~ moduleName|u.snake|slug  ~ '/components/' ~ componentName|u.snake|slug %}
{%- if path not in mainJsContent %}
import './module/{{moduleName|u.snake|slug}}/components/{{componentName|u.snake|slug}}';
{% endif -%}
{% endif -%}


