<?php

namespace AscentCreative\StackEditor\TypeDescriptors;

use AscentCreative\StackEditor\Contracts\TypeDescriptor as Contract; 

use Illuminate\Database\Eloquent\Model;

abstract class AbstractDescriptor implements Contract { 

    public static $name = 'DEFINEME';

    public static $bladePath = 'DE::FINE.ME';

    public static $description = "define me please";

    public static $category = "General";

    public static $applyTo = [];
    public static $excludeFrom = [];


    /**
     * @return string
     */
    public static function getName() : string {
        return static::$name;
    }


    /**
     * @return string
     */
    public static function getBladePath() : string {
        return static::$bladePath;
    }


    /**
     * @return string
     */
    public static function getDescription() : string {
        return static::$description;
    }


     /**
     * @return string
     */
    public static function getCategory() : string {
        return static::$category;
    }


    /**
     * @return string
     */
    public static function isApplicable($model=null) : bool {

        if(is_null($model)) {
            return true;
        } else {
            if($model instanceof Object) {
                $model = get_class($model);
            } else {
                $model = $model;  
            }   
        }

        if(count(static::$applyTo) != 0) {
            if(in_array($model, static::$applyTo)) {
                return true;
            } else {
                return false;
            }
        }

        if(count(static::$excludeFrom) != 0) {
            if(in_array($model, static::$applyTo)) {
                return false;
            } else {
                return true;
            }
        }

        return true;
    }


     /**
     * Return validation rules for the fields on the edit screen
     * @return array
     */
    public static function rules() : array {
        return [];
    }

    /**
     * Return validation failure messages for the fields on the edit screen
     * @return array
     */
    public static function messages() : array {
        return [];
    }


}