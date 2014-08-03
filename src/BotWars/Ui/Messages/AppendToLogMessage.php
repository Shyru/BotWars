<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Ui\Messages;


/**
 * Appends a string to a log.
 */
class AppendToLogMessage extends BaseMessage
{
	/** @var  string This can either be global, or the name of a bot participating in the game. */
	public $logName;
	/** @var  string The message that should be appended. */
	public $message;
} 