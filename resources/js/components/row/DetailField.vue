<template>
  <r64-panel-item
    :field="field"
    :hide-label="hideLabelInDetail"
    :label-classes="panelLabelClasses"
    :field-classes="panelFieldClasses"
    :wrapper-classes="panelWrapperClasses"
  >
    <r64-excerpt
      #value
      :content="field.value"
      :excerpt-classes="excerptClasses"
      :show-label="showContentLabel"
      :hide-label="hideContentLabel"
      :should-show="field.shouldShow"
    >
      <TableWrapper slot="content" :field="field" />
    </r64-excerpt>
  </r64-panel-item>
</template>

<script>
import TableWrapper from './TableWrapper.vue'
import R64Field from '../../mixins/R64Field'

export default {
  components: { TableWrapper },

  mixins: [R64Field],

  props: ['resource', 'resourceName', 'resourceId', 'field']
}
</script>
