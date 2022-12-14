<template>
  <r64-panel-item

      :field="field"
      :hide-label="hideLabelInDetail"
      :label-classes="panelLabelClasses"
      :field-classes="panelFieldClasses"
  >
    <template #value>
      <p v-if="field.value" class="text-90">{{ localizedDateTime }}</p>
      <p v-else>&mdash;</p>
    </template>
  </r64-panel-item>
</template>

<script>
// import { InteractsWithDates } from '../../../../../../laravel/nova/resources/js/mixins';

export default {
  mixins: [],

  props: ['resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    /**
     * Get the localized date time.
     */
    localizedDateTime() {
      return this.localizeDateTimeField(this.field)
    },
  },
}
</script>
