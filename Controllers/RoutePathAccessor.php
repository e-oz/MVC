<?php
namespace Jamm\MVC\Controllers;
trait RoutePathAccessor
{
	private $route_path;

	public function getRoutePath()
	{
		return $this->route_path;
	}

	public function setRoutePath($route_path)
	{
		$this->route_path = trim($route_path, '/');
	}
}
