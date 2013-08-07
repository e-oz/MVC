<?php
namespace Jamm\MVC\Factories;

use Jamm\HTTP\IRequest;
use Jamm\HTTP\SerializerJSON;
use Jamm\MVC\Controllers\IRequestParser;
use Jamm\MVC\Controllers\RequestParser;

/**
 * ServiceLocator is ONLY for Controllers!
 * For Models and other reusable code use Dependency Injection
 * @link http://martinfowler.com/articles/injection.html
 *
 * It's just example of implementation, but you can extend and use it if you find existing methods useful
 */
class ControllersServiceLocator implements IControllersServiceLocator
{
	private $Request;
	private $RequestParser;
	/** @var ServiceFactory */
	private $ServiceFactory;
	private $Response;
	private $SessionStorage;

	public function __construct(ServiceFactory $ServiceFactory)
	{
		$this->ServiceFactory = $ServiceFactory;
	}

	/**
	 * @return IRequest
	 */
	public function getRequest()
	{
		if (empty($this->Request)) {
			$this->Request = $this->ServiceFactory->getRequest();
		}
		return $this->Request;
	}

	public function getResponse()
	{
		if (empty($this->Response)) {

			$this->Response = $this->getServiceFactory()->getResponse();
			$RequestParser  = $this->getRequestParser();
			$Serializer     = $RequestParser->getAcceptedSerializer();
			if ($Serializer instanceof SerializerJSON) {
				/**
				 * Prefix for AngularJS, as JSON Vulnerability Protection
				 * @link http://docs.angularjs.org/api/ng.$http
				 *       If controller allows JSONP-requests, use setJSONPCallbackName() method
				 *       in that controller, and prefix will not be added.
				 */
				/** @var SerializerJSON $Serializer */
				$Serializer->setJSONPrefix(")]}',\n");
			}
			$this->Response->setSerializer($Serializer);
			$this->getSessionAuthenticator()->setCSRFTokenForSession($this->Response);
		}
		return $this->Response;
	}

	/**
	 * @param IRequest $Request
	 */
	public function setRequest(IRequest $Request)
	{
		$this->Request = $Request;
	}

	/**
	 * @return IRequestParser
	 */
	public function getRequestParser()
	{
		if (empty($this->RequestParser)) {
			$this->RequestParser = new RequestParser($this->getRequest());
		}
		return $this->RequestParser;
	}

	/**
	 * @param IRequestParser $RequestParser
	 */
	public function setRequestParser(IRequestParser $RequestParser)
	{
		$this->RequestParser = $RequestParser;
	}

	/**
	 * @return IServiceFactory
	 */
	public function getServiceFactory()
	{
		return $this->ServiceFactory;
	}

	/**
	 * @param IServiceFactory $ServiceFactory
	 */
	public function setServiceFactory(IServiceFactory $ServiceFactory)
	{
		$this->ServiceFactory = $ServiceFactory;
	}

	public function getSessionAuthenticator()
	{
		return $this->ServiceFactory->getSessionAuthenticator($this->getSessionStorage(), $this->getRequest());
	}

	public function getSessionStorage()
	{
		if (empty($this->SessionStorage)) {
			$this->SessionStorage = $this->ServiceFactory->getSessionStorage();
		}
		return $this->SessionStorage;
	}
}
