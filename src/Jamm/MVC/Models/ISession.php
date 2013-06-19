<?php
namespace Jamm\MVC\Models;

interface ISession
{
	public function setId($id);

	public function getId();

	public function setUserId($user_id);

	public function getUserId();
}
