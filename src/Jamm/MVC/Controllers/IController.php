<?php
namespace Jamm\MVC\Controllers;
interface IController
{
	/**
	 * @param \Jamm\HTTP\IResponse $Response
	 */
	public function fillResponse(\Jamm\HTTP\IResponse $Response);
}
