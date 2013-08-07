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
	/** @var IController[]|callable[] */
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
		$parts = $RequestParser->getQueryArray();
		if (empty($parts)) {
			return '/';
		}
		foreach ($this->routes as $route => $fx) {
			$pattern = '';
			foreach ($parts as $part) {
				$pattern .= $part;
				if ($route === $pattern) {
					return $route;
				}
				$pattern .= '/';
			}
		}
		if (!isset($parts[0]) || empty($parts[0])) {
			$parts[0] = '/';
		}
		return $parts[0];
	}

	protected function getRequestHandler($route)
	{
		$Controller = $this->getControllerForRoute($route);
		if (empty($Controller)) {
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
		if (empty($route)) {
			$route = '/';
		}
		return $route;
	}

	public function getControllerForRoute($route)
	{
		if (empty($this->routes[$route])) {
			return false;
		}
		$controller = $this->routes[$route];
		if (is_callable($controller)) {
			$controller           = $controller();
			$this->routes[$route] = $controller;
		}
		return $controller;
	}

	public function addRouteCallbackFunction($route, $callback_function)
	{
		$this->routes[$this->getFilteredRouteString($route)] = $callback_function;
	}
}
