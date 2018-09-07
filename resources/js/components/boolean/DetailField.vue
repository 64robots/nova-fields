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
export default {
  props: ['resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    dotClasses() {
      return this.field.dotClasses || 'inline-block rounded-full w-2 h-2 mr-1';
    },

    statusClass() {
      if (this.field.value) {
        return this.field.successClass || 'bg-success';
      }
      return this.field.dangerClass || 'bg-danger';
    },

    label() {
      return this.field.value == true
        ? this.field.yesLabel || this.__('Yes')
        : this.field.noLabel || this.__('No');
    }
  }
};
</script>