<?php
/**
 * exceptions.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    21.03.13
 */

namespace Flame\Youtube;

interface Exception
{

}

class DownloaderException extends \RuntimeException implements Exception
{

}