<?php
namespace Jamm\MVC\Views;
class TwigRenderer extends PageRenderer
{
	private $TwigLoader;

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
				$this->TwigLoader = new \Twig_Loader_Filesystem($this->getTemplatesDir());
			}
			catch (\Twig_Error_Loader $e)
			{
				trigger_error('Can not instantiate TwigLoader: '.$e->getMessage(), E_USER_WARNING);
				return false;
			}
		}
		return $this->TwigLoader;
	}

	public function renderPage($template_file_name, array $vars = array())
	{
		$twig = $this->getTwigEnvironment();
		try
		{
			$template = $twig->loadTemplate($template_file_name);
		}
		catch (\Twig_Error_Loader $e)
		{
			trigger_error('Error when loading template: '.$e->getMessage(), E_USER_WARNING);
			return false;
		}
		$this->setURLsInVarsArray($vars);
		return $template->render($vars);
	}
}
