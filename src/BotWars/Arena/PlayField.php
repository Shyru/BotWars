<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Arena;
use BotWars\Arena\Tiles\BaseTile;
use BotWars\Arena\Tiles\FillableTile;
use BotWars\Ui\Messages\SetupPlayFieldMessage;
use BotWars\Ui\SendMessageInterface;
use BotWars\WebInterface\WebSocketServer;


/**
 * Please add documentation for Playfield!
 */
class PlayField
{
	private $log;
	private $ui;
	/** @var BaseTile[][] */
	private $tiles;

	function __construct(SendMessageInterface $_ui, \Monolog\Logger $_log,$_options)
	{
		$this->ui=$_ui;
		$this->log=$_log;
		$defaultOptions=array(
			"width"=>10,
			"height"=>10,
			"tileSize"=>20
		);
		$options=array_merge($defaultOptions,$_options);
		$this->log->info("Size is ".$options["width"]."x".$options["height"]);
		$this->tiles=array();
		for ($x=0; $x<$options["width"]; $x++)
		{
			$column=array();
			for ($y=0; $y<$options["height"]; $y++)
			{
				$column[]=new FillableTile();
			}
			$this->tiles[]=$column;
		}


		$this->ui->sendInitializationMessage(new SetupPlayFieldMessage(array(
			"width"=>$options["width"],
			"height"=>$options["height"],
			"tileSize"=>$options["tileSize"])
		));
	}

	/**
	 * @param int $_x The x coordinate
	 * @param int $_y The y coordinate
	 * @param bool $_advanced If advanced information should be returned.
	 * @return TileInfo|null
	 */
	public function getFieldInfo($_x, $_y, $_advanced=false)
	{
		if (isset($this->tiles[$_x]) && isset($this->tiles[$_x][$_y]))
		{
			/** @var BaseTile $field */
			$field=$this->tiles[$_x][$_y];
			return $field->getInfo();
		}
		else return null;
	}
} 