<template>
  <r64-panel-item
    :field="field"
    :hide-label="hideLabelInDetail"
    :label-classes="panelLabelClasses"
    :field-classes="panelFieldClasses"
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
          v-if="!field.hideHeading"
          :fields="field.fields"
          :base-classes="field.childConfig"
        />
        <div
          v-for="row in values"
          :key="row.row_id"
          class="flex border-40 border"
        >
          <DetailFieldItem
            v-for="f in field.fields"
            :key="`${row.row_id}${f.attribute}`"
            :base-classes="field.fieldClasses"
            :field="f"
            :row="row"
          />
        </div>
      </template>
    </r64-excerpt>
  </r64-panel-item>
</template>

<script>
import R64Field from '../../mixins/R64Field';
import RowHeading from './RowHeading';
import RowField from './RowField';
import DetailFieldItem from './DetailFieldItem';

export default {
  mixins: [RowField, R64Field],

  components: { RowHeading, DetailFieldItem },

  props: ['resource', 'resourceName', 'resourceId', 'field']
};
</script>
