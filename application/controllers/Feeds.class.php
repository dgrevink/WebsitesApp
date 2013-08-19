<?php

/**
 * Website Feed Handler
 *
 * @package		admin
 * @package 	site
 * @subpackage 	feeds
 * @author		David Grevink
 * @link		http://websitesapp.com/
 */

WSLoader::load_dictionary('languages');

class Feeds extends WSController {

	function get() {
		if (count($this->params) != 2) die();
		if (!is_numeric($this->params[1])) die();
		$table = MyActiveRecord::FindById('tabledefinitions', $this->params[1]);
		if (!$table) die();
		if ($table->rss != 1) die();
		$language = $this->params[0];

		$code = array();
		
		$code[] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
		$code[] = "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">";
		$code[] = "<channel>";
		$code[] = "  <title>" . $this->config->get('company') . ' - ' . html_entity_decode(WSDLanguages::getLanguageName( $language ), ENT_QUOTES, 'UTF-8') /* . ' - ' . $table->title*/ . "</title>";
		$code[] = "  <link>http://" . $_SERVER['HTTP_HOST'] . "</link>";
		$code[] = "  <description>" . $table->description . "</description>";
		$code[] = "  <language>" . $language . "</language>";
		$code[] = "  <generator>Websitesapp.com</generator>";
		$code[] = "  <atom:link href=\"http://" . $_SERVER['HTTP_HOST'] . "/feeds/get/" . $language ."/" . $table->id ."\" rel=\"self\" type=\"application/rss+xml\" />";
		foreach(MyActiveRecord::FindAll($table->name, "language = '" . $language . "'", 'ddate desc') as $record) {
			$code[] = "    <item>";
			$code[] = "      <title>" . html_entity_decode(strip_tags($record->title), ENT_QUOTES, 'UTF-8') . "</title>";
			$code[] = "      <link>http://" . $_SERVER['HTTP_HOST'] . "/" . $language . "/" . $table->rss_sublink . $record->titleseo . "</link>";
			$code[] = "      <guid>http://" . $_SERVER['HTTP_HOST'] . "/" . $language . "/" . $table->rss_sublink . $record->titleseo . "</guid>";
//			$code[] = "      <description><![CDATA[" . str_replace("\n", "", html_entity_decode(strip_tags($record->content), ENT_QUOTES, 'UTF-8')) . "]]></description>";
			$code[] = "      <description><![CDATA[" . str_replace("\n", "", html_entity_decode(($record->contents), ENT_QUOTES, 'UTF-8')) . "]]></description>";
//			$code[] = "      <content:encoded><![CDATA[" . html_entity_decode(strip_tags($record->content), ENT_QUOTES, 'UTF-8') . "]]></content:encoded>";
			$code[] = "      <pubDate>" . date('r',strtotime($record->ddate)) . "</pubDate>";
			$code[] = "    </item>";
		}
		$code[] = "  </channel>";
		$code[] = "</rss>";
		
		header("Content-Type: application/rss+xml");
		
		echo implode("\n", $code);
		
$code = <<<RSSFEED
<?xml version=\"1.0\"?><rss version=\"2.0\">
   <channel>
      <title>Liftoff News</title>
      <link>http://liftoff.msfc.nasa.gov/</link>
      <description>Liftoff to Space Exploration.</description>
      <language>en-us</language>
      <pubDate>Tue, 10 Jun 2003 04:00:00 GMT</pubDate>
      <lastBuildDate>Tue, 10 Jun 2003 09:41:01 GMT</lastBuildDate>
      <docs>http://blogs.law.harvard.edu/tech/rss</docs>
      <generator>Weblog Editor 2.0</generator>
      <managingEditor>editor@example.com</managingEditor>
      <webMaster>webmaster@example.com</webMaster>
      <item>
         <title>Star City</title>
         <link>http://liftoff.msfc.nasa.gov/news/2003/news-starcity.asp</link>
         <description>How do Americans get ready to work with Russians aboard the International Space Station? They take a crash course in culture, language and protocol at Russia's &lt;a href="http://howe.iki.rssi.ru/GCTC/gctc_e.htm"&gt;Star City&lt;/a&gt;.</description>
         <pubDate>Tue, 03 Jun 2003 09:39:21 GMT</pubDate>
         <guid>http://liftoff.msfc.nasa.gov/2003/06/03.html#item573</guid>
      </item>
      <item>
         <description>Sky watchers in Europe, Asia, and parts of Alaska and Canada will experience a &lt;a href="http://science.nasa.gov/headlines/y2003/30may_solareclipse.htm"&gt;partial eclipse of the Sun&lt;/a&gt; on Saturday, May 31st.</description>
         <pubDate>Fri, 30 May 2003 11:06:42 GMT</pubDate>
         <guid>http://liftoff.msfc.nasa.gov/2003/05/30.html#item572</guid>
      </item>
      <item>
         <title>The Engine That Does More</title>
         <link>http://liftoff.msfc.nasa.gov/news/2003/news-VASIMR.asp</link>
         <description>Before man travels to Mars, NASA hopes to design new engines that will let us fly through the Solar System more quickly.  The proposed VASIMR engine would do that.</description>
         <pubDate>Tue, 27 May 2003 08:37:32 GMT</pubDate>
         <guid>http://liftoff.msfc.nasa.gov/2003/05/27.html#item571</guid>
      </item>
      <item>
         <title>Astronauts' Dirty Laundry</title>
         <link>http://liftoff.msfc.nasa.gov/news/2003/news-laundry.asp</link>
         <description>Compared to earlier spacecraft, the International Space Station has many luxuries, but laundry facilities are not one of them.  Instead, astronauts have other options.</description>
         <pubDate>Tue, 20 May 2003 08:56:02 GMT</pubDate>
         <guid>http://liftoff.msfc.nasa.gov/2003/05/20.html#item570</guid>
      </item>
   </channel>
</rss>
RSSFEED;
		
	}
	
}
