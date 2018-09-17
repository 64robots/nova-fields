<?php

namespace R64\NovaFields;

trait HasChilds
{
    /**
     * Set the config that should be applied to the child fields.
     *
     * @param  string  $classes
     * @return $this
     */
    public function childConfig($classes)
    {
        return $this->withMeta(['childConfig' => $classes]);
    }
}
