<?php
namespace Jamm\MVC\Factories;

use Jamm\HTTP\IRequest;
use Jamm\HTTP\Request;
use Jamm\HTTP\Response;
use Jamm\MVC\Models\RedisStorage;
use Jamm\MVC\Models\SessionAuthenticator;
use Jamm\MVC\Models\SessionStorage;

/**
 * Should return only new instances. Not cached, not cloned.
 */
class ServiceFactory implements IServiceFactory
{
	public function getRequest()
	{
		$Request = new Request();
		$Request->BuildFromInput();
		return $Request;
	}

	public function getResponse()
	{
		$Response = new Response();
		return $Response;
	}

	public function getSessionAuthenticator(IRequest $Request)
	{
		return new SessionAuthenticator($this->getSessionStorage(), $Request);
	}

	public function getSessionStorage()
	{
		return new SessionStorage(new RedisStorage());
	}
}
