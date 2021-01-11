<?php

namespace OctoberFa\Rtler\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'octoberfa_rtler';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
