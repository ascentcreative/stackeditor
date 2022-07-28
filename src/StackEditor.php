<?php
namespace AscentCreative\StackEditor;

use AscentCreative\Forms\Contracts\FormComponent;
use AscentCreative\Forms\FormObjectBase;
use AscentCreative\Forms\Traits\CanBeValidated;
use AscentCreative\Forms\Traits\CanHaveValue;


class StackEditor extends FormObjectBase implements FormComponent {

    use CanBeValidated, CanHaveValue;

    public $component = 'stackeditor';

    public function __construct($name) {
        $this->name = $name;
    }
    

}