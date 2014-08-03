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
 * Represents the play-field where a war happens.
 *
 */
class PlayField
{
	private $log;
	private $ui;
	/** @var BaseTile[][] */
	private $tiles;

	/**
	 * Constructs a new PlayField.
	 *
	 * @param SendMessageInterface $_ui The interface to the ui, so that the playfield can talk to the ui.
	 * @param \Monolog\Logger $_log The log so that the playfield may log something.
	 * @param array $_options The options of the playfield. Currently this supports the following values:
	 * - width: The width of the playfield. Defaults to 10
	 * - height: The height of the playfield. Defaults to 10
	 * - tileSize: The size of the tiles in pixels. Defaults to 40
	 */
	function __construct(SendMessageInterface $_ui, \Monolog\Logger $_log,$_options)
	{
		$this->ui=$_ui;
		$this->log=$_log;
		$defaultOptions=array(
			"width"=>10,
			"height"=>10,
			"tileSize"=>40
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
			"tileSize"=>$options["tileSize"],
			"tiles"=>$this->buildUiTiles()
		)));
	}

	private function buildUiTiles()
	{
		$tiles=array();
		for ($x=0; $x<count($this->tiles); $x++)
		{
			$column=array();
			for ($y=0; $y<count($this->tiles[$x]); $y++)
			{
				$column[]=$this->tiles[$x][$y]->buildUiTileInfo();
			}
			$tiles[]=$column;
		}
		return $tiles;
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