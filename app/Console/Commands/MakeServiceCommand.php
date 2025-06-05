<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class';
    
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
            $name = $this->argument('name');
            $servicePath = app_path('Services/' . $name . '.php');
            
            if (File::exists($servicePath)) {
                $this->error('Service already exists!');
                return;
            }
            
            $stub = <<<EOD
    <?php

    namespace App\Services;

    class {$name}
    {
        public function __construct()
        {
            //
        }
    }
    EOD;

            File::ensureDirectoryExists(app_path('Services'));
            File::put($servicePath, $stub);
            
            $this->info('Service created successfully: ' . $name);
    }
    
    }
