<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\WebInterface;


/**
 * Please add documentation for LogHandler!
 */
class LogHandler extends \Monolog\Handler\AbstractProcessingHandler
{
	private $buffer;
	private $bufferSize;
	private $callbacks;

	/**
	 * Constructs a new LogHandler.
	 *
	 * @param int $_bufferSize The bufferSize determines how many logmessages should be buffered
	 */
	function __construct($_bufferSize=10)
	{
		$this->bufferSize=$_bufferSize;
		$this->callbacks=array();
		parent::__construct();
	}


	/**
	 * Writes the record down to the log of the implementing handler
	 *
	 * @param array $_record
	 * @return void
	 */
	protected function write(array $_record)
	{
		$this->buffer[]=$_record["formatted"];
		if (count($this->buffer)>$this->bufferSize)
		{ //our buffer is bigger than the buffer-size we must make it smaller
			array_shift($this->buffer);
		}
		foreach ($this->callbacks as $callback)
		{
			call_user_func($callback,$_record["formatted"]);
		}
	}

	/**
	 * Register a callback that gets called for every added log line.
	 *
	 * @param callable $_callback The callback that should be called.
	 */
	public function registerCallback($_callback)
	{
		$this->callbacks[]=$_callback;
	}

	/**
	 * Get the buffered log messages.
	 *
	 * @return array The buffered log messages
	 */
	public function getBuffer()
	{
		return $this->buffer;
	}


}