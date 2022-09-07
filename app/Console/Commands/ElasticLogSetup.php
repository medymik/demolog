<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ElasticLogSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:log_setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup elastic log index';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $index = rtrim(config('elastic.prefix'), '_') . '_' . config('elastic.index');

        if (!$this->client->indices()->exists(['index' => $index])) {
            $this->client->indices()->create([
                'index' => $index,
            ]);
        }
    }
}
