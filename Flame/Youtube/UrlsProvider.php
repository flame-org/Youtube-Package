<?php
/**
 * Class UrlsProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 13.08.13
 */
namespace Flame\Youtube;

use Nette\Object;

class UrlsProvider extends Object
{

	/**
	 * @param $feedName
	 * @return string
	 */
	public function getStandardFeedsUrl($feedName)
	{
		return 'https://gdata.youtube.com/feeds/api/standardfeeds/' . (string) $feedName;
	}

	/**
	 * @return string
	 */
	public function getVideoSuggestionsUrl()
	{
		return 'http://suggestqueries.google.com/complete/search';
	}

} 