<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Arena;
use BotWars\Arena\Fields\BaseField;
use BotWars\Arena\Fields\FillableField;
use BotWars\WebInterface\WebSocketServer;


/**
 * Please add documentation for Playfield!
 */
class PlayField
{
	private $log;
	private $webSocketServer;
	/** @var BaseField[][] */
	private $fields;

	function __construct(WebsocketServer $_webSocketServer,\Monolog\Logger $_log,$_options)
	{
		$this->webSocketServer=$_webSocketServer;
		$this->log=$_log;
		$defaultOptions=array(
			"width"=>10,
			"height"=>10,
			"fieldSize"=>20
		);
		$options=array_merge($defaultOptions,$_options);
		$this->log->info("Size is ".$options["width"]."x".$options["height"]);
		$this->fields=array();
		for ($x=0; $x<$options["width"]; $x++)
		{
			$column=array();
			for ($y=0; $y<$options["height"]; $y++)
			{
				$column[]=new FillableField();
			}
			$this->fields[]=$column;
		}


		$this->webSocketServer->addInitMessage(array(
			"type"=>"initField",
			"width"=>$options["width"],
			"height"=>$options["height"],
		    "fieldSize"=>$options["fieldSize"])
		);
	}

	/**
	 * @param int $_x The x coordinate
	 * @param int $_y The y coordinate
	 * @param bool $_advanced If advanced information should be returned.
	 * @return FieldInfo|null
	 */
	public function getFieldInfo($_x, $_y, $_advanced=false)
	{
		if (isset($this->fields[$_x]) && isset($this->fields[$_x][$_y]))
		{
			/** @var BaseField $field */
			$field=$this->fields[$_x][$_y];
			return $field->getInfo();
		}
		else return null;
	}
} 