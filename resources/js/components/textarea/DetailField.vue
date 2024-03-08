<template>
  <panel-item
      :field="field"
      :hide-label="hideLabelInDetail"
      :label-classes="panelLabelClasses"
      :field-classes="panelFieldClasses"
      :wrapper-classes="panelWrapperClasses"
  >
    <template #value>
      <excerpt
          :content="field.value"
          :excerpt-classes="excerptClasses"
          :show-label="showContentLabel"
          :hide-label="hideContentLabel"
          :should-show="field.shouldShow"
      />
    </template>
  </panel-item>
</template>

<script>
import R64Field from '../../mixins/R64Field'
import PanelItem from '../PanelItem.vue'
import Excerpt from '../Excerpt.vue'

export default {
  mixins: [R64Field],

  props: ['resource', 'resourceName', 'resourceId', 'field']
}
</script>
