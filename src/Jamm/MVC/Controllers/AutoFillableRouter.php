<?php
namespace Jamm\MVC\Controllers;
use Jamm\MVC\Factories\IServiceContainer;

class AutoFillableRouter extends Router
{
	/**
	 * @param RoutesList $RoutesList
	 * @param \Jamm\MVC\Factories\IServiceContainer $ServiceContainer
	 * @return bool
	 */
	public function fillRoutesFromList(RoutesList $RoutesList, IServiceContainer $ServiceContainer)
	{
		$routes = $RoutesList->getRoutes();
		if (empty($routes))
		{
			return false;
		}
		foreach ($routes as $route => $controller_name)
		{
			$this->addRouteCallbackFunction($route, function () use ($controller_name, $ServiceContainer)
			{
				if (!class_exists($controller_name))
				{
					trigger_error('AutoInstantiable controller was not found by name '.$controller_name, E_USER_WARNING);
					return $this->getFallbackController();
				}
				/** @var IAutoInstantiable $Controller */
				$Controller = new $controller_name();
				$Controller->setServiceContainer($ServiceContainer);
				return $Controller;
			});
		}
	}
}
