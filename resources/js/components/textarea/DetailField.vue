<template>
  <r64-panel-item
    :hide-field="hideField"
    :field="field"
    :hide-label="hideLabelInDetail"
    :label-classes="panelLabelClasses"
    :field-classes="panelFieldClasses"
  >
    <r64-excerpt
      slot="value"
      :content="field.value"
      :excerpt-classes="excerptClasses"
      :show-label="showContentLabel"
      :hide-label="hideContentLabel"
      :should-show="field.shouldShow"
    />
  </r64-panel-item>
</template>

<script>
import R64Field from '../../mixins/R64Field'

export default {
  mixins: [R64Field],

  props: ['resource', 'resourceName', 'resourceId', 'field']
}
</script>
