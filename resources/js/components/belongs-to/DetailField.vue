<template>
  <r64-panel-item
    :hide-field="hideField"
    :field="field"
    :hide-label="hideLabelInDetail"
    :label-classes="panelLabelClasses"
    :field-classes="panelFieldClasses"
  >
    <template slot="value">
      <router-link
        v-if="field.value"
        :to="{
          name: 'detail',
          params: {
            resourceName: field.resourceName,
            resourceId: field.belongsToId
          }
        }"
        class="no-underline font-bold dim text-primary"
      >
        {{ field.value }}
      </router-link>
      <p v-else>&mdash;</p>
    </template>
  </r64-panel-item>
</template>

<script>
import R64Field from '../../mixins/R64Field'

export default {
  mixins: [R64Field],

  props: ['resource', 'resourceName', 'resourceId', 'field']
}
</script>
