						<!-- promotions & events -->
						<div class="promotions">
							<h2>Promotions 2011-2012</h2>
{section name=promos loop=$promos}
							<div class="block">
								<div class="block-holder">
									<div class="alignleft">
										<img src="../../../{$promos[promos].image}" width="304" height="126" alt="{$promos[promos].title}"/>
									</div>
									<div class="block-content">
										<h3>{$promos[promos].title}</h3>
										<em>{$promos[promos].subtitle}</em>
										<p>{$promos[promos].blurb} <a href="/fr/tarifs/promotions/+{$promos[promos].titleseo}">[Suite]</a></p>
									</div>
									<div class="price">
										<strong>{$promos[promos].promotext}<span>{$promos[promos].promoprice}</span></strong>
										<a href="/fr/tarifs/promotions/+{$promos[promos].titleseo}">Voir les détails</a>
									</div>
								</div>
							</div>
{/section}

{if $featured}
{else}
<br/>
<br/>
<br/>
{/if}

						</div>
