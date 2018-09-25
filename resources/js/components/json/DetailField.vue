<template>
  <r64-panel-item
    :field="field"
    :hide-label="field.hideLabelInDetail"
    :label-classes="field.panelLabelClasses"
    :field-classes="field.panelFieldClasses"
  >
    <div slot="value">
      <component
        :class="{'remove-bottom-border': index == fields.length - 1}"
        :key="index"
        :ref="f.attribute"
        v-for="(f, index) in fields"
        :is="`detail-${f.component}`"
        :field="f"
        :base-classes="f.childConfig"
      />
    </div>
  </r64-panel-item>
</template>

<script>
import HasChildFields from '../../mixins/HasChildFields';

export default {
  mixins: [HasChildFields],

  props: ['resource', 'resourceName', 'resourceId', 'field']
};
</script>
