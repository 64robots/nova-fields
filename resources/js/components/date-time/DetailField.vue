<template>
  <r64-panel-item
      :hide-field="hideField"
      :field="field"
      :hide-label="hideLabelInDetail"
      :label-classes="panelLabelClasses"
      :field-classes="panelFieldClasses"
  >
    <template slot="value">
      <p v-if="field.value" class="text-90">{{ localizedDateTime }}</p>
      <p v-else>&mdash;</p>
    </template>
  </r64-panel-item>
</template>

<script>
import { InteractsWithDates } from 'laravel-nova'

export default {
  mixins: [InteractsWithDates],

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
