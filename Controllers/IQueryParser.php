<?php
namespace Jamm\MVC\Controllers;

interface IQueryParser
{
	/**
	 * Parse query string from mod_rewrite data
	 * EXAMPLE of .htaccess (it can be config in vhosts or in nginx locations):
	 * <IfModule mod_rewrite.c>
	 *	 RewriteEngine On
	 *	 RewriteRule ^(.*)$ index.php [QSA,L]
	 * </IfModule>
	 */
	public function parseQueryString();

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
	 * @param string|numeric $value
	 */
	public function setQueryArrayItem($index, $value);
}
