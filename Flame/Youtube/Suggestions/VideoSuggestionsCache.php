<?php
/**
 * Class VideoSuggestions
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 04.05.13
 */
namespace Flame\Youtube\Suggestions;

use Flame\Youtube\Cache\ICacheService;
use Nette\Object;
use Nette\Caching\Cache;
use Flame\Youtube\Cache\CacheHelpers;

class VideoSuggestionsCache extends Object implements ICacheService
{

	/** @var \Nette\Caching\Cache  */
	private $cache;

	/** @var \Flame\Youtube\Cache\CacheHelpers */
	private $cacheHelpers;

	/** @var \Flame\Youtube\Suggestions\IVideoSuggestions  */
	private $videoSuggestions;

	/** @var string */
	private $cacheExpiration = '+ 34 hours';

	/**
	 * @param IVideoSuggestions $videoSuggestions
	 * @param CacheHelpers $cacheHelpers
	 */
	public function __construct(IVideoSuggestions $videoSuggestions, CacheHelpers $cacheHelpers)
	{
		$this->cacheHelpers = $cacheHelpers;
		$this->videoSuggestions = $videoSuggestions;
		$this->cache = $cacheHelpers->createCache($this->getCacheNamespace());
	}

	/**
	 * @param $key
	 * @return $this
	 */
	public function setSearchKey($key)
	{
		$this->videoSuggestions->setSearchKey($key);
		return $this;
	}

	/**
	 * @param string $limit
	 * @return $this
	 */
	public function setExpiration($limit)
	{
		$this->cacheExpiration = (string) $limit;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCacheExpiration()
	{
		return $this->cacheExpiration;
	}

	/**
	 * @return mixed
	 */
	public function getCache()
	{
		$cacheKey = $this->cacheHelpers->getCacheKey($this->videoSuggestions->getUrl());
		if (!$res = $this->cache->load($cacheKey)) {
			$res = $this->videoSuggestions->getApiResult();
			$this->cache->save($cacheKey, $res, array(Cache::EXPIRATION => $this->cacheExpiration));
		}

		return $res;
	}

	/**
	 * @return string
	 */
	public function getCacheNamespace()
	{
		return 'Youtube.VideoSuggestions';
	}
}