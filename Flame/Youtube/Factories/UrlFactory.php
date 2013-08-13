<?php
/**
 * Class UrlFactory
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 13.08.13
 */
namespace Flame\Youtube\Factories;

use Nette\Http\UrlScript;
use Nette\Object;

class UrlFactory extends Object
{

	/** @var null  */
	private $url = null;

	/**
	 * @param null $url
	 * @return $this
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}

	/**
	 * @return null
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @return UrlScript
	 */
	public function create()
	{
		return new UrlScript($this->url);
	}


} 