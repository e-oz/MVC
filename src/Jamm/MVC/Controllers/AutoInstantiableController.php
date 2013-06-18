<?php
namespace Jamm\MVC\Controllers;

abstract class AutoInstantiableController implements IAutoInstantiable
{
	final public function __construct() { }
}
