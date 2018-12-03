<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\File as NovaFile;

class File extends NovaFile
{
    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'form-file-input';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = 'rounded-full w-8 h-8';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-file';

    /**
     * Get additional meta information to merge with the element payload.
     *
     * @return array
     */
    public function meta()
    {
        return array_merge([
            'wrapperClasses' => $this->wrapperClasses,
            'indexClasses' => $this->indexClasses,
            'inputClasses' => $this->inputClasses,
            'fieldClasses' => $this->fieldClasses,
            'panelFieldClasses' => $this->panelFieldClasses,
            'labelClasses' => $this->labelClasses,
            'panelLabelClasses' => $this->panelLabelClasses,
            'excerptClasses' => $this->excerptClasses,
            'thumbnailUrl' => $this->resolveThumbnailUrl(),
            'previewUrl' => call_user_func($this->previewUrlCallback),
            'downloadable' => isset($this->downloadResponseCallback) && ! empty($this->value),
            'deletable' => isset($this->deleteCallback) && $this->deletable,
        ], $this->meta);
    }
}
