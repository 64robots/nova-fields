<template>
  <div :class="defaultClasses">
    <div
      v-if="!hideLabel"
      :class="labelClasses"
    >
      <slot>
        <form-label :for="field.name">
          {{ field.name || fieldName }}
          <span v-if="field.required" class="text-danger text-sm">{{
            __('*')
          }}</span>
        </form-label>
      </slot>
    </div>
    <div :class="fieldClasses">
      <slot name="field"/>
      <help-text :show-help-text="showHelpText">{{ field.helpText }}</help-text>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    wrapperClasses: {
      type: String,
      default: 'flex border-b border-40'
    },
    labelClasses: {
      type: String,
      default: 'w-1/5 px-8 py-6'
    },
    fieldClasses: {
      type: String,
      default: 'w-1/2 px-8 py-6'
    },
    hideLabel: {
      type: Boolean,
      default: false
    },
    field: {
      type: Object,
      required: true
    },
    fieldName: {
      type: String,
      default: ''
    },
    showHelpText: {
      type: Boolean,
      default: true
    },
    hideField: {
      type: Boolean,
      default: false
    }
  },

  computed: {
    defaultClasses() {
      if (this.hideField) {
        return 'hidden'
      }
      return this.wrapperClasses
    }
  }
}
</script>
