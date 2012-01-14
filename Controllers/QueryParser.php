<?php
namespace Jamm\MVC\Controllers;

class QueryParser implements IQueryParser
{
	protected $query_array = array();
	protected $query_string;
	protected $script_name;

	public function __construct($query_string = '', $script_name = '')
	{
		$this->script_name = !empty($script_name) ? $script_name : pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME);
		if (!empty($query_string)) $this->query_string = $query_string;
		else
		{
			$this->query_string = isset($_SERVER['PATH_INFO'])
					? $_SERVER['PATH_INFO'] : $_SERVER['QUERY_STRING'];
			if (empty($this->query_string))
			{
				$dirname = dirname($this->script_name);
				if (strpos($_SERVER['REQUEST_URI'], $dirname)===0)
				{
					$this->query_string = substr($_SERVER['REQUEST_URI'], strlen($dirname));
				}
			}
		}
	}

	public function parseQueryString()
	{
		$QUERY = addslashes($this->query_string);
		if (empty($QUERY)) return false;
		while (strpos($QUERY, '..')!==false)
		{
			$QUERY = str_replace('..', '', $QUERY);
		}
		if (empty($QUERY)) return false;

		if ($QUERY[0]==='/')
		{
			$QUERY = substr($QUERY, 1);
		}
		if (strpos($QUERY, $this->script_name)===0) $QUERY = substr($QUERY, strlen($this->script_name));
		if ($QUERY[0]==='&') $QUERY = substr($QUERY, 1);
		if (strpos($QUERY, '/')!==false) $this->query_array = explode('/', $QUERY);
		elseif (strpos($QUERY, '&')!==false) $this->query_array = explode('&', $QUERY);
		else $this->query_array = array($QUERY);
		return $this->query_array;
	}

	public function getQueryString()
	{
		return $this->query_string;
	}

	/** @param string $query_string	 */
	public function setQueryString($query_string)
	{
		$this->query_string = $query_string;
	}

	/** @return array */
	public function getQueryArray()
	{
		if (!isset($this->query_array)) $this->parseQueryString();
		return $this->query_array;
	}

	public function setQueryArray(array $query_array)
	{
		$this->query_array = $query_array;
	}

	/**
	 * Get item of the query array
	 * @param int $index
	 * @return string|null
	 */
	public function getQueryArrayItem($index)
	{
		return isset($this->query_array[$index]) ? $this->query_array[$index] : NULL;
	}

	/**
	 * Set item of the query array
	 * @param int $index
	 * @param string|numeric $value
	 */
	public function setQueryArrayItem($index, $value)
	{
		$this->query_array[$index] = $value;
	}
}
