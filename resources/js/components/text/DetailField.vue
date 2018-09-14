<template>
  <r64-panel-item
    :field="field"
    :hide-label="field.hideLabelInDetail"
    :label-classes="field.panelLabelClasses"
    :field-classes="field.panelFieldClasses"
  />
</template>

<script>
export default {
  props: ['resource', 'resourceName', 'resourceId', 'field']
};
</script>
