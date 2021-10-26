<template>
  <r64-panel-item
      :hide-field="hideField"
      :field="field"
      :hide-label="hideLabelInDetail"
      :label-classes="panelLabelClasses"
      :field-classes="panelFieldClasses"
      :wrapper-classes="panelWrapperClasses"
  >
    <div slot="value">
      <component
          :item="item"
          :key="index"
          v-bind="$props"
          :is="field.detailItemComponent"
          v-for="(item, index) in field.value"
      />
    </div>

  </r64-panel-item>
</template>

<script>
import R64Field from '../../mixins/R64Field'
export default {
  mixins: [R64Field],
  props: ['resource', 'resourceName', 'resourceId', 'field']
}
</script>
