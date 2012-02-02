<?php
namespace Jamm\MVC\Views;

abstract class TemplatesRenderer implements ITemplatesRenderer
{
	private $TwigLoader;
	private $base_url;
	private $full_url;

	public function render_HTML_template($template_file_name, array $vars = array())
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

	private function setURLsInVarsArray(array &$vars)
	{
		if (empty($vars['base_url']))
		{
			$vars['base_url'] = $this->getBaseURL();
		}
		if (empty($vars['full_url']))
		{
			$vars['full_url'] = $this->getFullUrl();
		}
	}

	abstract protected function getTemplatesDir();

	abstract protected function getTwigTemplatesDir();

	protected function getTwigEnvironment()
	{
		return new \Twig_Environment($this->getTwigLoader());
	}

	/** @return \Twig_LoaderInterface */
	protected function getTwigLoader()
	{
		if (empty($this->TwigLoader))
		{
			try
			{
				$this->TwigLoader = new \Twig_Loader_Filesystem($this->getTwigTemplatesDir());
			}
			catch (\Twig_Error_Loader $e)
			{
				return false;
			}
		}
		return $this->TwigLoader;
	}

	public function render_Twig_template($template_file_name, array $vars = array())
	{
		$twig = $this->getTwigEnvironment();
		try
		{
			$template = $twig->loadTemplate($template_file_name);
		}
		catch (\Twig_Error_Loader $e)
		{
			return false;
		}
		$this->setURLsInVarsArray($vars);
		return $template->render($vars);
	}

	/**
	 * @return string Without ending slash
	 */
	protected function getBaseURL()
	{
		return $this->base_url;
	}

	/**
	 * @param string $base_url
	 */
	public function setBaseURL($base_url)
	{
		$this->base_url = trim(rtrim($base_url, '/'));
	}

	/**
	 * @return string Without ending slash
	 */
	protected function getFullUrl()
	{
		return $this->full_url;
	}

	/**
	 * @param string $full_url
	 */
	public function setFullUrl($full_url)
	{
		$this->full_url = trim(rtrim($full_url, '/'));
	}
}
