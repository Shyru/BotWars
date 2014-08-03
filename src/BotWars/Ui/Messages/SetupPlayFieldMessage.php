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
 * This message is sent at the beginning of a game and contains all information for
 * a UI to draw the playfield.
 */
class SetupPlayFieldMessage extends BaseMessage
{
	public $width;
	public $height;
	public $tileSize;
	public $tiles;
}