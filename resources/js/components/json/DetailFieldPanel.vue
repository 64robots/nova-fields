<template>
  <div>
    <h4
      v-if="panel.name"
      :class="panelTitleClass"
    >{{ panel.name }}</h4>
    <component
      v-for="(field, index) in panel.fields"
      :key="index"
      :ref="field.attribute"
      :class="{'remove-bottom-border': index === panel.fields.length - 1}"
      :is="`detail-${field.component}`"
      :field="field"
      :base-classes="field.childConfig"
    />
  </div>
</template>

<script>
  export default {
    props: {
      panel: {
        type: Object,
        default: () => {}
      },

      panelTitleClass: {
        type: Object,
        default: () => {}
      },
    },
  }
</script>
