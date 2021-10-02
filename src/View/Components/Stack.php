<?php

namespace AscentCreative\StackEditor\View\Components;

use Illuminate\View\Component;

class Stack extends Component
{

   
    public $label;
    public $name;
    public $value;
  
    public $previewable;

    public $wrapper;
    public $class;



    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $name, $value, $previewable = true,
                        $wrapper='bootstrapformgroup', $class=''        
                    )
    {
       
        $this->label = $label;
        $this->name = $name;
        $this->value = json_decode($value);
       
        $this->previewable = $previewable;

        $this->wrapper = $wrapper;
        $this->class = $class;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('stackeditor::stack');
    }
}
