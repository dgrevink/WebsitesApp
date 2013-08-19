<?php
/*
*--------------------------------------------------------------------------
* Debug Options
*--------------------------------------------------------------------------
*
* debug (true/false) enables on screen debug output
* debug_log (true/false) enables debug output to file
*           default files are application/logs/site.log (php errors)
*                         and application/logs/sql.log  (sql calls)
*
*/
$system['debug'] = {if $system.debug}true{else}false{/if};
$system['debug_log'] = {if $system.debug_log}true{else}false{/if};


/*
*--------------------------------------------------------------------------
* Database Functionality
*--------------------------------------------------------------------------
*
* db_use (true/false) enables database access (nearly always on)
* setup (true/false) enables database autoconfiguration on or off
*
*/
$system['db_use'] = {if $system.db_use}true{else}false{/if};
$system['setup'] = {if $system.setup}true{else}false{/if};


/*
*--------------------------------------------------------------------------
* Performance
*--------------------------------------------------------------------------
*
* caching (2 (true)/0 (false)) enables smarty caching; should be set to 2 (caching) or 0 (no caching)
* cache_lifetime (seconds) how long (in seconds should the cache stored before being invalidated on the server)
* speedup (true/false) enables the following optimisation:
*                      - css and javascript minifaction
*                      - all css files are combined in one
*                      - all javascript files are combined in one
*                      - css, javascript and page content is gzipped
*
*/
$system['caching'] = {if $system.caching}2{else}0{/if};
$system['cache_lifetime'] = {$system.cache_lifetime};
$system['speedup'] = {if $system.speedup}true{else}false{/if};


/*
*--------------------------------------------------------------------------
* Security
*--------------------------------------------------------------------------
*
* security (true/false) Enables the login system for this site
*                       Related database tables are auth and groups
*
*/
$system['security'] = {if $system.security}true{else}false{/if};
$system['security_session'] = '{$system.security_session}';
$system['security_table'] = '{$system.security_table}';


/*
*--------------------------------------------------------------------------
* Timeouts
*--------------------------------------------------------------------------
*
*/
$system['timeout_history'] = '{$system.timeout_history}';
$system['timeout_logs'] = '{$system.timeout_logs}';
$system['timeout_sessions'] = '{$system.timeout_sessions}';




$system['reasonable_languages'] = array('fr', 'en', 'es', 'zh', 'ja', 'ko');


/*
*--------------------------------------------------------------------------
* Legacy
*--------------------------------------------------------------------------
*
* These parameters should be erased someday
*
*/
$system['dictionaries'] = array( 'Languages' ); 

