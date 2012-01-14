<?php
namespace Jamm\MVC\Views;

/**
 * If will be used as base class for Views, declare correct Templates directory
 * by default is __DIR__.'/Templates'
 */
abstract class View
{
	protected $templates_dir;
	protected $TwigLoader;

	public function __construct()
	{
		$this->setTemplatesDir();
	}

	public function render_HTML_template($template_file_name, array $vars)
	{
		$filepath = $this->getTemplatesDir().'/'.$template_file_name;
		ob_start();
		/** @noinspection PhpIncludeInspection */
		include $filepath;
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	public function getTemplatesDir()
	{
		return $this->templates_dir;
	}

	public function getTwigTemplatesDir()
	{
		return $this->templates_dir;
	}

	/**
	 * Set templates_dir property
	 * Example:
	 * $this->templates_dir = __DIR__.'/Templates';
	 * @return void
	 */
	abstract public function setTemplatesDir();

	public function getTwigEnvironment()
	{
		return new \Twig_Environment($this->getTwigLoader());
	}

	/** @return \Twig_LoaderInterface */
	public function getTwigLoader()
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
		return $template->render($vars);
	}
}
