<template>
  <r64-panel-item
    :field="field"
    :hide-label="field.hideLabelInDetail"
  />
</template>

<script>
export default {
  props: ['resource', 'resourceName', 'resourceId', 'field']
};
</script>
