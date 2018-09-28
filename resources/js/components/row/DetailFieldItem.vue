<template>
  <p :class="fieldClass">{{ display }}</p>
</template>
<script>
export default {
  props: {
    field: {
      type: Object,
      default: () => ({})
    },
    row: {
      type: Object,
      default: () => ({})
    },
    baseClasses: {
      type: String,
      default: ''
    }
  },

  computed: {
    display() {
      const display = this.row[this.field.attribute];
      if (this.field.displayUsingLabels && this.field.options) {
        const option = this.field.options.find(
          opt => Number(opt.value) === Number(display)
        );
        if (!option) {
          return '';
        }

        return option.label;
      }

      return display;
    },

    fieldClass() {
      return this.field.fieldClasses
        ? this.field.fieldClasses
        : this.baseClasses;
    }
  }
};
</script>
