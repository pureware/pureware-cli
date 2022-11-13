{{mainJsContent | raw}}
{%- if prefixedModuleName is defined %}
{% set path = '/module/' ~ prefixedModuleName|u.snake|slug %}
{%- if path not in mainJsContent %}
import './module/{{prefixedModuleName|u.snake|slug}}';
{% endif -%}
{% endif -%}


