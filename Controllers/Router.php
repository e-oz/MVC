<?php
namespace Jamm\MVC\Controllers;

/**
 * Class to route query from Request to Response
 * Should be extended and method getQueryHandler should be implemented
 */
class Router implements IRouter
{
	/** @var \Jamm\MVC\Factories\IControllerNullObject[] */
	protected $routes;
	protected $QueryParser;
	/** @var IController */
	private $FallbackController;

	public function addRoute($url_prefix, \Jamm\MVC\Factories\IControllerNullObject $ControllerNullObject)
	{
		$this->routes[$url_prefix] = $ControllerNullObject;
	}

	public function __construct(IQueryParser $QueryParser, IController $FallbackController)
	{
		$this->QueryParser        = $QueryParser;
		$this->FallbackController = $FallbackController;
	}

	/**
	 * Route query to controller and fill Response object
	 * @param \Jamm\HTTP\IResponse $Response
	 * @param \Jamm\MVC\Controllers\IQueryParser $QueryParser
	 * @return void
	 */
	public function fillResponseForQuery(\Jamm\HTTP\IResponse $Response, IQueryParser $QueryParser)
	{
		$command    = $this->getCommandFromQuery($QueryParser);
		$Controller = $this->getQueryHandler($command);

		return $Controller->fillResponse($Response);
	}

	protected function getCommandFromQuery(IQueryParser $QueryParser)
	{
		$QueryParser->parseQueryString();
		$query_parts = $QueryParser->getQueryArray();
		$command     = '';
		if (!empty($query_parts))
		{
			$command = trim($query_parts[0]);
		}
		return $command;
	}

	/**
	 * Return IController object, associated with the current command (first part of query string)
	 * This method contain (or reads) the map of routing and executes this map
	 * @param string $command
	 * @return IController
	 */
	protected function getQueryHandler($command)
	{
		if (empty($command) || !isset($this->routes[$command]))
		{
			return $this->getFallbackController();
		}
		return $this->routes[$command]->getController();
	}

	protected function getFallbackController()
	{
		return $this->FallbackController;
	}

	public function setFallbackController(IController $Controller)
	{
		$this->FallbackController = $Controller;
	}
}
