<?php

namespace R64\NovaFields\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class OptionsController extends Controller
{
    public function index(NovaRequest $request)
    {
        $attribute = $request->query('attribute');
        $parentValue = $request->query('parent');

        $resource = $request->newResource();
        $fields = $resource->updateFields($request);
        $dummyField = array([
            "component" => "tabs",
            "panel" => "Add Product",
            "fields" => $fields
        ]);
        $field = $this->findFieldByAttribute($dummyField,$attribute);
        $options = $field->getOptions($parentValue);
        return $options;
    }

    public function findFieldByAttribute($fieldObj,$attribute){
        foreach ($fieldObj as $field){
            if(gettype($field) == "array" && $field['component'] == "tabs"){
                foreach ($field["fields"] as $value){
                    if($value->component == "dependency-container" && $value->component && array_key_exists("fields",$value->meta)){
                        $data =  $this->getChildSelect($value->meta,$attribute);
                        if($data !== null){
                            return $data;
                        }
                    }
                    if($value->component != "nova-fields-dynamic-select" && $value->component && array_key_exists("fields",$value->meta)){
                        $data =  $this->getChildSelect($value->meta,$attribute);
                        if($data !== null){
                            return $data;
                        }
                    }
                    if ((isset($value->attribute) && $value->attribute == $attribute) && ($value->component == "nova-fields-dynamic-select" || $value->component == "nova-fields-multi-select-dual-box")){
                        return $value;
                    }
                }
            }else{
                if(isset($field->attribute) &&
                    $field->attribute == $attribute){
                    return $field;
                }
            }
        }
    }

    public function getChildSelect($field,$attribute){
        foreach ($field["fields"] as $value){
            if ((isset($value->attribute) && $value->attribute == $attribute) && ($value->component == "nova-fields-dynamic-select" || $value->component == "nova-fields-multi-select-dual-box")){
                return $value;
            }
        }
    }
}
