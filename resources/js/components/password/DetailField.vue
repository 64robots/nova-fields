<template>
  <r64-panel-item
    :hide-field="hideField"
    :field="field"
    :hide-label="hideLabelInDetail"
    :label-classes="panelLabelClasses"
    :field-classes="panelFieldClasses"
  >
  <p
    slot="value"
    class="text-90"
    v-html="maskLabel"
  />
  </r64-panel-item>
</template>

<script>
import R64Field from '../../mixins/R64Field'

export default {
  mixins: [R64Field],

  props: ['resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    maskLabel() {
      return (
        this.field.maskLabel ||
        '&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;'
      )
    }
  }
}
</script>
