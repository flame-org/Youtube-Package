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

class VideoSuggestions extends UrlService
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

	/** @var \Nette\Http\Url  */
	private $urlService;

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
		$this->urlService->appendQuery(array('q' => (string)$key));
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
	 * @return string
	 */
	public function getResponse()
	{
		try {
			$curl = $this->createCurl($this->getUrl());
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