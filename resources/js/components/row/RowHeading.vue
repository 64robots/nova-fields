<template>
  <div class="flex">
    <div
      v-for="(field, index) in fields"
      :key="index"
      class="border-40 border"
      :class="getFieldClasses(field)"
    >{{ field.name }}</div>
  </div>
</template>
<script>
import R64Field from '../../mixins/R64Field';

export default {
  mixins: [R64Field],

  props: {
    fields: {
      type: Array,
      default: () => []
    },
    baseClasses: {
      type: Object,
      default: () => ({})
    }
  },

  methods: {
    getFieldClasses(field) {
      return field.fieldClasses || this.baseClasses.fieldClasses;
    }
  }
};
</script>
