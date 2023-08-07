<template>
  <r64-default-field

      :field="field"
      :hide-label="hideLabelInForms"
      :field-classes="fieldClasses"
      :wrapper-classes="wrapperClasses"
      :label-classes="labelClasses"
  >
    <div v-if="shouldDisplayAsHtml" v-html="field.value" :class="classes" />
    <div v-else :class="classes">
      <p>{{ field.value }}</p>
    </div>
  </r64-default-field>
</template>

<script>
import R64Field from '../../mixins/R64Field'
export default {
  mixins: [R64Field],
  props: {
    resourceName: {
      type: String,
      require: true,
    },
    field: {
      type: Object,
      require: true,
    },
  },

  created() {
    this.field.fill = () => {}
  },

  computed: {
    classes: () => [
      'bg-white dark:bg-gray-700',
      'remove-last-margin-bottom',
      'leading-normal',
      'w-full',
      'py-4',
      'px-8',
    ],

    shouldDisplayAsHtml() {
      return this.field.asHtml || false
    },
  },
}
</script>
