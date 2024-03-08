<template>
  <r64-panel-item
      :field="field"
      :hide-label="hideLabelInDetail"
      :label-classes="panelLabelClasses"
      :field-classes="panelFieldClasses"
      :wrapper-classes="panelWrapperClasses"
  >
    <template #value>
      <ul class="list-reset" v-if="value.length > 0">
        <li v-for="option in value" class="mb-1">
          <span
              :class="classes[option.checked]"
              class="
              inline-flex
              items-center
              p-1
              rounded-full
              font-bold
              text-sm
              leading-tight
            "
          >
            <IconBoolean class="flex-none" :value="option.checked" width="20" height="20" />
            <span class="ml-1">{{ option.label }}</span>
          </span>
        </li>
      </ul>
      <span v-else>{{ this.field.noValueText }}</span>
    </template>
  </r64-panel-item>
</template>

<script>
import R64Field from '../../mixins/R64Field'
export default {
  mixins: [R64Field],
  props: ['resource', 'resourceName', 'resourceId', 'field'],

  data: () => ({
    value: [],
    classes: {
      true: 'bg-green-100 text-green-500',
      false: 'bg-red-100 text-red-500',
    },
  }),

  created() {
    this.field.value = this.field.value || {}
    this.value = _(this.field.options)
        .map(o => {
          return {
            name: o.name,
            label: o.label,
            checked: this.field.value[o.name] || false,
          }
        })
        .filter(o => {
          if (this.field.hideFalseValues === true && o.checked === false) {
            return false
          } else if (this.field.hideTrueValues === true && o.checked === true) {
            return false
          }

          return true
        })
        .value()
  },
}
</script>
