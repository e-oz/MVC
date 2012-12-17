<?php
namespace Jamm\MVC\Views;
abstract class PageRenderer implements IPageRenderer
{
	private $base_url;
	private $full_url;
	private $templates_dir;

	public function __construct($templates_dir)
	{
		$this->setTemplatesDir($templates_dir);
	}

	protected function setURLsInVarsArray(array &$vars)
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

	protected function getTemplatesDir()
	{
		return $this->templates_dir;
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
	public function getFullUrl()
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

	public function setTemplatesDir($templates_dir)
	{
		$this->templates_dir = rtrim($templates_dir, '/');
	}

	public function getBaseUrl()
	{
		return $this->base_url;
	}
}
