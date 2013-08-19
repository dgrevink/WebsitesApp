<div class='sub_menu_fixed sub_menu_fixed_wide'>
<h3>Derni&egrave;res parutions</h3>
{literal}
<style>.sub_menu_fixed_wide img { width: 50px !important; height: auto !important; }</style>
{/literal}
<table border="0">
<tbody>
{section name=publications loop=$publications}
<tr>
<td><strong>{$publications[publications].tag_image}</strong></td>
<td><strong><a href="+{$publications[publications].pubtype_titleseo}#{$publications[publications].id}">{$publications[publications].pubtype_title} N°{$publications[publications].number} {$publications[publications].title}</a></strong></td>
</tr>
{/section}
</tbody>
</table>
</div>
