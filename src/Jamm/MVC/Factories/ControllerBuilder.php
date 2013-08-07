<?php
namespace Jamm\MVC\Factories;

use Jamm\MVC\Controllers\IRequireServiceLocator;
use Jamm\MVC\Controllers\IRequireSessionAuthenticator;

class ControllerBuilder implements IControllerBuilder
{
	protected $ServiceLocator;

	public function __construct(ControllersServiceLocator $ServiceLocator)
	{
		$this->ServiceLocator = $ServiceLocator;
	}

	public function buildController($Controller)
	{
		if ($Controller instanceof IRequireServiceLocator) {
			/** @var IRequireServiceLocator $Controller */
			$Controller->initServiceLocator($this->ServiceLocator);
		}
		if ($Controller instanceof IRequireSessionAuthenticator) {
			/** @var IRequireSessionAuthenticator $Controller */
			$Controller->initSessionAuthenticator($this->ServiceLocator->getSessionAuthenticator());
		}
	}
}
