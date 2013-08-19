<div class='site-item'>
<div class='title'><h3>Derniers Tweets</h3></div>
<div class='meat'>
<div class='ws-twitter'>
{section name=twits loop=$twits}
<div class='ws-twitter-entry'>
<p><img src='{$twits[twits].avatar}'/>{$twits[twits].text}<span class="date">{$twits[twits].created_at|date_format:"%A, %B %e, %Y"}</span></p>
</div>
{/section}
</div>
</div>
</div>