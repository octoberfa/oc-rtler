<?php

namespace OctoberFa\Rtler;

use Lang;
use Backend;
use OctoberFa\Rtler\Classes\UrlGenerator;
use OctoberFa\Rtler\Models\Settings;
use System\Classes\PluginBase;

/**
 * Rtler Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * @var bool Plugin requires elevated permissions.
     */
    public $elevated = true;

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'octoberfa.rtler::lang.plugin.name',
            'description' => 'octoberfa.rtler::lang.plugin.description',
            'author'      => 'OctoberFa',
            'icon'        => 'icon-anchor'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        // Check if we are currently in backend module.
        if (!\App::runningInBackend()) {
            return;
        }
        $this->registerUrlGenerator();
        // Listen for `backend.page.beforeDisplay` event.
        \Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
            if (!\Request::ajax() && UrlGenerator::checkForRtl('layout_mode')) {
                $controller->addCss(\Config::get('cms.pluginsPath') . ('/octoberfa/rtler/assets/css/rtler.css'));
                $controller->addJs(\Config::get('cms.pluginsPath') . ('/octoberfa/rtler/assets/js/rtler.min.js'));
            }
        });
    }


    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'octoberfa.rtler.change_settings' => [
                'tab' => 'octoberfa.rtler::lang.permissions.tab',
                'label' => 'octoberfa.rtler::lang.permissions.label'
            ],
        ];
    }


    protected function registerUrlGenerator()
    {
        $this->app->singleton('url', function ($app) {
            $routes = $app['router']->getRoutes();
            $url = new UrlGenerator(
                $routes,
                $app->rebinding(
                    'request',
                    $this->requestRebinder()
                )
            );
            $url->setSessionResolver(function () {
                return $this->app['session'];
            });
            // If the route collection is "rebound", for example, when the routes stay
            // cached for the application, we will need to rebind the routes on the
            // URL generator instance so it has the latest version of the routes.
            $app->rebinding('routes', function ($app, $routes) {
                $app['url']->setRoutes($routes);
            });
            return $url;
        });
    }

    protected function requestRebinder()
    {
        return function ($app, $request) {
            $app['url']->setRequest($request);
        };
    }

    public function registerSettings()
    {
        // dd(Lang::get('octoberfa.rtler::lang'));
        return [
            'rtler' => [
                'label'       => 'octoberfa.rtler::lang.setting.menu',
                'description' => 'octoberfa.rtler::lang.setting.description',
                'category'    => 'octoberfa.rtler::lang.setting.category',
                'icon'        => 'icon-anchor',
                'class'       => 'OctoberFa\Rtler\Models\Settings',
                'order'       => 500,
                'keywords'    => 'octoberfa rtler',
                'permissions' => ['octoberfa.rtler.change_settings']
            ]
        ];
    }
}
