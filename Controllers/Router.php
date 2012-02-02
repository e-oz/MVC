<?php
namespace Jamm\MVC\Controllers;

/**
 * Class to route query from Request to Response
 * Should be extended and method getRequestHandler should be implemented
 */
class Router implements IRouter
{
	private $RequestParser;
	/** @var IController */
	private $FallbackController;
	/** @var IController[] */
	private $routes;

	public function __construct(IRequestParser $RequestParser, IController $FallbackController)
	{
		$this->RequestParser      = $RequestParser;
		$this->FallbackController = $FallbackController;
	}

	/**
	 * Route Request to Controller
	 * @return IController
	 */
	public function getControllerForRequest()
	{
		$route      = $this->getRouteFromRequest($this->RequestParser);
		$Controller = $this->getRequestHandler($route);

		return $Controller;
	}

	protected function getRouteFromRequest(IRequestParser $RequestParser)
	{
		return trim($RequestParser->getQueryArrayItem(0));
	}

	protected function getRequestHandler($route)
	{
		$Controller = $this->getControllerForRoute($route);
		if (empty($Controller))
		{
			$Controller = $this->getFallbackController();
		}
		return $Controller;
	}

	protected function getFallbackController()
	{
		return $this->FallbackController;
	}

	public function setFallbackController(IController $Controller)
	{
		$this->FallbackController = $Controller;
	}

	public function addRouteForController($route, IController $Controller)
	{
		$this->routes[$this->getFilteredRouteString($route)] = $Controller;
	}

	private function getFilteredRouteString($route)
	{
		$route = trim(trim($route, '/'));
		if (empty($route)) $route = '/';
		return $route;
	}

	public function getControllerForRoute($route)
	{
		if (empty($this->routes[$route]))
		{
			return false;
		}
		return $this->routes[$route];
	}

	public function getRouteOfController(IController $Controller)
	{
		if (empty($this->routes))
		{
			return '/';
		}
		foreach ($this->routes as $route => $AssignedController)
		{
			if ($Controller===$AssignedController)
			{
				return $route;
			}
		}
		trigger_error('Not assigned route for controller '.get_class($Controller), E_USER_WARNING);
		return '/?';
	}
}
