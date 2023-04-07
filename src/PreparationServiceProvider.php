<?php

declare(strict_types = 1);

namespace Yesccx\Preparation;

use Illuminate\Support\ServiceProvider;
use Yesccx\Preparation\Console\PreparationMakeCommand;
use Yesccx\Preparation\BasePre;

class PreparationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();

        $this->registerResolvingHandler();
    }

    /**
     * Setup the configuration.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/preparation.php',
            'preparation'
        );
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            PreparationMakeCommand::class
        ]);
    }

    /**
     * Register resolving handler
     *
     * @return void
     */
    protected function registerResolvingHandler(): void
    {
        $this->app->afterResolving(
            BasePre::class,
            function (BasePre $resolved) {
                if (!method_exists($resolved, 'handle')) {
                    throw new \Exception('Undefined handle method');
                }

                $this->app->call([$resolved, 'handle']);
            }
        );
    }
}
