<?php
namespace Jamm\MVC\Controllers;

interface IRouter
{
	/**
	 * Route query to controller and fill Response object
	 * @param \Jamm\HTTP\IResponse $Response
	 * @param \Jamm\MVC\Controllers\IQueryParser $QueryParser
	 * @return void
	 */
	public function fillResponseForQuery(\Jamm\HTTP\IResponse $Response, IQueryParser $QueryParser);

	public function addRoute($url_prefix, \Jamm\MVC\Factories\IControllerNullObject $ControllerNullObject);

	public function setFallbackController(IController $Controller);
}
