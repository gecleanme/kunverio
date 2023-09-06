<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class TestCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $list = (new CliMenuBuilider()) //still unrecognized 
        ->setTitle('Choose Unit to convert from')
            ->build();
        $list->open();

        function to_meter($from_unit, $value)
        {
            switch ($from_unit) {
                case 'inch':
                    return $value * 0.0254;
                case 'feet':
                    return $value * 0.3048;
                case 'yard':
                    return $value * 0.9144;
                case 'mile':
                    return $value * 1609.344;
                case 'millimeter':
                    return $value * 0.001;
                case 'centimeter':
                    return $value * 0.01;
                case 'meter':
                    return $value;
                case 'kilometer':
                    return $value * 1000;

            }
        }

        function from_meter($to_unit, $value)
        {
            switch ($to_unit) {
                case 'inch':
                    return $value / 0.0254;
                case 'feet':
                    return $value / 0.3048;
                case 'yard':
                    return $value / 0.9144;
                case 'mile':
                    return $value / 1609.344;
                case 'millimeter':
                    return $value / 0.001;
                case 'centimeter':
                    return $value / 0.01;
                case 'meter':
                    return $value;
                case 'kilometer':
                    return $value / 1000;

            }
        }


        $in_meter = round(to_meter($from_unit, $value), 3);
        $tovalue = round(from_meter($to_unit, $in_meter), 3);

    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
