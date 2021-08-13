<template>
  <span>
    <RowHeading
      v-if="shouldShowHeading"
      :fields="field.fields"
      :base-classes="field.childConfig"
      :heading-classes="field.headingClasses"
      :use-wrapper-classes="!!field.useWrapperClassesInHeading"
    />
    <div
      v-for="row in values"
      :key="row.row_id"
      :class="field.itemWrapperClasses"
    >
      <DetailFieldItem
        v-for="f in fields"
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
  </span>
</template>
<script>
import RowHeading from './RowHeading'
import DetailFieldItem from './DetailFieldItem'
import SumField from './SumField'
import RowField from './RowField'

export default {
  name: 'TableRow',

  components: { RowHeading, DetailFieldItem, SumField },

  mixins: [RowField],

  props: ['field'],

  computed: {
    fields() {
      return this.field.fields.filter(f => !f.hideFromDetail)
    }
  }
}
</script>