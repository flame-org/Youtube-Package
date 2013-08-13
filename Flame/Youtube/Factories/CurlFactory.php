<?php
/**
 * Class CurlFactory
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 13.08.13
 */
namespace Flame\Youtube\Factories;

use Kdyby\Curl\Request;
use Nette\Object;

class CurlFactory extends Object
{

	/** @var array  */
	private $post = array();

	/**
	 * @param array $post
	 * @return $this
	 */
	public function setPost(array $post)
	{
		$this->post = $post;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getPost()
	{
		return $this->post;
	}

	/**
	 * @param $url
	 * @return Request
	 */
	public function create($url)
	{
		return new Request((string) $url, $this->post);
	}
}