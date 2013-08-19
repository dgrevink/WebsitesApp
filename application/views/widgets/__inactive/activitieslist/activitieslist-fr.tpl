{if $featured}
						<div class="events">
{else}
						<div class="events" style='margin-top: -28px;'>
<br/>
<br/>
							<h2>Toutes nos activités</h2>
{/if}
{section name=activities loop=$activities}
							<div class="block-events">
							<!--
								<div class="info">
									<div class="date">
										<strong>{$activities[activities].day}</strong>
										<em>{$activities[activities].month} </em>
									</div>
									<a href="/fr/activites/">Voir toutes nos activités</a>
								</div>
								-->
								<div class="block-holder">
									{if $activities[activities].image != ''}
									<div class="img-holder">
										<img src="../../../{$activities[activities].image}" width="76" height="80" alt="{$activities[activities].title}"/>
									</div>
									{/if}
									<div class="block-content">
										<h3>{$activities[activities].title}</h3>
										<em>{$activities[activities].fulldate}</em>
										<p>{$activities[activities].blurb} <a href="/fr/activites/fiche/+{$activities[activities].titleseo}">Lisez la suite &raquo; </a></p>
									</div>
									<div class="clearer"></div>
								</div>
							</div><!-- block-events -->
							 {if !$smarty.section.activities.last}
<div class='separator'></div>
  {/if}
							
{/section}
{if $featured}
{else}
<br/>
<br/>
{/if}

						</div>
