<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Arena\Fields;


/**
 * Please add documentation for Rock!
 */
class Rock extends BaseField
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