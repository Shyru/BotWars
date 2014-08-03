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
 * This message is sent whenever the status of the bot is updated.
 *
 */
class BotUpdatedMessage extends BaseMessage
{
	/** @var  int The x-coordinate of the tile the bot is located at. */
	public $x;
	/** @var  int the y-coordinate of the tile the bot is located at. */
	public $y;
	/** @var  \BotWars\Bot\Status  The current status of the bot. */
	public $botStatus;
	/** @var  float The direction the engine (feet) of the boot faces to. (In degrees) */
	public $engineDirection;
	/** @var  float The direction the weapons (arms) face to. (In degrees) */
	public $weaponDirection;
	/** @var  float The direction the head faces to. (In degrees) */
	public $headDirection;

} 