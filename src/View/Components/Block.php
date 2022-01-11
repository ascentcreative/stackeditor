<?php

namespace AscentCreative\StackEditor\View\Components;

use Illuminate\View\Component;

class Block extends Component
{

   
  //  public $label;
    public $type;
    public $name;
    public $value;

    public $defaults;
  
  //  public $wrapper;
  //  public $class;



    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type, $name, $value)
    {
       
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;

        if(!isset($value->unid)) {
            $value->unid = uniqid();
        }

        // work out the descriptor and load the defaults:
        $this->defaults = resolveDescriptor($type)::getDefaults();

    
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        // Allows for block blades to be in either the main project or loaded from the cms package
        return view('stackeditor::block'); 
    }
}
