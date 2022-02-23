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
      <textarea
        type="text"
        v-model="value"
        :class="[errorClasses, inputClasses]"
        v-bind="extraAttributes"
        :disabled="field.readonly"
      />
      <p v-if="hasError" class="my-2 text-danger">
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

  watch: {
    value(value) {
      this.$emit('input', value)
    }
  },

  computed: {
    defaultAttributes() {
      return {
        rows: this.field.rows,
        disabled: this.readOnly,
        placeholder: this.placeholder
      }
    },

    extraAttributes() {
      const attrs = this.field.extraAttributes

      return {
        ...this.defaultAttributes,
        ...attrs
      }
    }
  }
}
</script>
