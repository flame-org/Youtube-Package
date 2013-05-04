<?php
/**
 * Downloader.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    21.03.13
 */

namespace Flame\Youtube\Video;

use Nette\Diagnostics\Debugger;
use Flame\Youtube\DownloaderException;

class DownloaderApi extends \Nette\Object
{

	const YTURL = 'http://www.youtube.com/watch?v=';

	/**
	 * @param $videoId
	 * @return array
	 * @throws \Flame\Youtube\DownloaderException
	 */
	public function getLinks($videoId)
	{

		if (!$html = $this->getResponse($videoId))
			throw new DownloaderException('No response from Youtube. Try it again.');

		if (strstr($html, 'verify-age-thumb'))
			throw new DownloaderException("Adult cideo detected");

		if (strstr($html, 'das_captcha'))
			throw new DownloaderException("Captcha found please run on diffrent server");

		if (!preg_match('/stream_map=(.[^&]*?)&/i', $html, $match))
			throw new DownloaderException("Error during looking for download URL's");

		if (!preg_match('/stream_map=(.[^&]*?)(?:\\\\|&)/i', $html, $match))
			throw new DownloaderException('Youtube error occured');

		$fmt_url = urldecode($match[1]);
		$urls = explode(',', $fmt_url);

		// Diffent types
		//		$typeMap[13] = array("13", "3GP", "Low Quality - 176x144");
		//		$typeMap[17] = array("17", "3GP", "Medium Quality - 176x144");
		//		$typeMap[36] = array("36", "3GP", "High Quality - 320x240");
		//		$typeMap[5]  = array("5", "FLV", "Low Quality - 400x226");
		//		$typeMap[6]  = array("6", "FLV", "Medium Quality - 640x360");
		//		$typeMap[34] = array("34", "FLV", "Medium Quality - 640x360");
		//		$typeMap[35] = array("35", "FLV", "High Quality - 854x480");
		//		$typeMap[43] = array("43", "WEBM", "Low Quality - 640x360");
		//		$typeMap[44] = array("44", "WEBM", "Medium Quality - 854x480");
		//		$typeMap[45] = array("45", "WEBM", "High Quality - 1280x720");


		$foundArray = array();

		foreach ($urls as $url) {
			if (preg_match('/itag=([0-9]+)/', $url, $tm) && preg_match('/sig=(.*?)&/', $url, $si) && preg_match('/url=(.*?)&/', $url, $um)) {
				$u = urldecode($um[1]);
				$foundArray[$tm[1]] = $u . '&signature=' . $si[1];
			}
		}

		return $foundArray;
	}

	/**
	 * @param $videoId
	 * @return array
	 */
	public function getMP4Links($videoId)
	{
		//		$typeMap[18] = array("18", "MP4", "Medium Quality - 480x360");
		//		$typeMap[22] = array("22", "MP4", "High Quality - 1280x720");
		//		$typeMap[37] = array("37", "MP4", "High Quality - 1920x1080");
		//		$typeMap[33] = array("38", "MP4", "High Quality - 4096x230");

		$links = array();
		$indexs = array(18, 22, 37, 33);
		$allLinks = $this->getLinks($videoId);
		if (count($allLinks)) {
			foreach ($indexs as $index) {
				if (isset($allLinks[$index]))
					$links[] = $allLinks[$index];
			}
		}

		return $links;
	}

	/**
	 * @param $videoId
	 * @return string
	 */
	protected function getVideoUrl($videoId)
	{
		if (!\Nette\Utils\Validators::isUrl($videoId)) {
			return self::YTURL . $videoId;
		}

		return $videoId;
	}

	/**
	 * @param $url
	 * @return \Kdyby\Curl\Request
	 */
	protected function createCurlService($url)
	{
		return new \Kdyby\Curl\Request($url);
	}

	/**
	 * @param $videoId
	 * @return string
	 * @throws \Flame\Youtube\DownloaderException
	 */
	protected function getResponse($videoId)
	{

		try {

			Debugger::timer(__METHOD__);

			$conn = $this->createCurlService($this->getVideoUrl($videoId));
			$conn->setUserAgent('Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
			$html = $conn->get()->getResponse();

			Debugger::barDump(Debugger::timer(__METHOD__), __METHOD__);

			return $html;

		} catch (\Kdyby\Curl\CurlException $ex) {
			throw new DownloaderException($ex->getMessage());
		}

	}

}
