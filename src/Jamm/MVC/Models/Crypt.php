<?php
namespace Jamm\MVC\Models;

class Crypt
{
	private $encryption_string;

	public function getHashFromPassword($password)
	{
		$this->requirePasswordLib();
		return password_hash($password, PASSWORD_BCRYPT);
	}

	protected function requirePasswordLib()
	{
		if (!defined('PASSWORD_DEFAULT'))
		{
			require __DIR__.'/password_compat.php';
		}
	}

	public function verifyPasswordHash($password, $hash)
	{
		$this->requirePasswordLib();
		return password_verify($password, $hash);
	}

	/**
	 * @param $string
	 * @param $password
	 * @return string
	 */
	public function getEncryptedString($string, $password = '')
	{
		$encryption_key = $this->getEncKey($password);
		$iv_size        = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CFB);
		$iv             = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$enc_string     = mcrypt_encrypt(\MCRYPT_RIJNDAEL_256, $encryption_key, $string, \MCRYPT_MODE_CFB, $iv);
		$enc_string     = bin2hex($iv.$enc_string);
		return $enc_string;
	}

	/**
	 * @param $input
	 * @param $password
	 * @return string
	 */
	public function getDecryptedString($input, $password = '')
	{
		$encryption_key = $this->getEncKey($password);
		$input          = hex2bin($input);
		$iv_size        = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CFB);
		$iv             = substr($input, 0, $iv_size);
		$input          = substr($input, $iv_size);
		$string         = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $encryption_key, $input, MCRYPT_MODE_CFB, $iv),"\0\4");
		return $string;
	}

	protected function getEncKey($password)
	{
		if (empty($password))
		{
			if (!empty($this->encryption_string))
			{
				$password = $this->encryption_string;
			}
			else
			{
				$password = md5(86400);
			}
		}
		$encryption_key = hash("SHA256", $password, true);
		return $encryption_key;
	}

	public function setEncryptionString($encryption_string)
	{
		$this->encryption_string = $encryption_string;
	}
}