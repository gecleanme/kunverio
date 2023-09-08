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



class RunCommand extends Command
{
    protected $signature = 'run';

    protected $description = 'Run the Script';

    public function handle()
    {
        $value =0;
        $from_unit = $to_unit = $menu= '';

        $from_unit = $this->menu('Choose a length Unit to convert from')
                    ->setForegroundColour('green')
                    ->setBackgroundColour('black')
                    ->addOption('inch', 'Inch')
                    ->addOption('centimeter', 'Centimeter')
                    ->addOption('feet', 'Feet')
                    ->addOption('yard', 'Yard')
                    ->addOption('mile', 'Mile')
                    ->addOption('meter', 'Meter')
                    ->addOption('millileter', 'Millileter')
                    ->addOption('kilometer', 'Kilometer')
                    ->setWidth(80)
                    ->open();

        $itemCallable = function (CliMenu $menu) use (&$value, &$from_unit, &$to_unit) {

            $style = (new MenuStyle())
                ->setBg('yellow')
                ->setFg('black');
                
            $input = new class (new InputIO($menu, $menu->getTerminal()), $style) extends Text {
                public function validate(string $value) : bool
                {
                    if(is_numeric($value)){
                    return true;

                }

                    else {
                        return false;
                    }
                }
            };
            
            $value = $input->setPromptText('Enter Length in '.ucfirst($from_unit).'')->ask()->fetch();
        
        
        };
        
        
        $menu = (new CliMenuBuilder)
            ->setTitle('Step II: Length')
            ->addItem('Enter/Modify Length', $itemCallable)
            ->addLineBreak('-')
            ->setWidth(80)
            ->setMarginAuto()     
            ->setForegroundColour('green')
            ->setBackgroundColour('black')
            ->setExitButtonText("Continue")    
            ->build();
        
        $menu->open();


        $to_unit = $this->menu('Choose a length Unit to convert to')
                    ->setForegroundColour('green')
                    ->setBackgroundColour('black')
                    ->addOption('inch', 'Inch')
                    ->addOption('centimeter', 'Centimeter')
                    ->addOption('feet', 'Feet')
                    ->addOption('yard', 'Yard')
                    ->addOption('mile', 'Mile')
                    ->addOption('meter', 'Meter')
                    ->addOption('millileter', 'Millileter')
                    ->addOption('kilometer', 'Kilometer')
                    ->setWidth(80)
                    ->open();


                    
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

        if(is_numeric($value)){

        $in_meter = round(to_meter($from_unit, $value), 3); // first convert to meter
        $tovalue = round(from_meter($to_unit, $in_meter), 3); //then convert from meter
        }

        else{
            $render_string ='<div class="py-1 ml-2 w-full justify-center text-center text-white bg-red-500">Invalid Input</div>';
            render($render_string);
            exit();
        }

        

        $render_string= '<div class="py-1 ml-2 w-full justify-center text-center">
            <div class="px-1 bg-blue-300 text-black">Kunverio</div>
            <em class="ml-1 bg-yellow-500 text-black">
            Converting '. $value .' '.ucfirst ($from_unit) .' to '. ucfirst($to_unit).'
        </em>


        <b class="block bg-green-500 text-white p-8 w-full justify-center text-center"> '. $value .' '.ucfirst($from_unit) .' is '. $tovalue. ' '.ucfirst($to_unit).'  </b>

        </div>';
        
        render($render_string);
        

    
    }
}