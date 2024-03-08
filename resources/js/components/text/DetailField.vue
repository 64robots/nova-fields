<template>
  <panel-item

    :field="field"
    :hide-label="hideLabelInDetail"
    :label-classes="panelLabelClasses"
    :field-classes="panelFieldClasses"
    :wrapper-classes="panelWrapperClasses"
  />
</template>

<script>
import R64Field from '../../mixins/R64Field'
import PanelItem from '../PanelItem.vue'
export default {
  mixins: [R64Field],

  props: ['resource', 'resourceName', 'resourceId', 'field']
}
</script>
