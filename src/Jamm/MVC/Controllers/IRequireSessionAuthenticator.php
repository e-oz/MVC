<?php
namespace Jamm\MVC\Controllers;

use Jamm\MVC\Models\SessionAuthenticator;

interface IRequireSessionAuthenticator
{
	public function initSessionAuthenticator(SessionAuthenticator $SessionAuthenticator);
}
