<?php

namespace AscentCreative\StackEditor\View\Components;

use Illuminate\View\Component;

class Row extends Component
{

   
  //  public $label;
  //  public $type;
    public $name;
    public $value;
  
  //  public $wrapper;
  //  public $class;



    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $value)
    {
       
    //    $this->type = $type;
        $this->name = $name;
        $this->value = $value;
    
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        // Allows for block blades to be in either the main project or loaded from the cms package
        return view('stackeditor::row'); 
    }
}
