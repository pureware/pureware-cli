{{mainJsContent | raw}}
{%- if componentName is defined %}
{% set path = '/module/' ~ componentName|u.snake|slug%}
{%- if path not in mainJsContent %}
import './module/{{componentName|u.snake|slug}}-override';
{% endif -%}
{% endif -%}


