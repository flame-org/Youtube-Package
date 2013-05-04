<?php
/**
 * Class CacheHelpers
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 04.05.13
 */
namespace Flame\Youtube\Cache;

use Flame\Caching\CacheProvider;
use Nette\Object;

class CacheHelpers extends Object
{

	/** @var \Flame\Caching\CacheProvider  */
	private $cacheProvider;

	/**
	 * @param CacheProvider $cacheProvider
	 */
	public function __construct(CacheProvider $cacheProvider)
	{
		$this->cacheProvider = $cacheProvider;
	}

	/**
	 * @param string $url
	 * @return string
	 */
	public function getCacheKey($url)
	{
		return md5((string) $url);
	}

	/**
	 * @param null $namespace
	 * @return \Nette\Caching\Cache
	 */
	public function createCache($namespace = null)
	{
		return $this->cacheProvider->createCache(CacheProvider::PERSIST_DIR, $namespace);
	}

}