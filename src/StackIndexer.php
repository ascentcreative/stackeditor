<?php
namespace AscentCreative\StackEditor;

use Illuminate\Database\Eloquent\Model;

/**
 * Extracts the text content from a pagebuilder field
 *  - for use with ascentcreative/sitesearch
 */
class StackIndexer {

    /**
     * @param mixed $input - the structured data from the content field
     * 
     * @return : String - the extracted text
     */
    public static function extract(Model $model, $field) : String {

        $input = $model->$field;

        if(!is_array($input)) {
            $input = json_decode($input, true);
        }

        // dd($input);

        $out = [];
        if(is_array($input)) {
            foreach($input['rows'] as $row) {
        
                foreach($row['blocks'] as $block) {
      
                    if(is_array($block)) {
                        $descriptor = resolveDescriptor($block['type']);
                        if($descriptor) {
                            $instance = new $descriptor();
                            $out[] = $instance->extractText($model, $block);
                            // $out[] = 
                        }
                    }

                }

            }
        } else {
            // echo 'not array: ' . $input;
        }

        return join(' ', $out);

    }

}