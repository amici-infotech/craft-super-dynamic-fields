<?php
namespace amici\SuperDynamicFields\assetbundles;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class SuperDynamicFieldsSettingsAsset extends AssetBundle
{

    public function init(): void
    {
        $this->sourcePath = "@amici/SuperDynamicFields/resources/settings/dist";

        $this->depends = [
            // VerbbCpAsset::class,
            CpAsset::class,
        ];

        $this->css = [
            'css/screen.css',
        ];

        $this->js = [
            'js/scripts.js',
        ];

        parent::init();
    }
}
