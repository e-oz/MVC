<?php
namespace Jamm\MVC\Controllers;
interface IRouter
{
	public function getControllerForRequest();

	public function setFallbackController(IController $Controller);

	public function addRouteForController($route, IController $Controller);

	public function addRouteCallbackFunction($route, $callback_function);

	public function getControllerForRoute($route);
}
