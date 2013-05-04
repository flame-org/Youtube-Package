<?php
/**
 * Utils.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame
 *
 * @date    01.01.13
 */

namespace Flame\Youtube;

class Utils extends \Nette\Object
{

	/**
	 * @param $url
	 * @return null
	 */
	public static function getVideoId($url)
	{
		$url = new \Nette\Http\Url($url);
		$query = $url->getQuery();
		parse_str($query, $parsed);
		return (isset($parsed['v'])) ? $parsed['v'] : null;
	}

}
