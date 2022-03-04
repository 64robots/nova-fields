<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\DateTime as NovaDateTime;

class DateTime extends NovaDateTime
{
    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'w-full form-control form-input form-input-bordered';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = 'whitespace-no-wrap';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-date-time';

    /**
     * Indicate that the timezone should be hidden in form view.
     *
     * @return $this
     */
    public function hideTimezone()
    {
        return $this->withMeta(['hideTimezone' => true]);
    }

    /**
     * @param  int  $hour
     */
    public function defaultHour($hour)
    {
        return $this->withMeta([__FUNCTION__ => $hour]);
    }

    /**
     * @param  int  $minute
     */
    public function defaultMinute($minute)
    {
        return $this->withMeta([__FUNCTION__ => $minute]);
    }

    /**
     * @param  bool  $seconds
     */
    public function enableSeconds($seconds)
    {
        return $this->withMeta([__FUNCTION__ => $seconds]);
    }

    /**
     * @param  bool  $time
     */
    public function enableTime($time)
    {
        return $this->withMeta([__FUNCTION__ => $time]);
    }

    /**
     * @param  bool  $time
     */
    public function setDefaultMinuteZero($value)
    {
        return $this->withMeta([__FUNCTION__ => $value]);
    }
}
