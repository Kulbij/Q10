<?php namespace App\Controllers;

class Ajax
{
	public $agents = [
	    'Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.2.15 Version/10.10',
	    'Opera/9.64 (Windows NT 5.1; U; ru) Presto/2.1.1',
	    'Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4',
	    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
	'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
	'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
	    'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1',
	    'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0',
	    'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36'
	];

	public static function run($config = [])
	{
		$url = $config['url'];

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, false);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_NOBODY, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, self::getAgent());
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$page = curl_exec($ch);
		curl_close($ch);
		unset($ch);
		

		return $page;
	}

	public static function getAgent()
	{
		return 'Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.2.15 Version/10.10';
	}

}