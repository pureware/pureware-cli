{% set elementPath = '/module/sw-cms/elements/' ~ elementName|u.snake|slug %}
{{mainJsContent | raw}}
{%- if elementPath not in mainJsContent %}
import './module/sw-cms/elements/{{elementName|u.snake|slug}}';
{% endif -%}
