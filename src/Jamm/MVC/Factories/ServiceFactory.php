<?php
namespace Jamm\MVC\Factories;

use Jamm\HTTP\Request;
use Jamm\HTTP\Response;
use Jamm\MVC\Controllers\IRequestParser;

class ServiceFactory implements IServiceFactory
{
	public function getRequest()
	{
		$Request = new Request();
		$Request->BuildFromInput();
		return $Request;
	}

	public function getResponse(IRequestParser $RequestParser)
	{
		$Response = new Response();
		$Response->setSerializer($RequestParser->getAcceptedSerializer());
		return $Response;
	}
}
