<template>
  <div :class="headingClasses">
    <div
      v-for="(field, index) in fields"
      v-if="shouldShowField(field)"
      :key="index"
      :class="getFieldClasses(field)"
    >{{ field.name }}</div>
    <span
      class="spacer"
      :class="spacerClasses"
    >x</span>
  </div>
</template>
<script>
import R64Field from '../../mixins/R64Field'

export default {
  mixins: [R64Field],

  props: {
    fields: {
      type: Array,
      default: () => []
    },

    headingClasses: {
      type: String,
      default: 'flex text-80 pt-2'
    },

    spacerClasses: {
      type: String,
      default: ''
    },

    useWrapperClasses: {
      type: Boolean,
      default: false
    },

    baseClasses: {
      type: Object,
      default: () => ({})
    }
  },

  methods: {
    getFieldClasses(field) {
      const classes = this.useWrapperClasses ? 'wrapperClasses' : 'fieldClasses'
      return this.baseClasses[classes] || field[classes]
    }
  }
}
</script>
<style scoped>
.spacer {
  opacity: 0 !important;
  cursor: default !important;
}
</style>
