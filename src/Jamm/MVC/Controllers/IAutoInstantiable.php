<?php
namespace Jamm\MVC\Controllers;

use Jamm\MVC\Factories\IControllersServiceLocator;

interface IAutoInstantiable extends IController
{
	/** exactly empty constructor */
	public function __construct();

	public function setServiceLocator(IControllersServiceLocator $ServiceLocator);
}
