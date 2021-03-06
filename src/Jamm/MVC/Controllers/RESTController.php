<?php
namespace Jamm\MVC\Controllers;

use Jamm\HTTP\IRequest;
use Jamm\HTTP\IResponse;
use Jamm\MVC\Factories\ControllersServiceLocator;
use Jamm\MVC\Factories\IControllersServiceLocator;

abstract class RESTController extends AutoInstantiableController implements IAutoInstantiable, IRequireServiceLocator
{
	/** @var ControllersServiceLocator */
	protected $ServiceLocator;
	/** @var IRequest */
	protected $Request;
	/** @var IResponse */
	protected $Response;
	/** @var IRequestParser */
	protected $Parser;

	public function initServiceLocator(IControllersServiceLocator $ServiceLocator)
	{
		$this->ServiceLocator = $ServiceLocator;
	}

	/**
	 * @param \Jamm\HTTP\IResponse $Response
	 */
	public function fillResponse(\Jamm\HTTP\IResponse $Response)
	{
		$this->Request  = $this->ServiceLocator->getRequest();
		$this->Parser   = $this->ServiceLocator->getRequestParser();
		$this->Response = $Response;
		$this->beforeFillingResponse();
		switch ($this->Request->getMethod()) {
			case 'GET':
				return $this->GET();
			case 'POST':
				if (!$this->isCSRFSafe()) {
					$this->Response->setStatusCode(403);
					return false;
				}
				return $this->POST();
			case 'PUT':
				if (!$this->isCSRFSafe()) {
					$this->Response->setStatusCode(403);
					return false;
				}
				return $this->PUT();
			case 'DELETE':
				if (!$this->isCSRFSafe()) {
					$this->Response->setStatusCode(403);
					return false;
				}
				return $this->DELETE();
			case 'PATCH':
				if (!$this->isCSRFSafe()) {
					$this->Response->setStatusCode(403);
					return false;
				}
				return $this->PATCH();
			case 'OPTIONS':
				return $this->OPTIONS();
			case 'HEAD':
				return $this->HEAD();
			case 'TRACE':
				return $this->TRACE();
			default:
				$Response->setStatusCode(405); // Method not allowed
		}
	}

	protected function isCSRFSafe()
	{
		return $this->ServiceLocator->getSessionAuthenticator()->isCSRFValid();
	}

	abstract protected function GET();

	abstract protected function POST();

	abstract protected function PUT();

	abstract protected function DELETE();

	protected function PATCH()
	{
		// they are not equal, it's just for simplicity
		$this->PUT();
		// override this method for correct PATCH support
	}

	protected function OPTIONS()
	{
		/**
		 * It's just "lorem ipsum" for OPTIONS method.
		 * Please, override this method and return documentation in response
		 * @link http://zacstewart.com/2012/04/14/http-options-method.html
		 * @link https://plus.google.com/113297466675790881291/posts/MjVwjudDNYo
		 */
		$this->Response->setHeader('Allow', 'OPTIONS, GET, HEAD, POST, PUT, PATCH, DELETE, TRACE');
	}

	protected function HEAD()
	{
		$this->Response->setSendBody(false);
		$this->GET();
	}

	protected function TRACE()
	{
		$this->Response->setStatusCode(200);
		$headers_string = '';
		foreach ($this->Request->getHeaders() as $header => $value) {
			if (substr($header, 0, 5) === 'HTTP_') {
				$header = substr($header, 5);
			}
			$header = str_replace('_', '-', $header);
			$headers_string .= ucwords($header).': '.$value."\r\n";
		}
		$this->Response->setBody($headers_string);
	}

	protected function beforeFillingResponse() { }
}
