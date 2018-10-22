<?php

namespace R64\NovaFields;

trait HasChilds
{
    /**
     * Set the config that should be applied to the child fields.
     *
     * @param  array  $childConfig
     * @return $this
     */
    public function childConfig($childConfig)
    {
        return $this->withMeta(['childConfig' => $childConfig]);
    }
}
