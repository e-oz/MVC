<?php
namespace Jamm\MVC\Controllers;

use Jamm\MVC\Factories\IControllersServiceLocator;

interface IRequireServiceLocator
{
	public function initServiceLocator(IControllersServiceLocator $ServiceLocator);
}