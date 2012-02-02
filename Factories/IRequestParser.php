<?php
namespace Jamm\MVC\Factories;

interface IRequestParser
{
	/**
	 * @param \Jamm\HTTP\IRequest $Request
	 * @return \Jamm\MVC\Controllers\IRequestParser
	 */
	public function getRequestParser(\Jamm\HTTP\IRequest $Request);
}
