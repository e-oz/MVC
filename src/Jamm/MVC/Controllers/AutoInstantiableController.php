<?php
namespace Jamm\MVC\Controllers;

use Jamm\MVC\Factories\IServiceContainer;

abstract class AutoInstantiableController implements IAutoInstantiable
{
	protected $ServiceContainer;

	public function setServiceContainer(IServiceContainer $ServiceContainer)
	{
		$this->ServiceContainer = $ServiceContainer;
	}
}
