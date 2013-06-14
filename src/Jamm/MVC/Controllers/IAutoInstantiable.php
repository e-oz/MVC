<?php
namespace Jamm\MVC\Controllers;

use Jamm\MVC\Factories\IServiceContainer;

interface IAutoInstantiable extends IController
{
	public function setServiceContainer(IServiceContainer $ServiceContainer);
}
