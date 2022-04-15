<?php
namespace amici\SuperDynamicFields\libraries;

use Craft;
use craft\helpers\FileHelper;
use amici\SuperDynamicFields\SuperDynamicFields;
use craft\base\Plugin;

class General
{

	public Plugin $plugin;

	function __construct()
	{
		$this->plugin = SuperDynamicFields::$plugin;
	}

	function getSettings($key = "")
	{

		$settings = $this->plugin->getSettings();

		if($key == "")
		{
			return (array) $settings;
		}
		else
		{
			return isset($settings->$key) ? $settings->$key : "";
		}

	}

}