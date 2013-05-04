<?php
/**
 * Class VideoSuggestions
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 04.05.13
 */
namespace Flame\Youtube;

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
		return @file_get_contents($this->getUrl());
	}

	/**
	 * @return array
	 */
	public function getResult()
	{
		$response = json_decode($this->getResponse());
		if(isset($response[1])){
			$result = array_map(function ($item) {
				return $item[0];
			}, $response[1]);

			return $result;
		}
	}


}