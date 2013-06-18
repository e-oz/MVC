<?php
namespace Jamm\MVC\Factories;

use Jamm\MVC\Controllers\IRequireServiceLocator;

class ControllerBuilder implements IControllerBuilder
{
	private $ServiceLocator;

	public function __construct(IControllersServiceLocator $ServiceLocator)
	{
		$this->ServiceLocator = $ServiceLocator;
	}

	public function buildController($Controller)
	{
		if ($Controller instanceof IRequireServiceLocator)
		{
			/** @var IRequireServiceLocator $Controller */
			$Controller->initServiceLocator($this->ServiceLocator);
		}
	}
}
