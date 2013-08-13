<?php
/**
 * Class ICacheService
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 04.05.13
 */
namespace Flame\Youtube\Cache;

interface ICacheService
{

	/**
	 * @return mixed
	 */
	public function getCache();

	/**
	 * @return string
	 */
	public function getCacheExpiration();

	/**
	 * @return string
	 */
	public function getCacheNamespace();
}