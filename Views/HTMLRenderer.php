<?php
namespace Jamm\MVC\Views;
class HTMLRenderer extends PageRenderer
{
	public function renderPage($template_file_name, array $vars = array())
	{
		$filepath = $this->getTemplatesDir().'/'.$template_file_name;
		$this->setURLsInVarsArray($vars);
		ob_start();
		/** @noinspection PhpIncludeInspection */
		include $filepath;
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
}
