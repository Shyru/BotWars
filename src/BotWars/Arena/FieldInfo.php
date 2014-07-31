<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Arena;


/**
 * Contains generic information about a field.
 */
class FieldInfo
{
	/** @var  bool Wether a bot can move into this field.  */
	public $movable;

	/** @var  bool Does the field contain a bot? */
	public $containsBot;

	/** @var string The identifier of the team the bot belongs to. */
	public $botInfo;

	/** @var int The height the field is filled with, if any. Look at BaseField::HEIGHT_* */
	public $height;

} 