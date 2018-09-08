<template>
  <r64-panel-item
    :field="field"
    :hide-label="field.hideLabelInDetail"
  >
    <p slot="value" class="text-90">
      <span
        :class="[dotClasses, statusClass]"
      />
      <span v-if="!field.hideBooleanLabel">{{ label }}</span>
    </p>
  </r64-panel-item>
</template>

<script>
import Classes from './Classes';

export default {
  mixins: [Classes],

  props: ['resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    label() {
      return this.field.value == true
        ? this.field.yesLabel || this.__('Yes')
        : this.field.noLabel || this.__('No');
    }
  }
};
</script>