<?php
namespace Jamm\MVC\Views;

interface IPageRenderer
{
	public function setTemplatesDir($templates_dir);

	public function renderPage($template_file_name, array $vars = array());

	public function setBaseURL($base_url);

	public function setFullUrl($full_url);
}
