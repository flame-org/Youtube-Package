<?php
/**
 * Downloader.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    21.03.13
 */

namespace Flame\Youtube\Video;

use Flame\Youtube\UrlService;
use Nette\Diagnostics\Debugger;
use Flame\Youtube\DownloaderException;

class DownloaderApi extends UrlService
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
		$links = array();
		$allLinks = $this->getLinks($videoId);

		if (count($allLinks)) {
			if(isset($allLinks[18])) {
				$links[] = array(
					'url' => $allLinks[18],
					'format' => 'mp4',
					'quality' => 'Medium',
					'size' => '480x360',
					);
			}

			if(isset($allLinks[22])) {
				$links[] = array(
					'url' => $allLinks[22],
					'format' => 'mp4',
					'quality' => 'High',
					'size' => '1280x720',
				);
			}

			if(isset($allLinks[37])) {
				$links[] = array(
					'url' => $allLinks[37],
					'format' => 'mp4',
					'quality' => 'High',
					'size' => '1920x1080',
				);
			}

			if(isset($allLinks[33])) {
				$links[] = array(
					'url' => $allLinks[33],
					'format' => 'mp4',
					'quality' => 'High',
					'size' => '4096x230',
				);
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
	 * @param $videoId
	 * @return string
	 * @throws \Flame\Youtube\DownloaderException
	 */
	protected function getResponse($videoId)
	{

		try {

			$conn = $this->createCurl($this->getVideoUrl($videoId));
			$conn->setUserAgent('Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
			return $conn->get()->getResponse();

		} catch (\Kdyby\Curl\CurlException $ex) {
			throw new DownloaderException($ex->getMessage());
		}

	}

}
