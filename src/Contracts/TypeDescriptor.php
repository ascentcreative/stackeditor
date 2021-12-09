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





}