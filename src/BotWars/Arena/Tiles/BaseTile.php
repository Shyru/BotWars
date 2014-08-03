<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Arena\Tiles;
use BotWars\Arena\TileInfo;


/**
 * The base class for all tiles.
 */
class BaseTile
{
	/** This means the tile is empty. */
	const HEIGHT_NONE=0;
	/** This means the tile is half-full. It cannot be entered, but you can shoot over it. */
	const HEIGHT_MEDIUM=1;
	/** This means the tile is full. It cannot be entered, and you cannot shoot through it. */
	const HEIGHT_FULL=2;

	/**
	 * Returns true if the tile is filled.
	 */
	function isFilled()
	{
		return false;
	}

	/**
	 * Returns the fill-height of the tile.
	 *
	 * @return int
	 */
	function height()
	{
		return self::HEIGHT_NONE;
	}

	/**
	 * Returns a TileInfo object for the tile.
	 * This is used from the Radar-API for bots.
	 *
	 * @param bool $_advanced Whether advanced information should be returned.
	 * @return TileInfo
	 */
	function getInfo($_advanced=false)
	{
		$fieldInfo=new TileInfo();
		$fieldInfo->movable=!$this->isFilled();
		$fieldInfo->height=$this->height();
		return $fieldInfo;
	}

	/**
	 * Returns the url of the image that should be used when drawing the tile.
	 *
	 * @return string
	 */
	function getImageUrl()
	{
		return "/static/images/tile_sand_a.jpg";
	}

	function buildUiTileInfo()
	{
		return (object)array("imageUrl"=>$this->getImageUrl());
	}



} 