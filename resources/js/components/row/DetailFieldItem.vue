<template>
  <div
    v-if="isBoolean"
    class="text-center"
    :class="fieldClass"
  >
    <span
      class="inline-block rounded-full w-2 h-2 mr-1"
      :class="statusClass"
    />
  </div>
  <detail-nova-fields-file
    v-else-if="isFile"
    :value="display"
    v-bind="{ ...$props }"
  />
  <p
    v-else
    :class="fieldClass"
  >{{ display }}</p>
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
    },

    resource: {
      type: Object,
      default: () => ({})
    },

    resourceId: {
      type: [String, Number],
      default: null
    },

    resourceName: {
      type: [String, Number],
      default: null
    },

    parentAttribute: {
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

    isFile() {
      return (
        this.field.component === 'file-field' ||
        this.field.component === 'nova-fields-file'
      )
    },

    statusClass() {
      return Boolean(this.row[this.field.attribute])
        ? 'bg-success'
        : 'bg-danger'
    },

    display() {
      const display = this.row[this.field.attribute]

      console.log(this.field.component)
      if (this.field.component === 'nova-fields-belongs-to') {
        return display[this.field.displayName]
      }

      if (this.field.component === 'nova-fields-date' && this.field.format) {
        return moment(display).format(this.field.format)
      }

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
