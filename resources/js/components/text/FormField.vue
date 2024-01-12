<template>
  <r64-default-field

    :field="field"
    :hide-label="hideLabelInForms"
    :field-classes="fieldClasses"
    :wrapper-classes="wrapperClasses"
    :label-classes="labelClasses"
  >
    <template #field>
      <input
        :id="field.name"
        :dusk="field.attribute"
        :type="inputType"
        :min="inputMin"
        :max="inputMax"
        :step="inputStep"
        :pattern="inputPattern"
        :disabled="field.readonly"
        v-model="value"
        :class="[errorClasses, inputClasses, { 'form-input-border-error': hasError }]"
        :placeholder="placeholder"

        @input="handleChange"
      />

      <p v-if="hasError" class="my-2 text-red-500">
        {{ firstError }}
      </p>
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import R64Field from '../../mixins/R64Field'

export default {
  mixins: [HandlesValidationErrors, FormField, R64Field],

  computed: {
    /**
     * Get the input type.
     */
    inputType() {
      return this.field.type || 'text'
    },

    /**
     * Get the input step amount.
     */
    inputStep() {
      return this.field.step
    },

    /**
     * Get the input minimum amount.
     */
    inputMin() {
      return this.field.min
    },

    /**
     * Get the input maximum amount.
     */
    inputMax() {
      return this.field.max
    },

    /**
     * Get the pattern that should be used for the field
     */
    inputPattern() {
      return this.field.pattern
    }
  }
}
</script>
