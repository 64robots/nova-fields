<template>
  <r64-panel-item
    :field="field"
    :hide-label="field.hideLabelInDetail"
  >
    <excerpt
      slot="value"
      :content="field.value"
      :excerpt-classes="field.excerptClasses"
      :show-label="field.showContentLabel"
      :hide-label="field.hideContentLabel"
    />
  </r64-panel-item>
</template>

<script>
import Excerpt from '../Excerpt';

export default {
  props: ['resource', 'resourceName', 'resourceId', 'field'],

  components: { Excerpt }
};
</script>
