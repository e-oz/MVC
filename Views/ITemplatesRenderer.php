<?php
namespace Jamm\MVC\Views;

interface ITemplatesRenderer
{
	public function render_HTML_template($template_file_name, array $vars = array());

	public function render_Twig_template($template_file_name, array $vars = array());

	public function setBaseURL($base_url);

	public function setFullUrl($full_url);
}
