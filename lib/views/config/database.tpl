<?php

/*
**
** Database Parameters
**
**
*/


{section name=items loop=$items}
$database['tables'][] = array(
	'name' => '{$items[items].name}',
	'sql'  => "
{$items[items].sql}
"
);
{/section}

?>