<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Style\SelectableStyle;
use PhpSchool\CliMenu\Input\Text;
use PhpSchool\CliMenu\Input\InputIO;
use function Termwind\{render};



class RunBetterCommand extends Command
{
    protected $signature = 'runbetter';

    protected $description = 'Run the Script better (hopefully)';

    public function handle()
    {

        $value = 0;
        $from_unit = '';
        $to_unit = '';



        $fromCallable = function (CliMenu $menu) use (&$from_unit) {
            if ($menu->getSelectedItem()->showsItemExtra()) {
                $menu->getSelectedItem()->hideItemExtra();
            } else {
                $menu->getSelectedItem()->showItemExtra();
            }
            $menu->redraw();
            $from_unit = $menu->getSelectedItem()->getText();
        };

        $toCallable = function (CliMenu $menu) use (&$to_unit) {
            if ($menu->getSelectedItem()->showsItemExtra()) {
                $menu->getSelectedItem()->hideItemExtra();
            } else {
                $menu->getSelectedItem()->showItemExtra();
            }
            $menu->redraw();

            $to_unit = $menu->getSelectedItem()->getText();
        };



        $valueCallable = function (CliMenu $menu) use (&$value, &$from_unit, &$to_unit) {

            $style = (new MenuStyle())
                ->setBg('yellow')
                ->setFg('black');

            $input = new class (new InputIO($menu, $menu->getTerminal()), $style) extends Text {
                public function validate(string $value) : bool
                {
                    if(is_numeric($value))
                        return true;
                    else return false;
                }
            };

            $value = $input->setPromptText('Enter Length in '.$from_unit.'')->ask()->fetch();


            $in_meter = round($this->to_meter($from_unit, $value), 3); // first convert to meter
            $tovalue = round($this->from_meter($to_unit, $in_meter), 3); //then convert from meter

            $menu->close();

            $render_string= '<div class="py-1 ml-2 w-full justify-center text-center">
        <div class="px-1 bg-blue-300 text-black">Kunverio</div>
        <em class="ml-1 bg-yellow-500 text-black">
        Converting '. $value .' '.$from_unit.' to '.$to_unit.'
        </em>
        <b class="block bg-green-500 text-white p-8 w-full justify-center text-center"> '. $value .' '.$from_unit .' is '. $tovalue. ' '.$to_unit.'  </b>

        </div>'

            ;

            render($render_string);

        };

        $menu = (new CliMenuBuilder)
            ->setTitle('Step I: Select a Length unit to convert from')
            ->setWidth(80)
            ->setMarginAuto()
            ->setForegroundColour('green')
            ->setBackgroundColour('black');

        $this->getMenuItems($menu, $fromCallable);

        $menu->addLineBreak('-')
            ->modifySelectableStyle(function (SelectableStyle $style) {
                $style->setItemExtra('[SELECTED]');
            })
            ->addSubMenu('Next', function (CliMenuBuilder $b) use ($toCallable, $valueCallable) {
                $b->setTitle('Step II: Select a Length unit to convert to');

                $this->getMenuItems($b, $toCallable);

                $b->addLineBreak('-')
                    ->modifySelectableStyle(function (SelectableStyle $style) {
                        $style->setItemExtra('[SELECTED]');
                    })
                    ->addSubMenu('Next', function (CliMenuBuilder $c) use ($valueCallable) {
                        $c->setTitle('Step III: Measurment Value')
                            ->addItem('Enter Value', $valueCallable);

                    });
            })
            ->build()
            ->open();
    }

    protected function to_meter($from_unit, $value): float
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

    protected function from_meter($to_unit, $value) : float
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


    protected function getMenuItems(CliMenuBuilder $menu, $callable)
    {
        $units = ['Inch' , 'Feet', 'Yard','Mile','Millimeter','Centimeter','Meter','Kilometer'];

        foreach($units as $unit){
            $menu->addItem($unit, $callable);
        }



    }

}
