<?php
namespace amici\SuperDynamicFields\libraries;

use Craft;
use craft\helpers\FileHelper;
use amici\SuperDynamicFields\SuperDynamicFields;

class General
{

	public $plugin;

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