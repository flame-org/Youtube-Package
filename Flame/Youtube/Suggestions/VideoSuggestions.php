<?php
/**
 * Class VideoSuggestions
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 04.05.13
 */
namespace Flame\Youtube\Suggestions;

use Flame\Youtube\UrlsProvider;
use Kdyby\Curl\CurlException;
use Nette\Http\Url;
use Nette\Object;
use Flame\Youtube\Factories\CurlFactory;
use Flame\Youtube\Factories\UrlFactory;

class VideoSuggestions extends Object implements IVideoSuggestions
{

	/** @var array  */
	private $default = array(
		'hl' => "en",
		'ds' => "yt",
		'client' => "youtube",
		'hjson' => "t",
		'cp' => 1
	);

	/** @var \Flame\Youtube\Factories\UrlFactory  */
	private $urlFactory;

	/** @var \Flame\Youtube\Factories\CurlFactory  */
	private $curlFactory;

	/** @var \Flame\Youtube\UrlsProvider  */
	private $urlsProvider;

	/**
	 * @param CurlFactory $curlFactory
	 * @param UrlFactory $urlFactory
	 * @param UrlsProvider $urlsProvider
	 */
	public function __construct(CurlFactory $curlFactory, UrlFactory $urlFactory, UrlsProvider $urlsProvider)
	{
		$this->curlFactory = $curlFactory;
		$this->urlFactory = $urlFactory;
		$this->urlsProvider = $urlsProvider;
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
			->setUrl($this->urlsProvider->getVideoSuggestionsUrl())
			->create()
			->setQuery($this->default);
	}

	/**
	 * @return null|string
	 */
	public function getApiResponse()
	{
		try {
			return $this->curlFactory->create($this->getUrl())->get()->getResponse();
		}catch (CurlException $ex) {}
	}

	/**
	 * @return array
	 */
	public function getApiResult()
	{
		if($response = $this->getApiResponse()) {
			$response = json_decode($response);

			if(isset($response[1])){
				$response = (array) $response[1];
				if(count($response)) {
					foreach ($response as $k => $item) {
						if(isset($item[0])) {
							$response[$k] = $item[0];
						} else {
							unset($response[$k]);
						}
					}

					return $response;
				}
			}
		}

		return array();
	}


}