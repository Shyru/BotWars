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
 * Signals the UI that a chat-entry should be added.
 * Chat-Entries are messages that the bots say (to each other).
 *
 */
class AddChatEntryMessage extends BaseMessage
{
	/** @var  string The team the bot is belonging to. Can either be "red", "blue", or "green" */
	public $team;
	/** @var  string The name of the bot that said the text. */
	public $botName;
	/** @var  string The text that the bot said. */
	public $text;
} 