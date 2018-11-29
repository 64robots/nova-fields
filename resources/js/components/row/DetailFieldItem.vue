<template>
  <BooleanDetailField
    v-if="isBoolean"
    :field="field"
  />
  <p
    v-else
    :class="fieldClass"
  >{{ display }}</p>
</template>
<script>
import BooleanDetailField from '../boolean/DetailField'

export default {
  components: { BooleanDetailField },

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
    isBoolean() {
      return (
        this.field.component === 'boolean-field' ||
        this.field.component === 'nova-fields-boolean'
      )
    },

    display() {
      const display = this.row[this.field.attribute]
      if (this.field.displayUsingLabels && this.field.options) {
        const option = this.field.options.find(
          opt => Number(opt.value) === Number(display)
        )
        if (!option) {
          return ''
        }

        return option.label
      }

      return display
    },

    fieldClass() {
      return this.field.fieldClasses
        ? this.field.fieldClasses
        : this.baseClasses.fieldClasses
    }
  }
}
</script>
