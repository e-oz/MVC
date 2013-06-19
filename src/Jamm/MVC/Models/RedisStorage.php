<?php
namespace Jamm\MVC\Models;

use Jamm\Memory\RedisObject;

class RedisStorage extends RedisObject implements IKeyValueStorage
{
	public function get($key)
	{
		return $this->read($key);
	}

	public function set($key, $value, $ttl_seconds = 0)
	{
		return $this->save($key, $value, $ttl_seconds);
	}

	public function delete($key)
	{
		return $this->del($key);
	}
}
