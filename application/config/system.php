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
$system['debug'] = true;
$system['debug_log'] = false;


/*
*--------------------------------------------------------------------------
* Database Functionality
*--------------------------------------------------------------------------
*
* db_use (true/false) enables database access (nearly always on)
* setup (true/false) enables database autoconfiguration on or off
*
*/
$system['db_use'] = true;
$system['setup'] = true;


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
$system['caching'] = 0;
$system['cache_lifetime'] = 1800;
$system['speedup'] = false;


/*
*--------------------------------------------------------------------------
* Security
*--------------------------------------------------------------------------
*
* security (true/false) Enables the login system for this site
*                       Related database tables are auth and groups
*
*/
$system['security'] = true;
$system['security_session'] = 'ws_default';
$system['security_table'] = 'users';


/*
*--------------------------------------------------------------------------
* Timeouts
*--------------------------------------------------------------------------
*
*/
$system['timeout_history'] = '7776000';
$system['timeout_logs'] = '7776000';
$system['timeout_sessions'] = '3600';




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

