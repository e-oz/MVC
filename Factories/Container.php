<?php
namespace Jamm\MVC\Factories;

/**
 * Example of container, which can be useful as universal class for different
 * requirements in objects constructors.
 * For example, if we have 3 classes, where
 * class 1 requires QueryParser factory,
 * class 2 requires MemoryStorage factory,
 * class 3 requires PDO factory.
 * So if Container will implement 3 these interfaces, we can pass $Container object in
 * all these classes, and each class will got what were required,
 * and it will be easy to reconfigure.
 */
abstract class Container implements IQueryParser, IPDOConnection
{
	/** @var \Jamm\MVC\Controllers\IQueryParser */
	private $QueryParser;

	/**
	 * @return \Jamm\MVC\Controllers\IQueryParser
	 */
	public function getQueryParser()
	{
		if (empty($this->QueryParser))
		{
			$this->QueryParser = new \Jamm\MVC\Controllers\QueryParser();
		}
		return $this->QueryParser;
	}

	public function getNewDefaultResponseObject()
	{
		$response = new \Jamm\HTTP\Response();
		$response->setHeader('Content-type', 'text/html; charset=UTF-8');
		return $response;
	}
}
