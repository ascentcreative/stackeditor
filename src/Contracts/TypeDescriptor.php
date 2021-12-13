<?php 

namespace AscentCreative\StackEditor\Contracts;

use Illuminate\Database\Eloquent\Model;

interface TypeDescriptor {


    /**
     * @return boolean
     */
    public static function getName() : string;


    /**
     * @return string
     */
    public static function getBladePath() : string;
    

    /**
     * @return string
     */
    public static function getDescription() : string;




    /**
     * @param Model $model
     * 
     * @return boolean
     */
    public static function isApplicable(Model $model=null) : bool;


    /**
     * Return validation rules for the fields on the edit screen
     * @return array
     */
    public static function rules() : array;

    /**
     * Return validation failure messages for the fields on the edit screen
     * @return array
     */
    public static function messages() : array;




}