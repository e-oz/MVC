<?php
namespace Jamm\MVC\Factories;
interface IPDOConnection
{
	/**
	 * @return \PDO
	 */
	public function getPDOConnection();
}
