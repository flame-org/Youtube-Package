<?php
/**
 * Class IVideoSuggestions
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 13.08.13
 */
namespace Flame\Youtube\Suggestions;

interface IVideoSuggestions
{

	/**
	 * @return string
	 */
	public function getUrl();

	/**
	 * @return string|null
	 */
	public function getApiResponse();

	/**
	 * @return array
	 */
	public function getApiResult();

	/**
	 * @param string $key
	 * @return $this
	 */
	public function setSearchKey($key);
} 