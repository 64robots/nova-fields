<template>
  <r64-default-field
    :field="field"
    :hide-label="field.hideLabelInForms"
    :field-classes="field.fieldClasses"
    :wrapper-classes="field.wrapperClasses"
    :label-classes="field.labelClasses"
  >
    <template slot="field">
      <checkbox
        :class="inputClasses"
        @input="toggle"
        :id="field.name"
        :name="field.name"
        :checked="checked"
      />

      <p v-if="hasError" class="my-2 text-danger" v-html="firstError" />
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';

export default {
  mixins: [HandlesValidationErrors, FormField],

  data: () => ({
    value: false
  }),

  computed: {
    checked() {
      return Boolean(this.value);
    },

    trueValue() {
      return +this.checked;
    },

    /**
     * Get the input classes.
     */
    inputClasses() {
      return this.field.inputClasses || 'py-2';
    }
  },

  mounted() {
    this.value = this.field.value || false;

    this.field.fill = formData => {
      formData.append(this.field.attribute, this.trueValue);
    };
  },

  methods: {
    toggle() {
      this.value = !this.value;
    }
  }
};
</script>
