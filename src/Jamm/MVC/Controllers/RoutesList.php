<?php
namespace Jamm\MVC\Controllers;

class RoutesList
{
	/** @var array */
	private $routes = [];

	public function __construct(array $routes = [])
	{
		$this->routes = $routes;
	}

	/**
	 * @param array $routes where key is a route and value is a name of controller class, which imlement IFixedCtorController
	 * @return bool
	 */
	public function setRoutesArray(array $routes)
	{
		$this->routes = $routes;
		return true;
	}

	public function setFromJSON($json)
	{
		$this->routes = json_decode($json, true);
		return true;
	}

	public function setFromJSONfile($json_file_path)
	{
		if (!($content = file_get_contents($json_file_path)))
		{
			return false;
		}
		return $this->setFromJSON($content);
	}

	public function setFromINI($ini)
	{
		$this->routes = parse_ini_string($ini, false);
		return true;
	}

	public function setFromINIfile($ini_file_path)
	{
		$this->routes = parse_ini_file($ini_file_path, false);
		return true;
	}

	/**
	 * @return array
	 */
	public function getRoutes()
	{
		return $this->routes;
	}
}
