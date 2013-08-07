<?php
namespace Jamm\MVC\Models;

class SessionStorage implements ISessionStorage
{
	/** @var IKeyValueStorage */
	private $Storage;
	/** @var int */
	private $expiration_time;

	public function __construct(IKeyValueStorage $Storage, $expiration_time = 31536000)
	{
		$this->Storage         = $Storage;
		$this->expiration_time = $expiration_time;
	}

	public function setStorage(IKeyValueStorage $Storage)
	{
		$this->Storage = $Storage;
	}

	public function save(ISession $Session)
	{
		if (!$Session->getId()) {
			return $this->insert($Session);
		}
		return $this->Storage->set($Session->getId(), $Session, $this->expiration_time);
	}

	protected function insert(ISession $Session)
	{
		$symbols = str_split('qw1er2ty3ui4op5as6df7gh8jk9lz0xcvbnm');
		shuffle($symbols);
		$length = count($symbols) - 1;
		for ($try = 0; $try < 1000; ++$try) {
			$key = '';
			for ($i = 0; $i < 32; ++$i) {
				$key .= $symbols[mt_rand(0, $length)];
			}
			$Session->setID($key);
			if ($this->Storage->add($key, $Session, $this->expiration_time)) {
				return true;
			}
		}
		trigger_error("Can't insert session after 1000 tries", E_USER_WARNING);
		return false;
	}

	public function deleteByID($id)
	{
		return $this->Storage->delete($id);
	}

	/**
	 * @param $id
	 * @return ISession
	 */
	public function getByID($id)
	{
		return $this->Storage->get($id);
	}

	/**
	 * @return ISession
	 */
	public function getNewSession()
	{
		return new Session();
	}
}
