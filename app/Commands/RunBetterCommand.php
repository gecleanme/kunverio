<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Input\Text;
use PhpSchool\CliMenu\Input\InputIO;
use function Termwind\{render};



class RunBetterCommand extends Command
{
    protected $signature = 'runbetter';

    protected $description = 'Run the Script better (hopefully)';

    public function handle()
    {

        $value = 100;
        $from_unit = '';
        $to_unit = '';
    
    
     
        $fromCallable = function (CliMenu $menu) use (&$from_unit) {
               $from_unit = $menu->getSelectedItem()->getText();
               echo $from_unit;
        };

        $toCallable = function (CliMenu $menu) use (&$to_unit) {
            $to_unit = $menu->getSelectedItem()->getText();
            echo $to_unit;
     };

     $valueCallable = function (CliMenu $menu) use (&$to_unit,&$from_unit, &$value) {

        $in_meter = round($this->to_meter($from_unit, $value), 3); // first convert to meter
        $tovalue = round($this->from_meter($to_unit, $in_meter), 3); //then convert from meter
        echo $tovalue;
 };



        $menu = (new CliMenuBuilder)
            ->setTitle('Step I: Select a Length unit to convert from')
            ->addItem('Feet', $fromCallable)
            ->addSubMenu('Submenu', function (CliMenuBuilder $b) use ($toCallable, $valueCallable) {
                $b->setTitle('Step II: Select a Length unit to convert to')
                    ->addItem('Inch', $toCallable)
                    ->addSubMenu('Submenu', function (CliMenuBuilder $c) use ($valueCallable) {
                        $c->setTitle('Step III: Measurment Value')
                            ->addItem('Enter Value', $valueCallable);
                            
                    });
            })
            ->addLineBreak('-')
            ->setWidth(80)
            ->setMarginAuto()     
            ->setForegroundColour('green')
            ->setBackgroundColour('black')
            ->build()
            ->open();
    }

    protected function to_meter($from_unit, $value)
    {
        switch ($from_unit) {
            case 'Inch':
                return $value * 0.0254;
            case 'Feet':
                return $value * 0.3048;
            case 'Yard':
                return $value * 0.9144;
            case 'Mile':
                return $value * 1609.344;
            case 'Millimeter':
                return $value * 0.001;
            case 'Centimeter':
                return $value * 0.01;
            case 'Meter':
                return $value;
            case 'Kilometer':
                return $value * 1000;

        }
    }

    protected function from_meter($to_unit, $value)
    {
        switch ($to_unit) {
            case 'Inch':
                return $value / 0.0254;
            case 'Feet':
                return $value / 0.3048;
            case 'Yard':
                return $value / 0.9144;
            case 'Mile':
                return $value / 1609.344;
            case 'Millimeter':
                return $value / 0.001;
            case 'Centimeter':
                return $value / 0.01;
            case 'Meter':
                return $value;
            case 'Kilometer':
                return $value / 1000;

        }
    }


}
