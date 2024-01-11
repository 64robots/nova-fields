<template>
  <r64-default-field

    :field="field"
    :hide-label="hideLabelInForms"
    :field-classes="fieldClasses"
    :wrapper-classes="wrapperClasses"
    :label-classes="labelClasses"
  >
    <template #field>
      <checkbox
        :class="[inputClasses]"
        @input="toggle"
        :id="field.name"
        :name="field.name"
        :checked="checked"
        :disabled="field.readonly"
      />

      <p v-if="hasError" class="my-2 text-red-500" v-html="firstError" />
    </template>
  </r64-default-field>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova';
import R64Field from '../../mixins/R64Field'

export default {
  mixins: [HandlesValidationErrors, FormField, R64Field],

  data: () => ({
    value: false
  }),

  computed: {
    checked() {
      return Boolean(this.value)
    },

    trueValue() {
      return +this.checked
    }
  },

  mounted() {
    this.value = this.field.value || false

    this.field.fill = formData => {
      formData.append(this.field.attribute, this.trueValue)
    }
  },

  methods: {
    toggle() {
      this.value = !this.value
    }
  }
}
</script>
