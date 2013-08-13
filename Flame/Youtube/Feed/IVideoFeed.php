<?php
/**
 * Class IFeed
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 13.08.13
 */
namespace Flame\Youtube\Feed;

interface IVideoFeed
{

	/**
	 * @return string
	 */
	public function getUrl();

	/**
	 * @return string
	 */
	public function getFeedName();

	/**
	 * @return string|null
	 */
	public function getApiResponse();

	/**
	 * @return array
	 */
	public function getVideos();
} 