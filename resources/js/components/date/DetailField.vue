<template>
  <r64-panel-item
      :field="field"
      :hide-label="hideLabelInDetail"
      :label-classes="panelLabelClasses"
      :field-classes="panelFieldClasses"
  >
    <template #value>
      <p>
        <span v-if="fieldHasValue">
          {{ formattedDate }}
        </span>
        <span v-else>&mdash;</span>
      </p>
    </template>
  </r64-panel-item>
</template>

<script>
import R64Field from '../../mixins/R64Field'
import { DateTime } from 'luxon'
import { FieldValue } from '@/mixins'

export default {
  mixins: [FieldValue,R64Field],

  props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    formattedDate() {
      if (this.field.usesCustomizedDisplay) {
        return this.field.displayedAs
      }

      let date = new Date(this.field.value);
      return ('0' + (date.getMonth()+1)).slice(-2) +'/'+ ('0' + date.getDate()).slice(-2) +'/'+date.getFullYear();
    },
  }
}
</script>
