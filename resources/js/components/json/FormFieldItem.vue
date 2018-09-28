<template>
  <component
    v-show="shouldBeShown"
    :is="`form-${field.component}`"
    :validationErrors="validationErrors"
    :resource-name="resourceName"
    :resource-id="resourceId"
    :field="field"
    :base-classes="baseClasses"
  />
</template>
<script>
export default {
  props: [
    'field',
    'validationErrors',
    'resourceName',
    'resourceId',
    'baseClasses',
    'currentValue'
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
    }
  }
};
</script>
