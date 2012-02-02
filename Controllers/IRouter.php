<?php
namespace Jamm\MVC\Controllers;

interface IRouter
{
	public function getControllerForRequest();

	public function setFallbackController(IController $Controller);

	public function addRouteForController($route, IController $Controller);

	public function getControllerForRoute($route);

	public function getRouteOfController(IController $Controller);
}
