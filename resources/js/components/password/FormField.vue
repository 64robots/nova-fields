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
      <input
        :id="field.name"
        :dusk="field.attribute"
        type="password"
        v-model="value"
        :class="[errorClasses, inputClasses]"
        :placeholder="placeholder"
        :disabled="readOnly"

        @input="$emit('input', $event.target.value)"
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
  mixins: [HandlesValidationErrors, FormField, R64Field]
}
</script>
