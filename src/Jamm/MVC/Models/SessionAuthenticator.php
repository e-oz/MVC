<?php
namespace Jamm\MVC\Models;

use Jamm\HTTP\Cookie;
use Jamm\HTTP\IRequest;
use Jamm\HTTP\IResponse;

class SessionAuthenticator
{
	private $cookie_name = 'session';
	/** @var ISessionStorage */
	private $SessionStorage;
	/** @var ISession */
	private $Session;
	/** @var \Jamm\HTTP\IRequest */
	private $Request;

	public function __construct(ISessionStorage $SessionStorage, IRequest $Request)
	{
		$this->SessionStorage = $SessionStorage;
		$this->Request        = $Request;
	}

	public function isAuthenticatedSession()
	{
		if (!$Session = $this->getSession())
		{
			return false;
		}
		if ($Session->getUserId() > 0)
		{
			return true;
		}
		return false;
	}

	public function getSession()
	{
		if (empty($this->Session))
		{
			if (!$SessionCookie = $this->Request->getCookie($this->cookie_name))
			{
				return false;
			}
			if (!$SessionCookie->getValue())
			{
				return false;
			}
			if (!($this->Session = $this->SessionStorage->getByID($SessionCookie->getValue())))
			{
				return false;
			}
		}
		return $this->Session;
	}

	public function logOut(IResponse $Response)
	{
		$this->getSession();
		if (!empty($this->Session))
		{
			$this->SessionStorage->deleteByID($this->Session->getId());
		}
		$Response->setCookie(new Cookie($this->cookie_name, ''));
	}

	public function setUserID(IResponse $Response, $user_id, $remember = true)
	{
		if (!$Session = $this->getSession())
		{
			$Session = $this->SessionStorage->getNewSession();
		}
		$Session->setUserId($user_id);
		if (!$this->SessionStorage->save($Session))
		{
			return false;
		}
		if ($remember)
		{
			$expire = time()+94608000;
		}
		else
		{
			$expire = 0;
		}
		$Cookie = new Cookie($this->cookie_name, $Session->getId(), $expire);
		$Response->setCookie($Cookie);
		return true;
	}

	public function getUserID()
	{
		if (!$Session = $this->getSession())
		{
			return false;
		}
		return $Session->getUserId();
	}

	/**
	 * @return string
	 */
	public function getCookieName()
	{
		return $this->cookie_name;
	}

	/**
	 * @param string $cookie_name
	 */
	public function setCookieName($cookie_name)
	{
		$this->cookie_name = $cookie_name;
	}
}
