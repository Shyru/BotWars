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
 * Contains generic information about a tile.
 * This is used from the Radar-API for bots.
 */
class TileInfo
{
	/** @var  bool Whether a bot can move into this tile.  */
	public $movable;

	/** @var  bool Does the tile contain a bot? */
	public $containsBot;

	/** @var \BotWars\Bot\RadarInfo The bot radar info. */
	public $botRadarInfo;

	/** @var int The height the field is filled with, if any. Look at BaseField::HEIGHT_* */
	public $height;

} 