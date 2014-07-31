<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Server;


/**
 * Extends the reactive stack to inject the global loop into the silex application aswell so that we can use it from there
 */
class Stack extends \Kpacha\ReactiveSilex\Stack
{
	function __construct($_app)
	{
		parent::__construct($_app);
		$_app['loop']=$this['loop'];
	}
}