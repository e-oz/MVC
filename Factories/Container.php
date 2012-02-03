<?php
namespace Jamm\MVC\Factories;

/**
 * Example of container, which can be useful as universal class for different
 * requirements in objects constructors.
 * For example, if we have 3 classes, where
 * class 1 requires Request factory,
 * class 2 requires MemoryStorage factory,
 * class 3 requires PDO factory.
 * So if Container will implement 3 these interfaces, we can pass $Container object in
 * all these classes, and each class will got what were required,
 * and it will be easy to reconfigure.
 */
abstract class Container implements IRequestParser, IPDOConnection
{
	/** @var \Jamm\MVC\Controllers\IRequestParser */
	private $RequestParser;

	/**
	 * @param \Jamm\HTTP\IRequest $Request
	 * @return \Jamm\MVC\Controllers\IRequestParser
	 */
	public function getRequestParser(\Jamm\HTTP\IRequest $Request)
	{
		if (empty($this->RequestParser))
		{
			$this->RequestParser = new \Jamm\MVC\Controllers\RequestParser($Request);
		}
		return $this->RequestParser;
	}

	public function getNewDefaultResponseObject()
	{
		$response = new \Jamm\HTTP\Response();
		$response->setHeader('Content-type', 'text/html; charset=UTF-8');
		return $response;
	}
}
