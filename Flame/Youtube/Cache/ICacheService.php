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
	 * @param string $limit
	 * @return $this
	 */
	public function setExpiration($limit);
}