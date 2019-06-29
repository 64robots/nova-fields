<template>
  <r64-panel-item
    :field="field"
    :hide-label="hideLabelInDetail"
    :label-classes="panelLabelClasses"
    :field-classes="panelFieldClasses"
    :wrapper-classes="panelWrapperClasses"
  >
    <r64-excerpt
      slot="value"
      :content="field.value"
      :excerpt-classes="excerptClasses"
      :show-label="showContentLabel"
      :hide-label="hideContentLabel"
      :should-show="field.shouldShow"
    >
      <template slot="content">
        <RowHeading
          v-if="shouldShowHeading"
          :fields="field.fields"
          :base-classes="field.childConfig"
          :heading-classes="field.headingClasses"
        />
        <div
          v-for="row in values"
          :key="row.row_id"
          :class="field.itemWrapperClasses"
        >
          <DetailFieldItem
            v-for="f in field.fields"
            v-if="!f.hideFromDetail"
            v-bind="{ row, resource, resourceId, resourceName }"
            :key="`${row.row_id}${f.attribute}`"
            :base-classes="field.fieldClasses"
            :field="f"
            :parent-attribute="field.attribute"
          />
        </div>
        <SumField
          v-if="shouldShowSum"
          :values="values"
          :field="field"
          :fields="field.fields"
        />
      </template>
    </r64-excerpt>
  </r64-panel-item>
</template>

<script>
import R64Field from '../../mixins/R64Field'
import RowHeading from './RowHeading'
import RowField from './RowField'
import DetailFieldItem from './DetailFieldItem'
import SumField from './SumField'

export default {
  mixins: [RowField, R64Field],

  components: { RowHeading, DetailFieldItem, SumField },

  props: ['resource', 'resourceName', 'resourceId', 'field']
}
</script>
