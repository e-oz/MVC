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
	/** @var string */
	private $received_csrf_token;
	private $csrf_header = 'HTTP_X_XSRF_TOKEN';
	private $csrf_cookie = 'XSRF-TOKEN';
	private $verify_token = true;

	public function __construct(ISessionStorage $SessionStorage, IRequest $Request)
	{
		$this->SessionStorage = $SessionStorage;
		$this->Request        = $Request;
	}

	public function isAuthenticatedSession()
	{
		return ($this->getSession()->getUserId() > 0);
	}

	public function getSession()
	{
		if (empty($this->Session)) {
			$this->Session = $this->SessionStorage->getNewSession();
			if (!$SessionCookie = $this->Request->getCookie($this->cookie_name)) {
				return $this->Session;
			}
			if (!$SessionCookie->getValue()) {
				return $this->Session;
			}
			if (!($Session = $this->SessionStorage->getByID($SessionCookie->getValue()))) {
				return $this->Session;
			}
			else {
				$this->Session = $Session;
			}
		}
		return $this->Session;
	}

	public function logOut(IResponse $Response)
	{
		$Session = $this->getSession();
		if ($Session->getId()) {
			$this->SessionStorage->deleteByID($Session->getId());
		}
		$Response->setCookie(new Cookie($this->cookie_name, ''));
	}

	public function setUserID(IResponse $Response, $user_id, $remember = true)
	{
		$Session = $this->getSession();
		$Session->setUserId($user_id);
		if (!$this->SessionStorage->save($Session)) {
			return false;
		}
		if ($remember) {
			$expire = time() + 94608000;
		}
		else {
			$expire = 0;
		}
		$Cookie = new Cookie($this->cookie_name, $Session->getId(), $expire);
		$Response->setCookie($Cookie);
		return true;
	}

	public function getUserID()
	{
		return $this->getSession()->getUserId();
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

	public function getReceivedCSRFToken()
	{
		if (empty($this->received_csrf_token)) {
			$this->received_csrf_token = $this->Request->getHeaders($this->csrf_header);
		}
		return $this->received_csrf_token;
	}

	public function isCSRFValid()
	{
		if (!$this->isAuthenticatedSession()) {
			return true;
		}
		$token = $this->getReceivedCSRFToken();
		if (empty($token)) {
			return false;
		}
		$cookie_token = $this->getCookieCSRFToken();
		if (empty($token)) {
			return false;
		}
		if ($cookie_token === $token) {
			if ($this->verify_token) {
				return $this->isTokenValid($token, $this->getSession()->getId());
			}
			return true;
		}
		return false;
	}

	public function getCookieCSRFToken()
	{
		$CsrfCookie = $this->Request->getCookie($this->csrf_cookie);
		if (empty($CsrfCookie)) {
			return false;
		}
		return $CsrfCookie->getValue();
	}

	public function setCSRFTokenForSession(IResponse $Response)
	{
		if (!$this->isAuthenticatedSession()) {
			return false;
		}
		$csrf_token = $this->getNewCSRFTokenForSession($this->getSession());
		if (empty($csrf_token)) {
			return false;
		}
		$Cookie = new Cookie($this->csrf_cookie, $csrf_token, 0, '/', '', false, false);
		$Response->setCookie($Cookie);
		return true;
	}

	public function getNewCSRFTokenForSession(ISession $Session)
	{
		if (!$Session->getId()) {
			trigger_error('To generate safe CSRF token ID of session is required', E_USER_WARNING);
			return false;
		}
		return crypt($Session->getId());
	}

	protected function isTokenValid($token, $session_id)
	{
		if (empty($session_id)) {
			trigger_error('To verify CSRF token ID of session is required', E_USER_WARNING);
			return false;
		}
		if (crypt($session_id, $token) === $token) {
			return true;
		}
		return false;
	}

	/**
	 * @return string
	 */
	public function getCsrfHeader()
	{
		return $this->csrf_header;
	}

	/**
	 * @param string $csrf_header
	 */
	public function setCsrfHeader($csrf_header)
	{
		$this->csrf_header = $csrf_header;
	}

	/**
	 * @return string
	 */
	public function getCsrfCookie()
	{
		return $this->csrf_cookie;
	}

	/**
	 * @param string $csrf_cookie
	 */
	public function setCsrfCookie($csrf_cookie)
	{
		$this->csrf_cookie = $csrf_cookie;
	}

	/**
	 * @return boolean
	 */
	public function getVerifyToken()
	{
		return $this->verify_token;
	}

	/**
	 * @param boolean $validate_token
	 */
	public function setVerifyToken($validate_token = true)
	{
		$this->verify_token = $validate_token;
	}
}
