<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Arena\Tiles;


/**
 * RockTile is a tile that is filled with a rock.
 * The rock is large, which means that it is full-height and one
 * cannot should over it.
 */
class RockTile extends BaseTile
{
	function isFilled()
	{
		return true;
	}

	function height()
	{
		return self::HEIGHT_FULL;
	}

} 