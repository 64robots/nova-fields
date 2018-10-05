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
      <checkbox
        :class="[inputClasses, {'opacity-50 pointer-events-none': readOnly }]"
        @input="toggle"
        :id="field.name"
        :name="field.name"
        :checked="checked"
        :disabled="readOnly"
      />

      <p v-if="hasError" class="my-2 text-danger" v-html="firstError" />
    </template>
  </r64-default-field>
</template>

<script>
import FormField from 'laravel-nova/src/mixins/FormField'
import HandlesValidationErrors from 'laravel-nova/src/mixins/HandlesValidationErrors'
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
