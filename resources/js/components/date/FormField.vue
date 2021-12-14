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
      <DateTimePicker
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

      <p
        v-if="hasError"
        class="my-2 text-danger"
      >
        {{ firstError }}
      </p>
    </template>
  </r64-default-field>
</template>

<script>
import {
  FormField,
  HandlesValidationErrors,
  InteractsWithDates
} from 'laravel-nova'
import DateTimePicker from '../date/DateTimePicker'
import R64Field from '../../mixins/R64Field'

export default {
  components: { DateTimePicker },
  mixins: [HandlesValidationErrors, FormField, InteractsWithDates, R64Field],

  methods: {
    /*
     * Set the initial value for the field
     */
    setInitialValue() {
      // Set the initial value of the field
      this.value = this.field.value || ''
    },

    /**
     * On save, populate our form data
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value || '')
    },

    /**
     * Update the field's internal value when it's value changes
     */
    handleChange(value) {
      this.value = value
    },
  },

  computed: {
    firstDayOfWeek() {
      return this.field.firstDayOfWeek || 0
    },

    placeholder() {
      const format = this.field.format ? this.field.format : 'YYYY-MM-DD'
      return this.field.placeholder || moment().format(format)
    }
  }
}
</script>
