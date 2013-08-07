<?php
namespace Jamm\MVC\Views;

/**
 * Render HTML template, containing PHP code parts (PHTML)
 * @package Jamm\MVC\Views
 */
class PHTMLRenderer extends PageRenderer
{
	public function renderPage($template_file_name, array $vars = array())
	{
		$file_path = $this->getTemplatesDir().'/'.$template_file_name;
		if (!file_exists($file_path)) {
			trigger_error('File of template does not exist', E_USER_WARNING);
			return false;
		}
		$this->setURLsInVarsArray($vars);
		ob_start();
		/** @noinspection PhpIncludeInspection */
		include $file_path;
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
}
