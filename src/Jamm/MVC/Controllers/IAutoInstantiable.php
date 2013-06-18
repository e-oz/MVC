<?php
namespace Jamm\MVC\Controllers;

interface IAutoInstantiable extends IController
{
	/** exactly empty constructor */
	public function __construct();
}
