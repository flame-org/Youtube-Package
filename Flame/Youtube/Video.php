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

use Nette\Utils\Validators;

class Video extends \Nette\Object
{

	const URL = 'http://www.youtube.com/watch?v=';

	/** @var  string */
	private $videoId;

	/**
	 * @param $id
	 */
	public function __construct($id)
	{
		$this->videoId = $id;
	}

	/**
	 * @return null|string
	 */
	public function getVideoId()
	{
		if(Validators::isUrl($this->videoId)) {
			return Utils::getVideoId($this->videoId);
		}else{
			return $this->videoId;
		}

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
		if(Validators::isUrl($this->videoId)) {
			return $this->videoId;
		}else{
			return self::URL . $this->videoId;
		}
	}
}
