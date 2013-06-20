<?php
namespace Jamm\MVC\Factories;

/**
 * Should return only new instances. Not cached, not cloned.
 */
interface IServiceFactory
{
	/**
	 * @return \Jamm\HTTP\IRequest;
	 */
	public function getRequest();

	/**
	 * @return \Jamm\HTTP\IResponse
	 */
	public function getResponse();
}
