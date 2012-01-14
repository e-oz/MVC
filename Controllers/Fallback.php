<?php
namespace Jamm\MVC\Controllers;

class Fallback implements IController
{
	/**
	 * @param \Jamm\HTTP\IResponse $Response
	 */
	public function fillResponse(\Jamm\HTTP\IResponse $Response)
	{
		$Response->setStatusCode(404);
		$Response->setBody('<html><body>Oops. Can not find page.</body></html>');
	}
}
