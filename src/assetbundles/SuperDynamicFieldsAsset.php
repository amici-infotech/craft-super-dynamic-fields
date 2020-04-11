<?php
namespace amici\SuperDynamicFields\assetbundles;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

// use verbb\base\assetbundles\CpAsset as VerbbCpAsset;

class SuperDynamicFieldsAsset extends AssetBundle
{

    public function init()
    {
        $this->sourcePath = "@amici/SuperDynamicFields/resources/dist";

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
