<?php namespace Bugsnag\BugsnagLaravel;

use Illuminate\Support\ServiceProvider;

class BugsnagLaravelServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('bugsnag/bugsnag-laravel', 'bugsnag');

        $app = $this->app;

        // Register for exception handling
        $app->error(function (\Exception $exception) use ($app) {
            $app['bugsnag']->notifyException($exception);
        });

        // Register for fatal error handling
        $app->fatal(function ($exception) use ($app) {
            $app['bugsnag']->notifyException($exception);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bugsnag', function ($app) {
            $config = $app['config']['bugsnag'] ?: $app['config']['bugsnag::config'];

            $client = new \Bugsnag_Client($config['api_key']);
            $client->setStripPath(base_path());
            $client->setProjectRoot(app_path());
            $client->setAutoNotify(false);
            $client->setBatchSending(false);
            $client->setReleaseStage($app->environment());
            $client->setNotifier(array(
                'name'    => 'Bugsnag Laravel',
                'version' => '1.1.1',
                'url'     => 'https://github.com/bugsnag/bugsnag-laravel'
            ));

            if (is_array($stages = $config['notify_release_stages'])) {
                $client->setNotifyReleaseStages($stages);
            }

            // Check if someone is logged in.
            if ($app['auth']->check()) {
                // User is logged in.
                $user = $app['auth']->user();

                // If these attributes are available: pass them on.
                $client->setUser(array('id' => $user->getAuthIdentifier()));
            }

            return $client;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array("bugsnag");
    }
}
