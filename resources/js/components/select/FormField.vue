<template>
  <r64-default-field
    :field="field"
    :hide-label="field.hideLabelInForms"
    :field-classes="field.fieldClasses"
    :wrapper-classes="field.wrapperClasses"
    :label-classes="field.labelClasses"
  >
    <template slot="field">
      <select
        :id="field.name"
        v-model="value"
        :class="[inputClasses, errorClasses]"
        :disabled="field.readOnly"
      >
        <option value="" selected disabled>
          {{ placeholder }}
        </option>

        <option
          v-for="option in field.options"
          :key="option.value"
          :value="option.value"
          :selected="option.value == value"
        >
          {{ option.label }}
        </option>
      </select>

      <p v-if="hasError" class="my-2 text-danger">
          {{ firstError }}
      </p>
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';

export default {
  mixins: [HandlesValidationErrors, FormField],

  computed: {
    /**
     * Get the placeholder.
     */
    placeholder() {
      return this.field.placeholder === undefined
        ? this.__('Choose an option')
        : this.field.placeholder;
    },

    /**
     * Get the input classes.
     */
    inputClasses() {
      return this.field.inputClasses || 'w-full form-control form-select';
    }
  },

  watch: {
    value(value) {
      this.$emit('value', value);
    }
  },

  methods: {
    /**
     * Provide a function that fills a passed FormData object with the
     * field's internal value attribute. Here we are forcing there to be a
     * value sent to the server instead of the default behavior of
     * `this.value || ''` to avoid loose-comparison issues if the keys
     * are truthy or falsey
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value);
    }
  }
};
</script>
