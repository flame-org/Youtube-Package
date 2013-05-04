<?php
/**
 * Api.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame
 *
 * @date    01.01.13
 */

namespace Flame\Youtube;

class Video extends \Nette\Object
{

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @param $url
	 */
	public function __construct($url)
	{
		$this->url = $url;
	}

	/**
	 * @return null
	 */
	public function getVideoId()
	{
		return Utils::getVideoId($this->url);
	}

	/**
	 * @return mixed
	 */
	public function getResponse()
	{
		$url = 'http://gdata.youtube.com/feeds/api/videos/' . $this->getVideoId() . '?v=2&alt=json';
		return json_decode(file_get_contents($url));
	}

	/**
	 * @return mixed
	 */
	public function getResult()
	{
		$response = $this->getResponse();
		return $response->entry;
	}

	/**
	 * @return null
	 */
	public function getVideoImg()
	{
		$result = $this->getResult();
		if (isset($result->{'media$group'}->{'media$thumbnail'}[2]->{'url'})) {
			return $result->{'media$group'}->{'media$thumbnail'}[2]->{'url'};
		} else {
			return null;
		}
	}

	/**
	 * @return null
	 */
	public function getVideoDuration()
	{
		$result = $this->getResult();
		if (isset($result->{'media$group'}->{'media$thumbnail'}[2]->{'url'})) {
			return $result->{'media$group'}->{'yt$duration'}->{'seconds'};
		} else {
			return null;
		}
	}

	/**
	 * @return null
	 */
	public function getVideoTitle()
	{
		$result = $this->getResult();
		if (isset($result->{'title'}->{'$t'})) {
			return $result->{'title'}->{'$t'};
		} else {
			return null;
		}
	}


	/**
	 * @return string
	 */
	public function getVideoUrl()
	{
		return $this->url;
	}
}
