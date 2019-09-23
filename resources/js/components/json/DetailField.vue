<template>
  <div>
    <r64-panel-item
      v-if="! field.flatten"
      :field="field"
      :hide-label="field.hideLabelInDetail"
      :label-classes="field.panelLabelClasses"
      :field-classes="field.panelFieldClasses"
      :wrapper-classes="field.panelWrapperClasses"
    >
      <div slot="value">
        <DetailFieldPanel
          v-for="panel in panels"
          :key="panel.name"
          :panel="panel"
          :panel-title-class="field.panelTitleClasses"
        />
      </div>
    </r64-panel-item>
    <template v-else>
      <DetailFieldPanel
        v-for="panel in panels"
        :key="panel.name"
        :panel="panel"
        :panel-title-class="field.panelTitleClasses"
      />
    </template>
  </div>
</template>

<script>
import DetailFieldPanel from './DetailFieldPanel'
import HasChildFields from '../../mixins/HasChildFields';

export default {
  components: { DetailFieldPanel },

  mixins: [HasChildFields],

  props: ['resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    panels() {
      const panels = []
      this.fields.forEach(field => {
        const panel = panels.find(p => p.name === field.panel)

        if (panel) {
          panel.fields.push(field)
        } else {
          panels.push({
            name: field.panel,
            fields: [field]
          })
        }
      })

      return panels
    }
  }
};
</script>
