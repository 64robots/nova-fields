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
          ref="dateTimePicker"
          :dusk="field.attribute"
          :name="field.name"
          :value="value"
          dateFormat="Y-m-d"
          :alt-format="pickerDisplayFormat"
          :placeholder="placeholder"
          :enable-time="false"
          :enable-seconds="false"
          :first-day-of-week="firstDayOfWeek"
          :class="[errorClasses, inputClasses]"
          @change="handleChange"
          :disabled="isReadonly"
      />

      <a
          v-if="field.nullable"
          @click.prevent="$refs.dateTimePicker.clear()"
          href="#"
          :title="__('Clear value')"
          tabindex="-1"
          class="p-1 px-2 cursor-pointer leading-none focus:outline-none"
          :class="{
            'text-50': !value.length,
            'text-black hover:text-danger': value.length,
          }"
      >
        <icon type="x-circle" width="22" height="22" viewBox="0 0 22 22" />
      </a>

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
import R64Field from '../../mixins/R64Field'

export default {
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
      return this.field.placeholder || moment().format(this.format)
    },

    format() {
      return this.field.format || 'YYYY-MM-DD'
    },

    pickerFormat() {
      return this.field.pickerFormat || 'Y-m-d'
    },

    pickerDisplayFormat() {
      return this.field.pickerDisplayFormat || 'Y-m-d'
    },
  }
}
</script>
