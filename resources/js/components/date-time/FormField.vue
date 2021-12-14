<template>
  <r64-default-field
      :hide-field="hideField"
      :field="field"
      :show-help-text="showHelpText"
      :hide-label="hideLabelInForms"
      :field-classes="fieldClasses"
      :wrapper-classes="wrapperClasses"
      :label-classes="labelClasses"
  >
    <template slot="field">
      <div class="flex items-center">
        <date-time-picker
            :dusk="field.attribute"
            :name="field.name"
            :value="localizedValue"
            :dateFormat="field.pickerFormat"
            :placeholder="placeholder"
            :enable-time="true"
            :enable-seconds="true"
            :first-day-of-week="firstDayOfWeek"
            :class="[errorClasses, inputClasses]"
            @change="handleChange"
            :disabled="isReadonly"
        />

        <span v-if="!field.hideTimezone" class="text-80 text-sm ml-2">({{ userTimezone }})</span>
      </div>

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
import
{
  FormField,
  HandlesValidationErrors,
  InteractsWithDates,
} from 'laravel-nova'
import R64Field from "../../mixins/R64Field";

export default {

  mixins: [HandlesValidationErrors, FormField, InteractsWithDates,  R64Field],

  data: () => ({ localizedValue: '' }),

  methods: {
    /*
     * Set the initial value for the field
     */
    setInitialValue() {
      // Set the initial value of the field
      this.value = this.field.value || ''

      // If the field has a value let's convert it from the app's timezone
      // into the user's local time to display in the field
      if (this.value !== '') {
        this.localizedValue = this.fromAppTimezone(this.value)
      }
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
      this.value = this.toAppTimezone(value)
    },
  },

  computed: {
    firstDayOfWeek() {
      return this.field.firstDayOfWeek || 0
    },

    format() {
      return this.field.format || 'YYYY-MM-DD HH:mm:ss'
    },

    placeholder() {
      return this.field.placeholder || moment().format(this.format)
    },

    pickerFormat() {
      return this.field.pickerFormat || 'Y-m-d H:i:S'
    },

    pickerDisplayFormat() {
      return this.field.pickerDisplayFormat || 'Y-m-d H:i:S'
    },
  },
}
</script>
