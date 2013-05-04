<?php
/**
 * Class UrlService
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 04.05.13
 */
namespace Flame\Youtube;

use Kdyby\Curl\Request;
use Nette\Http\Url;
use Nette\Object;

abstract class UrlService extends Object
{
	/**
	 * @param $url
	 * @param array $post
	 * @return Request
	 */
	public function createCurl($url, $post = array())
	{
		return new Request($url, $post);
	}

	/**
	 * @param null $url
	 * @return Url
	 */
	public function createUrl($url = null)
	{
		return new Url($url);
	}
}