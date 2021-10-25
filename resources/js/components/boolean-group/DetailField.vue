<template>
  <r64-panel-item
      :field="field"
      :hide-field="hideField"
      :hide-label="hideLabelInDetail"
      :label-classes="panelLabelClasses"
      :field-classes="panelFieldClasses"
      :wrapper-classes="panelWrapperClasses"
  >
    <template slot="value">
      <ul class="list-reset" v-if="value.length > 0">
        <li v-for="option in value" class="mb-1">
          <span
            :class="classes[option.checked]"
            class="
              inline-flex
              items-center
              py-1
              pl-2
              pr-3
              rounded-full
              font-bold
              text-sm
              leading-tight
            "
          >
            <boolean-icon :value="option.checked" width="20" height="20" />
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
      true: 'bg-success-light text-success-dark',
      false: 'bg-danger-light text-danger-dark',
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
