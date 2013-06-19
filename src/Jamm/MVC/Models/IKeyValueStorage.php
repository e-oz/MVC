<?php
namespace Jamm\MVC\Models;

interface IKeyValueStorage
{
	public function get($key);

	public function set($key, $value, $ttl_seconds = 0);

	public function add($key, $value, $ttl_seconds = 0);

	public function delete($key);
}