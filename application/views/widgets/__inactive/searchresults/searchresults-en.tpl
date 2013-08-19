{section name=results loop=$results}
<h3><a href='{$results[results].path}' title='{$results[results].title}'>{$results[results].title}</a> {if $results[results].path != ''}({$results[results].path}){/if}</h3>
<p>{$results[results].description}</p>
{sectionelse}
<p>No results found.</p>
{/section}
<br/>
<h3>{$result_text}</h3>

