<?php

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}


class Ads extends WSController {
	function redirect() {
		if (isset($this->params[0])) {
			$id = is_numeric($this->params[0])?$this->params[0]:-1;
			$brecord = MyActiveRecord::FindById('banners', $id);
			
			$bcrecord = $brecord->find_children('bannerclicks');

			if (!$bcrecord) {
				$bcrecord = MyActiveRecord::Create('bannerclicks');
				$bcrecord->ip = getRealIpAddr();
				$bcrecord->banners_id = $id;
				$bcrecord->date = date("Y-m-d H:i:s");
				$bcrecord->save();
				$brecord->clicks++;
				$brecord->save();
			}
			else {
				$bcrecord = array_pop($bcrecord);
				$now = time() - 24*60*60;
				$then = strtotime($bcrecord->date);
				if ($now > $then) {
					$bcrecord->date = date("Y-m-d H:i:s");
					$bcrecord->save();
					$brecord->clicks++;
					$brecord->save();
				}
			}
			
			header('Location: ' . $brecord->url );
		}
	}
}

