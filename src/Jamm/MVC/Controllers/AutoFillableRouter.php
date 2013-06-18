<?php
namespace Jamm\MVC\Controllers;

use Jamm\MVC\Factories\IControllerBuilder;

class AutoFillableRouter extends Router
{
	/**
	 * @param RoutesList $RoutesList
	 * @param string $prefix_namespace
	 * @param \Jamm\MVC\Factories\IControllerBuilder $Builder
	 * @return bool
	 */
	public function fillRoutesFromList(RoutesList $RoutesList, $prefix_namespace = '', IControllerBuilder $Builder = null)
	{
		$routes = $RoutesList->getRoutes();
		if (empty($routes))
		{
			return false;
		}
		foreach ($routes as $route => $controller_name)
		{
			if (!empty($prefix_namespace) && substr($controller_name, 0, 1)!=='\\')
			{
				$controller_name = '\\'.trim($prefix_namespace, '\\').'\\'.$controller_name;
			}
			$this->addRouteCallbackFunction($route, function () use ($controller_name, $Builder)
			{
				if (!class_exists($controller_name))
				{
					trigger_error('AutoInstantiable controller was not found by name '.$controller_name, E_USER_WARNING);
					return $this->getFallbackController();
				}
				$Controller = new $controller_name();
				if (!empty($Builder))
				{
					$Builder->buildController($Controller);
				}
				return $Controller;
			});
		}
	}
}
