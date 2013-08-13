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
	 * @return null|string
	 */
	public static function getVideoId($url)
	{
		preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
		return (isset($matches[0])) ? $matches[0] : null;
	}

}
