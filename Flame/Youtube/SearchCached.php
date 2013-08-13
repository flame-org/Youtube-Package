<?php
/**
 * SearchCached.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    20.04.13
 */

namespace Flame\Youtube;

use Flame\Caching\CacheProvider;
use Flame\Youtube\Factories\CurlFactory;
use Flame\Youtube\Factories\UrlFactory;

class SearchCached extends Search
{

	const CACHE_NAMESPACE = 'Youtube.Search';

	/** @var string */
	private $cacheExpiration = '+ 34 hours';

	/** @var \Nette\Caching\Cache */
	private $cache;

	/**
	 * @param CurlFactory $curlFactory
	 * @param UrlFactory $urlFactory
	 * @param CacheProvider $cacheProvider
	 */
	public function __construct(CurlFactory $curlFactory, UrlFactory $urlFactory, CacheProvider $cacheProvider)
	{
		parent::__construct($curlFactory, $urlFactory);

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
	 * @return mixed|NULL|\SimpleXMLElement
	 */
	public function getResponse()
	{
		$cacheKey = $this->getCacheKey();

		if (!$res = $this->cache->load($cacheKey)) {
			$res = parent::getResponse();
			$this->cache->save($cacheKey, $res, array(\Nette\Caching\Cache::EXPIRATION => $this->cacheExpiration));
		}

		return $res;

	}

	/**
	 * @return string
	 */
	protected function getCacheKey()
	{
		return md5((string)$this->getUrl());
	}

}
