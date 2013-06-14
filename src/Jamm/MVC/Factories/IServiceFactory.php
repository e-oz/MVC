<?php
namespace Jamm\MVC\Factories;

interface IServiceFactory
{
	/**
	 * @return \Jamm\HTTP\IRequest;
	 */
	public function getRequest();

	/**
	 * @param \Jamm\MVC\Controllers\IRequestParser $RequestParser
	 * @return \Jamm\HTTP\IResponse
	 */
	public function getResponse(\Jamm\MVC\Controllers\IRequestParser $RequestParser);
}
