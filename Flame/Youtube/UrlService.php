<?php
/**
 * Class UrlService
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 04.05.13
 */
namespace Flame\Youtube;

use Nette\Http\Url;
use Nette\Object;

abstract class UrlService extends Object
{

	/**
	 * @param Url $url
	 * @param array $default
	 * @return Url
	 */
	public function appendDefault(Url $url, array $default)
	{
		if(count($default)){
			foreach ($default as $k => $default) {
				$url->appendQuery(array($k => $default));
			}
		}

		return $url;
	}

}