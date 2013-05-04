<?php
/**
 * Downloader.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    24.03.13
 */

namespace Flame\Youtube\Video;

use Flame\Caching\CacheProvider;

class DownloaderApiCached extends DownloaderApi
{

	const CACHE_NAMESPACE = 'Youtube.Video';

	/** @var string */
	private $cacheExpiration = '+ 5 minute';

	/** @var \Nette\Caching\Cache */
	private $cache;

	/**
	 * @param \Flame\Caching\CacheProvider $cacheProvider
	 */
	public function __construct(CacheProvider $cacheProvider)
	{
		$this->cache = $cacheProvider->createCache(CacheProvider::PERSIST_DIR, self::CACHE_NAMESPACE);
	}

	/**
	 * @param $limit
	 * @return SearchCached
	 */
	public function setCacheExpiration($limit)
	{
		$this->cacheExpiration = (string)$limit;
		return $this;
	}

	/**
	 * @param $videoId
	 * @return array|mixed|NULL
	 */
	public function getLinks($videoId)
	{
		$cacheKey = $this->getCacheKey($videoId);

		if (!$value = $this->cache->load($cacheKey)) {
			$value = parent::getLinks($videoId);
			$this->cache->save($cacheKey, $value, array(\Nette\Caching\Cache::EXPIRE => $this->cacheExpiration));
		}

		return $value;
	}

	/**
	 * @param $videoId
	 * @return string
	 */
	private function getCacheKey($videoId)
	{
		return md5($videoId);
	}

}
