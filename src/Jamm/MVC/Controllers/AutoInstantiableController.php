<?php
namespace Jamm\MVC\Controllers;

use Jamm\MVC\Factories\IControllersServiceLocator;

abstract class AutoInstantiableController implements IAutoInstantiable
{
	protected $ServiceLocator;

	final public function __construct() { }

	public function setServiceLocator(IControllersServiceLocator $ServiceLocator)
	{
		$this->ServiceLocator = $ServiceLocator;
	}

	public function getServiceLocator()
	{
		return $this->ServiceLocator;
	}
}
