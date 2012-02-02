<?php
namespace Jamm\MVC\Controllers;

interface IRequestParser
{
	public function getQueryString();

	/** @param string $query_string	 */
	public function setQueryString($query_string);

	/** @return array */
	public function getQueryArray();

	public function setQueryArray(array $query_array);

	/**
	 * Get item of the query array
	 * @param int $index
	 * @return string|null
	 */
	public function getQueryArrayItem($index);

	/**
	 * Set item of the query array
	 * @param int $index
	 * @param string $value
	 */
	public function setQueryArrayItem($index, $value);

	/** @return array|null */
	public function getRequestArguments();	
}
