<?php

namespace AscentCreative\StackEditor\TypeDescriptors;

use AscentCreative\StackEditor\TypeDescriptors\AbstractDescriptor; 

class Image extends AbstractDescriptor { 

    public static $name = 'Image';

    public static $bladePath = 'image';

    public static $description = "Upload an image - also supply Alt text and link URLs";

    public static $category = "General";

}