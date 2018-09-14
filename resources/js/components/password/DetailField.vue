<template>
  <r64-panel-item
    :field="field"
    :hide-label="field.hideLabelInDetail"
    :label-classes="field.panelLabelClasses"
    :field-classes="field.panelFieldClasses"
  >
  <p
    slot="value"
    class="text-90"
    v-html="maskLabel"
  />
  </r64-panel-item>
</template>

<script>
export default {
  props: ['resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    maskLabel() {
      return (
        this.field.maskLabel ||
        '&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;'
      );
    }
  }
};
</script>
