<template>
  <div :class="itemClasses">
    <div
      v-if="!hideLabel"
      :class="labelClasses"
    >
      <slot>
        <h4 class="font-normal text-80">
          {{ label }}
        </h4>
      </slot>
    </div>
    <div :class="fieldClasses">
      <slot name="value">
        <p
          v-if="field.value && !field.asHtml"
          class="text-90"
          :class="applyColor"
        >{{ field.value }}</p>
        <div v-else-if="field.value && field.asHtml" v-html="field.value"></div>
        <p v-else>&mdash;</p>
      </slot>
    </div>
  </div>
</template>

<script>
import Colors from '../mixins/Colors'

export default {
  mixins: [Colors],

  props: {
    field: {
      type: Object,
      required: true
    },

    fieldName: {
      type: String,
      default: ''
    },

    labelClasses: {
      type: String,
      default: 'w-1/4 py-4'
    },

    fieldClasses: {
      type: String,
      default: 'w-3/4 py-4'
    },

    wrapperClasses: {
      type: String,
      default: 'flex border-b border-40'
    },

    hideLabel: {
      type: Boolean,
      default: false
    },

    hideField: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    label() {
      return this.fieldName || this.field.name
    },

    itemClasses() {
      return this.hideField ? 'hidden' : this.wrapperClasses
    }
  }
}
</script>
