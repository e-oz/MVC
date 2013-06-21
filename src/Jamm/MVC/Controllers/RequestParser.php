<?php
namespace Jamm\MVC\Controllers;
class RequestParser implements IRequestParser
{
	private $query_array;
	private $request_uri;
	private $script_name;
	private $Request;

	public function __construct(\Jamm\HTTP\IRequest $Request)
	{
		$this->Request = $Request;
	}

	protected function getRequestURI()
	{
		if (!isset($this->request_uri))
		{
			$this->script_name = pathinfo($this->Request->getHeaders('SCRIPT_NAME'), PATHINFO_BASENAME);
			$path_info         = $this->Request->getHeaders('PATH_INFO');
			if ($this->Request->getHeaders('REQUEST_URI'))
			{
				$this->request_uri = $this->Request->getHeaders('REQUEST_URI');
			}
			else
			{
				$this->request_uri = !empty($path_info) ? $path_info : $this->Request->getHeaders('QUERY_STRING');
			}
			if (empty($this->request_uri))
			{
				$dirname = dirname($this->script_name);
				if (strpos($this->Request->getHeaders('REQUEST_URI'), $dirname)===0)
				{
					$this->request_uri = substr($this->Request->getHeaders('REQUEST_URI'), strlen($dirname));
				}
				elseif (empty($dirname) || $dirname==='.')
				{
					$this->request_uri = $this->Request->getHeaders('REQUEST_URI');
				}
			}
		}
		return $this->request_uri;
	}

	private function parseRequestURI()
	{
		$Request     = $this->Request;
		$request_uri = addslashes($this->getRequestURI());
		if (empty($request_uri)) return false;
		while (strpos($request_uri, '..')!==false)
		{
			$request_uri = str_replace('..', '', $request_uri);
		}
		if (empty($request_uri)) return false;
		if ($request_uri[0]==='/')
		{
			$request_uri = substr($request_uri, 1);
		}
		if (strpos($request_uri, $this->script_name)===0) $request_uri = substr($request_uri, strlen($this->script_name));
		if ($request_uri[0]==='&') $request_uri = substr($request_uri, 1);
		if ((($ampersand_pos = strpos($request_uri, '&'))!==false) || (($ampersand_pos = strpos($request_uri, '?'))!==false))
		{
			if ($Request->getMethod()==$Request::method_GET)
			{
				$get_array = array();
				parse_str(substr($request_uri, $ampersand_pos+1), $get_array);
				$Request->setData($get_array);
			}
			$this->request_uri = substr($request_uri, 0, $ampersand_pos);
			$request_uri       = $this->request_uri;
		}
		if (strpos($request_uri, '/')!==false)
		{
			$this->query_array = explode('/', $request_uri);
		}
		else $this->query_array = array($request_uri);
		return $this->query_array;
	}

	/** @param string $query_string */
	protected function setRequestURI($query_string)
	{
		$this->request_uri = $query_string;
	}

	/** @return array */
	public function getQueryArray()
	{
		if (empty($this->query_array))
		{
			$this->parseRequestURI();
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

	public function getRequestArguments($skip_path_parts_count = 1)
	{
		$data    = $this->Request->getData();
		$Request = $this->Request;
		if (!empty($data) && $Request->getMethod()===$Request::method_GET)
		{
			if (is_array($data))
			{
				$values = array_values($data);
				if (!empty($values))
				{
					$values_without_spaces = implode('', $values);
					if (!empty($values_without_spaces))
					{
						return $data;
					}
				}
			}
			$values = $this->getArgumentsFromQueryString($skip_path_parts_count);
			if (!empty($values)) return $values;
		}
		return $data;
	}

	private function getArgumentsFromQueryString($skip_path_parts_count = 1)
	{
		$parts = $this->getQueryArray();
		if (!empty($parts))
		{
			return array_slice($parts, $skip_path_parts_count);
		}
		return NULL;
	}

	public function getAcceptedSerializer()
	{
		$serialization_method = $this->Request->getAccept();
		if (stripos($serialization_method, 'JSON')!==false)
		{
			$Serializer = new \Jamm\HTTP\SerializerJSON();
			$callback   = $this->Request->getData('callback');
			if (!empty($callback))
			{
				$Serializer->setJSONPCallbackName($callback);
			}
			return $Serializer;
		}
		elseif ($serialization_method==='XML')
		{
			return new \Jamm\HTTP\SerializerXML();
		}
		return NULL;
	}

	public function getRequestObject()
	{
		return $this->Request;
	}
}
