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
      <the-mask v-if="this.field.mask"
        :id="field.name"
        :dusk="field.attribute"
        :type="inputType"
        :min="inputMin"
        :max="inputMax"
        :step="inputStep"
        :pattern="inputPattern"
        :disabled="readOnly"
        v-model="value"
        :mask="this.field.mask"
        :class="[errorClasses, inputClasses]"
        :placeholder="placeholder"

        @input="onMaskInput"
      />
      <input v-else
        :id="field.name"
        :dusk="field.attribute"
        :type="inputType"
        :min="inputMin"
        :max="inputMax"
        :step="inputStep"
        :pattern="inputPattern"
        :disabled="readOnly"
        v-model="value"
        :class="[errorClasses, inputClasses]"
        :placeholder="placeholder"

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
import {TheMask} from 'vue-the-mask'

export default {
  mixins: [HandlesValidationErrors, FormField, R64Field],

  components: {TheMask},
  
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
    },

  },
  
  methods: {
    fill(formData) {
      // if set to use raw value (no mask), remove mask chars from value
      let rawValue = this.value || "";
      if (this.field.raw) {
        for (let i = 0; i < this.field.mask.length; i++) {
          rawValue = rawValue.replace(this.field.mask[i], "");
        }
      }

      formData.append(this.field.attribute, rawValue);
    },
    onMaskInput(value) {
      this.$emit('input', value)
    },
  }
}
</script>
