<?php
namespace Jamm\MVC\Controllers;

interface IRequestParser
{
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
	 * @param int    $index
	 * @param string $value
	 */
	public function setQueryArrayItem($index, $value);

	/**
	 * @param int $skip_path_parts_count
	 * @return array|null
	 */
	public function getRequestArguments($skip_path_parts_count = 1);

	/**
	 * @return \Jamm\HTTP\ISerializer|NULL
	 */
	public function getAcceptedSerializer();

	/** @return \Jamm\HTTP\IRequest */
	public function getRequestObject();
}
