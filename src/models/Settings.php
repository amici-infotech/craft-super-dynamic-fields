<?php
namespace amici\SuperDynamicFields\models;

use Craft;
use craft\base\Model;

class Settings extends Model
{

    public string $defaultTemplatesExt = 'json';
    public string $defaultTemplatesPath = 'templates';

}