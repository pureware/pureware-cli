{{mainJsContent | raw}}
{%- if elementName is defined %}
{% set elementPath = '/module/sw-cms/elements/' ~ elementName|u.snake|slug %}
{%- if elementPath not in mainJsContent %}
import './module/sw-cms/elements/{{elementName|u.snake|slug}}';
{% endif -%}
{% endif -%}

{%- if blockName is defined %}
{% set path = '/module/sw-cms/blocks/' ~ blockCategory|u.snake|slug  ~ '/' ~ blockName|u.snake|slug %}
{%- if path not in mainJsContent %}
import './module/sw-cms/blocks/{{blockCategory|u.snake|slug}}/{{blockName|u.snake|slug}}';
{% endif -%}
{% endif -%}

