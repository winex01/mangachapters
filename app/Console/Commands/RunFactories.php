<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RunFactories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'winex:factories 
        {count : The factory counts}
        {--model= : Run provided model factory, Ex. Model1,Model2}
        {--priority= : Run provided models first, Ex. Model1,Model2}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all available factories.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (config('app.env') != 'local') {
            return $this->info('Cant\' run factories in production.'); 
        }

        $factories = Storage::createLocalDriver([
            'root' => base_path('/database/factories')
        ])->files();

        $count      = $this->argument('count');
        $models     = $this->option('model');
        $priorities = $this->option('priority') ?: [];

        if ($models) {
            $models = explode(',', $models);
            foreach ($models as $model) {
                $model = str_replace(' ', '', $model);
                classInstance($model)::factory()->count($count)->create();
            }

            return $this->info('Database factory '.$model.' completed successfully.');
        }

        if ($priorities) {
           $priorities = explode(',', $priorities);
            foreach ($priorities as $model) {
                $model = str_replace(' ', '', $model);
                classInstance($model)::factory()->count($count)->create();
                $this->info('Running: Factory '.$model.' completed');
            }
        }

        foreach ($factories as $factory) {
            $factory = str_replace('Factory.php', '', $factory);

            if (in_array($factory, $priorities)) {
                continue;
            }

            classInstance($factory)::factory()->count(
                $this->argument('count')
            )->create();

            $this->info('Running: Factory '.$factory.' completed');
        }
        
        return $this->info('Database factories completed successfully.'); 
    }

}
