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
 * The BaseMessage provides functionality needed for all messages.
 *
 */
class BaseMessage
{

	/**
	 * Constructs a message with the given values.
	 *
	 * @param array $_values
	 * @throws \InvalidArgumentException If not all needed values are present.
	 */
	function __construct($_values)
	{
		$memberValues=get_object_vars($this);
		foreach ($_values as $key => $value)
		{
			if (array_key_exists($key, $memberValues))
			{ //ok, this is a known field, set the value
				$this->{$key}=$value;
			}
			else
			{
				throw new \InvalidArgumentException("Cannot set unknown value '$key'! Look at the class definition of '".get_class($this)."'!");
			}
		}
		//now check if all values are set
		$updatedMemberValues=get_object_vars($this);
		foreach ($updatedMemberValues as $key => $value)
		{
			if ($value===null)
			{
				throw new \InvalidArgumentException("Needed value '$key' was not set in constructor!");
			}
		}
	}

	/**
	 * Converts the message into a json-string that can be sent to a Ui.
	 *
	 */
	function __toString()
	{
		$obj=(object)$this;
		$fullClassName=get_class($this);
		$obj->type=substr($fullClassName,strrpos($fullClassName,"\\")+1);

		return json_encode($obj);
	}
} 