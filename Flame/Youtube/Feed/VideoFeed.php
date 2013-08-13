<?php
/**
 * Class FeedApi
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 13.08.13
 */
namespace Flame\Youtube\Feed;

use Flame\Youtube\Factories\CurlFactory;
use Flame\Youtube\UrlsProvider;
use Flame\Youtube\Video;
use Kdyby\Curl\CurlException;
use Nette\Object;

class VideoFeed extends Object implements IVideoFeed
{

	/** @var  string */
	private $feedName;

	/** @var \Flame\Youtube\UrlsProvider  */
	private $urlsProvider;

	/** @var \Flame\Youtube\Factories\CurlFactory  */
	private $curlFactory;

	/**
	 * @param UrlsProvider $urlsProvider
	 * @param CurlFactory $curlFactory
	 * @param string $feedName
	 */
	function __construct(UrlsProvider $urlsProvider, CurlFactory $curlFactory, $feedName = 'most_popular')
	{
		$this->urlsProvider = $urlsProvider;
		$this->curlFactory = $curlFactory;
		$this->feedName = (string) $feedName;
	}

	/**
	 * @param string $feedName
	 * @return $this
	 */
	public function setFeedName($feedName)
	{
		$this->feedName = (string) $feedName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFeedName()
	{
		return $this->feedName;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->urlsProvider->getStandardFeedsUrl($this->getFeedName());
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
	public function getVideos()
	{
		$videos = array();
		if($res = $this->getApiResponse()) {
			$xml = @simplexml_load_string($res);
			if($xml && isset($xml->entry)) {
				foreach ($xml->entry as $entry) {
					if(!isset($entry->content->attributes()->src)) {
						continue;
					}

					$response = $this->createVideo($entry->content->attributes()->src)->getResponse();
					if(!$response || !isset($response->entry)) {
						continue;
					}

					$videos[] = array(
						'title' => $response->entry->title->{'$t'},
						'img' => $response->entry->{'media$group'}->{'media$thumbnail'}[2]->url,
						'duration' => $response->entry->{'media$group'}->{'yt$duration'}->seconds,
						'id' => $response->entry->{'media$group'}->{'yt$videoid'}->{'$t'}
					);
				}
			}
		}

		return $videos;
	}

	/**
	 * @param $id
	 * @return Video
	 */
	private function createVideo($id)
	{
		return new Video((string) $id);
	}
}