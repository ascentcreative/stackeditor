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
    public function __construct($name, $value, $label=null, $previewable = true,
                        $wrapper='none', $class='',     
                        $model = null   
                    )
    {
       
        $this->label = $label;
        $this->name = $name;

    
        if(is_string($value)) {
            $this->value = json_decode($value); //, true);
        } else {
            // this feels like such a fudge but works... 
            // used on validation fail when the incoming data is a pure array. 
            // encode / decode makes it match the expected object style.
            // works for now, but feels uncontrolled....
            $this->value = json_decode(json_encode($value));
        }
        
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

    /**
     * 
     * interrogates the incoming stack to build validation rules from the various Blocks (via their TypeDescriptors)
     * 
     * @param mixed $value
     * 
     * @return array|null
     */
    public static function getRules($stack, $value) : ?array {

        $rules = [];

        if(is_string($value)) {
            $value = json_decode($value);
        } else {
            $value = json_decode(json_encode($value));
        }

        $path['field'] = $stack;
        $path['rows'] = 'rows';
        $path['rowIdx'] = '-1';
        $path['blocks'] = 'blocks';
        $path['blockIdx'] = '-1';
    
        if(isset($value->rows)) {
            foreach($value->rows as $row) {
                $path['rowIdx'] = $path['rowIdx'] + 1;
                $path['blockIdx'] = -1;

                if(isset($row->blocks)) {
                    foreach($row->blocks as $block) {

                        $path['blockIdx'] = $path['blockIdx'] + 1;
                    
                        $r = resolveDescriptor($block->type)::rules();
                        
                        foreach($r as $field=>$validators) {
                            
                            $rules[join('.', $path) . '.' . $field] = $validators;
                        }
                    }
                }
            }
        }

        return $rules; 

    }

     /**
     * 
     * interrogates the incoming stack to build validation faliure messages from the various Blocks (via their TypeDescriptors)
     * 
     * @param mixed $value
     * 
     * @return array|null
     */
    public static function getMessages($stack, $value) : ?array {

        $msgs = [];

        if(is_string($value)) {
            $value = json_decode($value);
        } else {
            $value = json_decode(json_encode($value));
        }

        $path['field'] = $stack;
        $path['rows'] = 'rows';
        $path['rowIdx'] = '-1';
        $path['blocks'] = 'blocks';
        $path['blockIdx'] = '-1';
    
        if(isset($value->rows)) {

            foreach($value->rows as $row) {
                $path['rowIdx'] = $path['rowIdx'] + 1;
                $path['blockIdx'] = -1;

                if(isset($row->blocks)) {
                    foreach($row->blocks as $block) {

                        $path['blockIdx'] = $path['blockIdx'] + 1;
                    
                        $r = resolveDescriptor($block->type)::messages();
                        
                        foreach($r as $field=>$validators) {
                            
                            $msgs[join('.', $path) . '.' . $field] = $validators;
                        }


                        // $rules = array_merge($rules, );
                    }
                }
            }
        }

        return $msgs;

    }

}
