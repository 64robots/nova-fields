<template>
  <component
    v-show="shouldBeShown"
    :is="`form-${field.component}`"
    :errors="itemErrors"
    :resource-name="resourceName"
    :resource-id="resourceId"
    :field="field"
    :base-classes="baseClasses"
  />
</template>
<script>
import { HandlesValidationErrors } from 'laravel-nova'
import { Errors } from 'form-backend-validation'

export default {
  mixins: [HandlesValidationErrors],

  props: [
    'field',
    'resourceName',
    'resourceId',
    'baseClasses',
    'currentValue',
    'parent',
  ],

  computed: {
    shouldBeShown() {
      const { onlyWhen } = this.field;
      if (!onlyWhen) {
        return true;
      }

      const keys = Object.keys(onlyWhen);
      if (!keys.length) {
        return true;
      }

      const lookThisField = keys[0];
      const validValues = onlyWhen[lookThisField];
      const values = JSON.parse(this.currentValue || '{}');

      return validValues.includes(values[lookThisField]);
    },

    fieldAttribute() {
      return `${this.parent.attribute}.${this.field.attribute}`;
    },

    itemErrors() {
      const errors = new Errors();
      if (this.errors.has(this.fieldAttribute)) {
        errors.record({
          [this.field.attribute]: this.errors.get(this.fieldAttribute),
        })
      }

      return errors;
    },
  }
};
</script>
