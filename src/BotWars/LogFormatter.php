<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars;


/**
 * Monolog logformatter that formats log messages a bit cleaner than the default
 * log formatter of monolog.
 */
class LogFormatter extends \Monolog\Formatter\LineFormatter
{
	public function __construct()
	{
		parent::__construct("%datetime% %level_name_padded% %channel%: %message%\n");
	}

	public function format(array $record)
	{
		$output = parent::format($record);

		$output=str_replace("%level_name_padded%",str_pad($record["level_name"],7," "),$output);

		return $output;
	}
}