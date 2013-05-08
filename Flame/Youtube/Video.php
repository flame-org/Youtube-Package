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

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @param $id
	 */
	public function __construct($id)
	{
		if(Validators::isUrl($id)) {
			$this->url = $id;
		}else{
			$this->url = self::URL . $id;
		}
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
