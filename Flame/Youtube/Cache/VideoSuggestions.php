<?php
/**
 * Class VideoSuggestions
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 04.05.13
 */
namespace Flame\Youtube\Cache;

use Nette\Caching\Cache;
use Flame\Youtube\Factories\CurlFactory;
use Flame\Youtube\Factories\UrlFactory;
use Flame\Youtube;

class VideoSuggestions extends Youtube\VideoSuggestions implements ICacheService
{

	const CACHE_NAMESPACE = 'Youtube.VideoSuggestions';

	/** @var \Nette\Caching\Cache  */
	private $cache;

	/** @var \Flame\Youtube\Cache\CacheHelpers */
	private $cacheHelpers;

	/** @var string */
	private $cacheExpiration = '+ 34 hours';

	/**
	 * @param CurlFactory $curlFactory
	 * @param UrlFactory $urlFactory
	 * @param CacheHelpers $cacheHelpers
	 */
	public function __construct(CurlFactory $curlFactory, UrlFactory $urlFactory, CacheHelpers $cacheHelpers)
	{
		parent::__construct($curlFactory, $urlFactory);

		$this->cacheHelpers = $cacheHelpers;
		$this->cache = $cacheHelpers->createCache(self::CACHE_NAMESPACE);
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
	 * @return mixed|NULL|string
	 */
	public function getResponse()
	{
		$cacheKey = $this->cacheHelpers->getCacheKey($this->getUrl());

		if (!$res = $this->cache->load($cacheKey)) {
			$res = parent::getResponse();
			$this->cache->save($cacheKey, $res, array(Cache::EXPIRATION => $this->cacheExpiration));
		}

		return $res;

	}
}