<?php
namespace Jamm\MVC\Models;

interface ISessionStorage
{
	public function save(ISession $Session);

	public function deleteByID($id);

	public function getByID($id);

	public function getNewSession();
}