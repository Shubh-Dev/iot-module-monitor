<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Module;
use App\Models\ModuleHistory;
use Faker\factory as Faker;
use Carbon\Carbon;

class SimulateModuleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:module-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate and update module data every 3 seconds, and back it up in the history table';

    /**
     * Execute the console command.
     */
    // public function handle()
    // {
    //     $faker = Faker::create();
    //     for ($i = 0; $i < 10; $i++) {
    //         // Create and save new module data
    //         Module::create([
    //             'name' => $faker->word,  // Random module name
    //             'type' => $faker->word,
    //             'measured_value' => $faker->randomFloat(2, 10, 100),
    //             'operating_time' => $faker->numberBetween(1, 100), 
    //             'data_sent_count' => $faker->numberBetween(1, 1000),
    //             'status' => $faker->randomElement(['active', 'inactive', 'malfunction']),
    //         ]);
    //     }

    //     $this->info('Module data simulated and stored in the database!');
    // }

    public function handle()
    {
        $faker = Faker::create();

        // Get all modules (or choose specific ones)
        $modules = Module::all();

        // Loop forever to simulate new data every 3 seconds
        while (true) {
            foreach ($modules as $module) {
                // Backup current data in module_history
                ModuleHistory::create([
                    'module_id' => $module->id,
                    'measured_value' => $module->measured_value,
                    'status' => $module->status,
                    'operating_time' => $module->operating_time,
                    'data_sent_count' => $module->data_sent_count,
                    'recorded_at' => Carbon::now(), // Timestamp of the backup
                ]);

                // Generate new random data for the module
                $module->measured_value = $faker->randomFloat(2, 10, 100);
                $module->status = $faker->randomElement(['active', 'inactive', 'malfunction']);
                $module->operating_time = $faker->numberBetween(1, 100);
                $module->data_sent_count = $faker->numberBetween(1, 1000);
                $module->last_operated_at = Carbon::now(); // Update the timestamp

                // Save the updated data to the modules table
                $module->save();
            }

            // Log the action to the console (optional)
            $this->info('Module data simulated and updated.');

            // Wait for 3 seconds before repeating
            sleep(3);
        }
    }
}
