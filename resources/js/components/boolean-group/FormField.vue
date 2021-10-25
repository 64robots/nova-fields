<template>
  <r64-default-field
      :field="field"
      :errors="errors"
      :show-help-text="showHelpText"
      :hide-field="hideField"
      :hide-label="hideLabelInForms"
      :field-classes="fieldClasses"
      :wrapper-classes="wrapperClasses"
      :label-classes="labelClasses"
  >
    <template slot="field">
      <checkbox-with-label
          :class="[inputClasses, {'opacity-50 pointer-events-none': readOnly }]"
          v-for="option in value"
          :key="option.name"
          :name="option.name"
          :checked="option.checked"
          @input="toggle($event, option)"
          :disabled="isReadonly"
      >
        {{ option.label }}
      </checkbox-with-label>
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import R64Field from '../../mixins/R64Field'

export default {
  mixins: [HandlesValidationErrors, FormField, R64Field],

  data: () => ({
    value: [],
  }),

  methods: {
    /*
     * Set the initial value for the field
     */
    setInitialValue() {
      this.field.value = this.field.value || {}

      this.value = _(this.field.options)
        .map(o => {
          return {
            name: o.name,
            label: o.label,
            checked: this.field.value[o.name] || false,
          }
        })
        .value()
    },

    /**
     * Provide a function that fills a passed FormData object with the
     * field's internal value attribute.
     */
    fill(formData) {
      formData.append(this.field.attribute, JSON.stringify(this.finalPayload))
    },

    /**
     * Toggle the option's value.
     */
    toggle(event, option) {
      const firstOption = _(this.value).find(o => o.name == option.name)
      firstOption.checked = event.target.checked
    },
  },

  computed: {
    /**
     * Return the final filtered json object
     */
    finalPayload() {
      return _(this.value)
        .map(o => [o.name, o.checked])
        .fromPairs()
        .value()
    },
  },
}
</script>
