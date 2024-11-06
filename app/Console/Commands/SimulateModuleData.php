<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Module;
use Faker\factory as Faker;

class SimulateModuleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:simulate-module-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate module data and store it in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            // Create and save new module data
            Module::create([
                'name' => $faker->word,  // Random module name
                'type' => $faker->word,
                'status' => $faker->randomElement(['active', 'inactive', 'malfunction']),
                'description' => $faker->sentence,  // Random description
                'current_value' => $faker->randomFloat(2, 10, 100),  // Random measured value (e.g., temperature)
                'operating_time' => $faker->numberBetween(1, 100),  // Random operating time
                'data_sent' => $faker->numberBetween(1, 1000),  // Random data sent count
            ]);
        }

        $this->info('Module data simulated and stored in the database!');
    }
}
