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

    public $model;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $name, $value, $previewable = true,
                        $wrapper='bootstrapformgroup', $class='',     
                        $model = null   
                    )
    {
       
        $this->label = $label;
        $this->name = $name;
        $this->value = json_decode($value);
       
        $this->previewable = $previewable;

        $this->wrapper = $wrapper;
        $this->class = $class;

        try {
            if (!is_string($model)) {
                $this->model = get_class($model);
            } else {
                $this->model = $model;
            }
        } catch (Exception $e) {
            $this->model = null;
        }
       


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
