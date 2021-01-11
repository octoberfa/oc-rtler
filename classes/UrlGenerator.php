<?php

namespace OctoberFa\Rtler\Classes;

use App;
use File;
use Config;
use Request;
use Illuminate\Routing\UrlGenerator as baseGenerator;
use OctoberFa\Rtler\Models\Settings;
use RainLab\Translate\Classes\Translator;
use Backend\Models\Preference as BackendPreference;

class UrlGenerator extends baseGenerator
{
    public static $rtlLanguages = [
        'fa',   /* 'فارسی', Persian */
        'ae',    /* Avestan */
        'ar',   /* 'العربية', Arabic */
        'arc',  /* Aramaic */
        'bcc',  /* 'بلوچی مکرانی', Southern Balochi */
        'bqi',  /* 'بختياري', Bakthiari */
        'ckb',  /* 'Soranî / کوردی', Sorani */
        'dv',   /* Dhivehi */
        'glk',  /* 'گیلکی', Gilaki */
        'he',   /* 'עברית', Hebrew */
        'ku',   /* 'Kurdî / كوردی', Kurdish */
        'mzn',  /* 'مازِرونی', Mazanderani */
        'nqo',  /* N'Ko */
        'pnb',  /* 'پنجابی', Western Punjabi */
        'ps',   /* 'پښتو', Pashto, */
        'sd',   /* 'سنڌي', Sindhi */
        'ug',   /* 'Uyghurche / ئۇيغۇرچە', Uyghur */
        'ur',   /* 'اردو', Urdu */
        'yi'    /* 'ייִדיש', Yiddish */
    ];
    /**
     * Generate a URL to an application asset.
     *
     * @param  string $path
     * @param  bool|null $secure
     * @return string
     */
    public function asset($path, $secure = null)
    {

        if ($this->isValidUrl($path)) return $path;
        if (self::checkForRtl('editor_mode') || self::checkForRtl('markdown_editor_mode')) {
            if (strpos($path, 'modules/backend/formwidgets/codeeditor/assets/js/build-min.js')) {
                return parent::asset('/plugins/octoberfa/rtler/assets/js/codeeditor.min.js');
            }
        }
        if (self::checkForRtl('markdown_editor_mode')) {
            if (strpos($path, 'modules/backend/formwidgets/markdowneditor/assets/js/markdowneditor.js')) {
                return parent::asset('/plugins/octoberfa/rtler/assets/js/markdowneditor.js');
            }
        }
        if (self::checkForRtl('layout_mode')) {
            if (!strpos($path, '/octoberfa/rtler/assets/css/rtler.css')) {
                $backendUri = Config::get('cms.backendUri', 'backend');
                $requestUrl = Request::url();
                if (File::exists(
                    base_path(dirname($path)) . '.rtl.' . File::extension($path)
                )) {
                    $path = dirname($path) . '.rtl.' . File::extension($path);
                } else if (File::extension($path) == 'css' && (strpos($requestUrl, $backendUri) || strpos($path, 'plugins/') || strpos($path, 'modules/'))) {
                    $path = CssFlipper::flipCss($path);
                }
            }
        }
        return parent::asset($path, $secure);
    }
    /**
     * Generate an absolute URL to the given path.
     *
     * @param  string  $path
     * @param  mixed  $extra
     * @param  bool|null  $secure
     * @return string
     */
    public function to($path, $extra = [], $secure = null)
    {

        if ($this->isValidUrl($path)) {
            return $path;
        }
        if (!strpos($path, '/octoberfa/rtler/assets/css/rtler.css')) {
            $backendUri = Config::get('cms.backendUri', 'backend');
            $requestUrl = Request::url();
            if (File::exists(
                base_path(dirname($path)) . '.rtl.' . File::extension($path)
            )) {
                $path = dirname($path) . '.rtl.' . File::extension($path);
            } else if (File::extension($path) == 'css' && (strpos($requestUrl, $backendUri) || strpos($path, 'plugins/') || strpos($path, 'modules/'))) {
                $path = CssFlipper::flipCss($path);
            }
        }
        return parent::to($path, $extra, $secure);
    }

    /**
     * Get user locale
     *
     * @return string
     */
    private static function getCurrentLocale()
    {
        BackendPreference::setAppLocale();
        BackendPreference::setAppFallbackLocale();

        if (class_exists('RainLab\Translate\Classes\Translator')) {
            $translator = Translator::instance();
            return $translator->getLocale();
        }

        return App::getLocale();
    }

    /**
     * Detect user language is RTL or nor
     *
     * @return boolean
     */
    protected static function isLanguageRtl()
    {
        $locale = self::getCurrentLocale();
        $locale = strtolower(explode('_', $locale)[0]);
        if (in_array($locale, static::$rtlLanguages)) {
            return true;
        }
        return false;
    }

    /**
     * check settings for rtl mode
     *
     * @param string $what  what shuld check?
     *
     * @return boolean
     */
    public static function checkForRtl($what)
    {
        $value = Settings::get($what, 'language');
        if ($value === 'never') {
            return false;
        }
        if ($value === 'always') {
            return true;
        }
        return self::isLanguageRtl();
    }
}
