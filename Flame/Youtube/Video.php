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

use Kdyby\Curl\CurlException;
use Kdyby\Curl\Request;
use Nette\Utils\Validators;

class Video extends UrlService
{

	/** @var  string */
	private $videoId;

	/** @var  string */
	private $respose;

	/**
	 * @param $id
	 */
	public function __construct($id)
	{
		if(Validators::isUrl($id)) {
			$this->videoId = Utils::getVideoId($id);
		}else{
			$this->videoId = $id;
		}
	}

	/**
	 * @return null|string
	 */
	public function getVideoId()
	{
		return $this->videoId;

	}

	/**
	 * @param bool $invalidate
	 * @return mixed|string
	 */
	public function getResponse($invalidate = false)
	{
		if($invalidate === true || $this->respose === null) {

			$url = 'http://gdata.youtube.com/feeds/api/videos/' . $this->getVideoId() . '?v=2&alt=json';

			try {

				$curl = new Request($url);
				$res = $curl->get()->getResponse();

				if($res) {
					$this->respose = json_decode($res);
					return $this->respose;
				}

			}catch (CurlException $ex) {}
		}else{
			return $this->respose;
		}
	}

	/**
	 * @return mixed
	 */
	public function getResult()
	{
		if($response = $this->getResponse()) {
			return $response->entry;
		}
	}

	/**
	 * @return null
	 */
	public function getVideoImg()
	{
		$result = $this->getResult();
		if (isset($result->{'media$group'}->{'media$thumbnail'}[2]->{'url'})) {
			return $result->{'media$group'}->{'media$thumbnail'}[2]->{'url'};
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
		}
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->videoId;
	}
}
