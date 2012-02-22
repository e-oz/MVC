<?php
namespace Jamm\MVC\Controllers;

class RequestParser implements IRequestParser
{
	private $query_array;
	private $query_string;
	private $script_name;
	private $Request;

	public function __construct(\Jamm\HTTP\IRequest $Request)
	{
		$this->Request = $Request;
	}

	private function getQueryString()
	{
		if (!isset($this->query_string))
		{
			$this->script_name = !empty($script_name) ? $script_name : pathinfo($this->Request->getHeaders('SCRIPT_NAME'), PATHINFO_BASENAME);
			if (!empty($query_string)) $this->query_string = $query_string;
			else
			{
				$path_info          = $this->Request->getHeaders('PATH_INFO');
				$this->query_string = !empty($path_info) ? $path_info : $this->Request->getHeaders('QUERY_STRING');
				if (empty($this->query_string))
				{
					$dirname = dirname($this->script_name);
					if (strpos($this->Request->getHeaders('REQUEST_URI'), $dirname)===0)
					{
						$this->query_string = substr($this->Request->getHeaders('REQUEST_URI'), strlen($dirname));
					}
				}
			}
		}
		return $this->query_string;
	}

	private function parseQueryString()
	{
		$Request = $this->Request;
		$QUERY   = addslashes($this->getQueryString());
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

		if (($ampersand_pos = strpos($QUERY, '&'))!==false)
		{
			if ($Request->getMethod()==$Request::method_GET)
			{
				$Request->setData(array());
				$get_array = explode('&', substr($QUERY, $ampersand_pos+1));
				foreach ($get_array as $get_element)
				{
					list($get_key, $get_value) = explode('=', $get_element);
					$this->Request->setDataKey($get_key, $get_value);
				}
			}
			$this->query_string = substr($QUERY, 0, $ampersand_pos);
			$QUERY              = $this->query_string;
		}
		if (strpos($QUERY, '/')!==false) $this->query_array = explode('/', $QUERY);

		else $this->query_array = array($QUERY);
		return $this->query_array;
	}

	/** @param string $query_string     */
	public function setQueryString($query_string)
	{
		$this->query_string = $query_string;
	}

	/** @return array */
	public function getQueryArray()
	{
		if (empty($this->query_array))
		{
			$this->parseQueryString();
		}
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
		$QueryArray = $this->getQueryArray();
		return isset($QueryArray[$index]) ? $QueryArray[$index] : NULL;
	}

	/**
	 * Set item of the query array
	 * @param int $index
	 * @param string $value
	 */
	public function setQueryArrayItem($index, $value)
	{
		$this->query_array[$index] = $value;
	}

	public function getRequestArguments()
	{
		$data    = $this->Request->getData();
		$Request = $this->Request;
		if (!empty($data) && $Request->getMethod()===$Request::method_GET)
		{
			if (is_array($data))
			{
				$values = array_slice(array_values($data), 1);
				if (!empty($values))
				{
					$values_without_spaces = implode('', $values);
					if (!empty($values_without_spaces))
					{
						return $values;
					}
				}
			}
			$values = $this->getArgumentsFromQueryString();
			if (!empty($values)) return $values;
		}
		return $data;
	}

	private function getArgumentsFromQueryString()
	{
		$query_string = $this->getQueryString();
		if (strpos($query_string, '/')!==false)
		{
			$parts = explode('/', $query_string);
			return array_slice($parts, 1);
		}
		return NULL;
	}

	public function getAcceptedSerializer()
	{
		$serialization_method = $this->Request->getAccept();
		switch ($serialization_method)
		{
			case 'JSON':
				return new \Jamm\HTTP\SerializerJSON();
				break;
			case 'XML':
				return new \Jamm\HTTP\SerializerXML();
				break;
			case 'PHP':
				return new \Jamm\HTTP\SerializerPHP();
				break;
			default:
				return NULL;
		}
	}
}
