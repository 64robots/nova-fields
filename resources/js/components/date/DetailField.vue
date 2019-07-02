<template>
  <r64-panel-item
    :hide-field="hideField"
    :field="field"
    :hide-label="hideLabelInDetail"
    :label-classes="panelLabelClasses"
    :field-classes="panelFieldClasses"
  >
    <template slot="value">
      <p
        v-if="field.value"
        class="text-90"
      >{{ formattedDate }}</p>
      <p v-else>&mdash;</p>
    </template>
  </r64-panel-item>
</template>

<script>
import R64Field from '../../mixins/R64Field'

export default {
  mixins: [R64Field],

  props: ['resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    formattedDate() {
      return this.field.format
        ? moment(this.field.value).format(this.field.format)
        : this.field.value
    }
  }
}
</script>
