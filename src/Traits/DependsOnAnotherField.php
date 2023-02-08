<?php

namespace R64\NovaFields\Traits;

trait DependsOnAnotherField
{
    protected $dependsOn = null;
    protected $dependentValues = [];

    public function dependsOn($field)
    {
        $this->dependsOn = $field;

        return $this;
    }

    protected function getDependsOn()
    {
        $attributes = value($this->dependsOn);

        if($attributes === null) {
            return [];
        }

        return is_string($attributes) ? [$attributes] : $attributes;
    }

    protected function extractDependentValues($model)
    {
        if($this->dependsOn === null) {
            $this->dependentValues = [];
        } else {
            $attributes = $this->getDependsOn();

            $values = [];
            foreach ($attributes as $attribute) {
                $values[$attribute] = $this->resolveAttribute($model, $attribute);
            }

            $this->dependentValues = $values;
        }
    }
}
