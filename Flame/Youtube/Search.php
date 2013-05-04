<?php
/**
 * Search.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame
 *
 * @date    01.01.13
 */

namespace Flame\Youtube;

use Nette\Http\Url;

class Search extends UrlService
{

	const URL = 'https://gdata.youtube.com/feeds/api/videos';

	/** @var array */
	private $default = array(
		'orderby' => 'relevance',
		'start-index' => '1',
		'max-results' => '10',
		'v' => '2',
		'strict' => false
	);

	/** @var \Nette\Http\Url */
	protected $urlService;

	public function __construct()
	{
		$this->urlService = $this->appendDefault(new Url(self::URL), $this->default);
	}

	/**
	 * @param $key
	 * @return $this
	 */
	public function setSearchKey($key)
	{
		$this->urlService->setQuery(array('q' => (string)$key));
		return $this;
	}

	/**
	 * @param $limit
	 * @return $this
	 */
	public function setMaxResults($limit)
	{
		$this->urlService->setQuery(array('max-results' => (string) $limit));
		return $this;
	}

	/**
	 * @param $key
	 * @return $this
	 */
	public function setOrderBy($key)
	{
		$this->urlService->setQuery(array('orderby' => (string) $key));
		return $this;
	}

	/**
	 * @param $index
	 * @return $this
	 */
	public function setStartIndex($index)
	{
		$this->urlService->setQuery(array('start-index' => ((int) $index) ? (int) $index : 1));
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return (string) $this->urlService;
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function getResponse()
	{
		return @file_get_contents($this->getUrl());
	}

	/**
	 * @return object
	 */
	public function getResult()
	{
		return $this->parseResponse($this->getResponse());
	}

	/**
	 * @param $response
	 * @return object
	 */
	protected function parseResponse($response)
	{
		return @simplexml_load_string($response);
	}

}
