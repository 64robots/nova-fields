<template>
  <r64-default-field
    :hide-field="hideField"
    :field="field"
    :hide-label="hideLabelInForms"
    :field-classes="fieldClasses"
    :wrapper-classes="wrapperClasses"
    :label-classes="labelClasses"
  >
    <template slot="field">
      <date-time-picker
              :dusk="field.attribute"
              :name="field.name"
              :value="value"
              dateFormat="Y-m-d"
              :placeholder="placeholder"
              :enable-time="false"
              :enable-seconds="false"
              :first-day-of-week="firstDayOfWeek"
              :class="[errorClasses, inputClasses]"
              @change="handleChange"
              :disabled="isReadonly"
      />

      <p v-if="hasError" class="my-2 text-danger">
        {{ firstError }}
      </p>
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors, InteractsWithDates } from 'laravel-nova'
import R64Field from '../../mixins/R64Field'

export default {
  mixins: [HandlesValidationErrors, FormField, InteractsWithDates, R64Field],

  computed: {
    firstDayOfWeek() {
      return this.field.firstDayOfWeek || 0
    },

    placeholder() {
      return this.field.placeholder || moment().format('YYYY-MM-DD')
    },
  }
}
</script>
