<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Module;
use App\Models\ModuleHistory;
use Faker\Factory as Faker;
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

    //     // Check if there are any modules to update, and insert initial data if empty
    //     if (Module::count() == 0) {
    //             Module::create([
    //                 'name' => $faker->word,
    //                 'type' => $faker->word,
    //                 'measured_value' => $faker->randomFloat(2, 10, 100),
    //                 'operating_time' => $faker->numberBetween(1, 100),
    //                 'data_sent_count' => $faker->numberBetween(1, 1000),
    //                 'status' => $faker->randomElement(['active', 'inactive', 'malfunction']),
    //             ]);
            
    //         $this->info("Initial module data created.");
    //     }

    //     // Run the simulation loop
    //     while (true) {
           
    //      for($i = 0; $i < 10; $i++) {
    //         $modules = Module::all();  // Fetch all modules for each iteration
    //         foreach ($modules as $module) {
    //             // Backup current data in module_history table
    //             ModuleHistory::create([
    //                 'module_id' => $module->id,
    //                 'measured_value' => $module->measured_value,
    //                 'status' => $module->status,
    //                 'operating_time' => $module->operating_time,
    //                 'data_sent_count' => $module->data_sent_count,
    //                 'recorded_at' => Carbon::now(),
    //             ]);

    //             // Generate and save new random data for each module
    //             $module->measured_value = $faker->randomFloat(2, 10, 100);
    //             $module->status = $faker->randomElement(['active', 'inactive', 'malfunction']);
    //             $module->operating_time = $faker->numberBetween(1, 100);
    //             $module->data_sent_count = $faker->numberBetween(1, 1000);
    //             $module->last_operated_at = Carbon::now();

    //             // Save the updated module data
    //             $module->save();
    //         }
    //     }

    //         // Log the action to the console
    //         $this->info('Module data simulated and updated.');

    //         // Wait for 3 seconds before repeating
    //         sleep(3);
    //     }
    // }

    public function handle()
{
    $faker = Faker::create();

    // Check if there are any modules to update, and insert 10 initial rows if empty
    if (Module::count() == 0) {
        // Insert 10 rows of initial data
        for ($i = 0; $i < 10; $i++) {
            Module::create([
                'name' => $faker->word,
                'type' => $faker->word,
                'measured_value' => $faker->randomFloat(2, 10, 100),
                'operating_time' => $faker->numberBetween(1, 100),
                'data_sent_count' => $faker->numberBetween(1, 1000),
                'status' => $faker->randomElement(['active', 'inactive', 'malfunction']),
            ]);
        }

        $this->info("10 initial module data created.");
    }

    // Run the simulation loop
    while (true) {
        // Fetch all modules once per iteration
        $modules = Module::all();

        // Update each module's data and store it in the history
        foreach ($modules as $module) {
            // Backup current data in the module_history table (storing history)
            ModuleHistory::create([
                'module_id' => $module->id,
                'measured_value' => $module->measured_value,
                'status' => $module->status,
                'operating_time' => $module->operating_time,
                'data_sent_count' => $module->data_sent_count,
                'recorded_at' => Carbon::now(),
            ]);

            // Generate new random data for each module (excluding name and status)
            $module->measured_value = $faker->randomFloat(2, 10, 100);
            $module->operating_time = $faker->numberBetween(1, 100);
            $module->data_sent_count = $faker->numberBetween(1, 1000);
            $module->last_operated_at = Carbon::now();

            // Save the updated module data (name and status remain unchanged)
            $module->save();
        }

        // Log the action to the console
        $this->info('Module data simulated and updated.');

        // Wait for 3 seconds before repeating
        sleep(3);
    }
}

}

