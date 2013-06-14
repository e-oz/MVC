<?php
namespace Jamm\MVC\Controllers;
use Jamm\MVC\Factories\IServiceContainer;

class AutoFillableRouter extends Router
{
	/**
	 * @param RoutesList $RoutesList
	 * @param \Jamm\MVC\Factories\IServiceContainer $ServiceContainer
	 * @param string $prefix_namespace
	 * @return bool
	 */
	public function fillRoutesFromList(RoutesList $RoutesList, IServiceContainer $ServiceContainer = null, $prefix_namespace = '')
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
			$this->addRouteCallbackFunction($route, function () use ($controller_name, $ServiceContainer)
			{
				if (!class_exists($controller_name))
				{
					trigger_error('AutoInstantiable controller was not found by name '.$controller_name, E_USER_WARNING);
					return $this->getFallbackController();
				}
				/** @var IAutoInstantiable $Controller */
				$Controller = new $controller_name();
				if (!empty($ServiceContainer))
				{
					$Controller->setServiceContainer($ServiceContainer);
				}
				return $Controller;
			});
		}
	}
}
