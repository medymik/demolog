<?php

namespace App\Providers;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Monolog\Formatter\ElasticsearchFormatter;
use Monolog\Handler\ElasticsearchHandler;

class ElasticLogProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $index = rtrim(config('elastic.prefix'), '_') . '_' . config('elastic_log.index');
        $type = config('elastic.type');
        
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()->setHosts([config('elastic.host')])->build();
        });

        $this->app->bind(ElasticsearchFormatter::class, function ($app) use ($index, $type) {
            return new ElasticsearchFormatter($index, $type);
        });

        $this->app->bind(ElasticsearchHandler::class, function ($app) use ($index, $type) {
            return new ElasticsearchHandler($app->make(Client::class), [
                'index'        => $index,
                'type'         => $type,
                'ignore_error' => false,
            ]);
        });
    }
}
