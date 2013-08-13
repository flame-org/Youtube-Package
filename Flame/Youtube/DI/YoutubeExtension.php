<?php
/**
 * Class YoutubeExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 13.08.13
 */
namespace Flame\Youtube\DI;

use Nette\DI\CompilerExtension;

class YoutubeExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('curlFactory'))
			->setClass('Flame\Youtube\Factories\CurlFactory');

		$builder->addDefinition($this->prefix('urlFactory'))
			->setClass('Flame\Youtube\Factories\UrlFactory');

		$builder->addDefinition($this->prefix('cacheHelpers'))
			->setClass('Flame\Youtube\Cache\CacheHelpers');
	}
} 