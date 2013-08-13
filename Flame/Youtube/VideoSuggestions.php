<?php
/**
 * Class VideoSuggestions
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 04.05.13
 */
namespace Flame\Youtube;

use Kdyby\Curl\CurlException;
use Nette\Http\Url;
use Nette\Object;
use Flame\Youtube\Factories\CurlFactory;
use Flame\Youtube\Factories\UrlFactory;

class VideoSuggestions extends Object
{

	const URL = 'http://suggestqueries.google.com/complete/search';

	/** @var array  */
	private $default = array(
		'hl' => "en",
		'ds' => "yt",
		'client' => "youtube",
		'hjson' => "t",
		'cp' => 1
	);

	/** @var  UrlFactory */
	private $urlFactory;

	/** @var  CurlFactory */
	private $curlFactory;

	/**
	 * @param CurlFactory $curlFactory
	 * @param UrlFactory $urlFactory
	 */
	public function __construct(CurlFactory $curlFactory, UrlFactory $urlFactory)
	{
		$this->curlFactory = $curlFactory;
		$this->urlFactory = $urlFactory;
	}

	/**
	 * @param $key
	 * @return $this
	 */
	public function setSearchKey($key)
	{
		$this->default['q'] = (string) $key;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return (string) $this->urlFactory
			->setUrl(self::URL)
			->create()
			->setQuery($this->default);
	}

	/**
	 * @return string
	 */
	public function getResponse()
	{
		try {
			$curl = $this->curlFactory->create($this->getUrl());
			return $curl->get()->getResponse();
		}catch (CurlException $ex) {}
	}

	/**
	 * @return array
	 */
	public function getResult()
	{
		if($response = $this->getResponse()) {
			$response = json_decode($response);
			if(isset($response[1])){
				$result = array_map(function ($item) {
					return $item[0];
				}, $response[1]);

				return $result;
			}
		}
	}


}