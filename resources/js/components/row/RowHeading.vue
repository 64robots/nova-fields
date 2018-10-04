<template>
  <div class="flex border-40 border">
    <div
      v-for="(field, index) in fields"
      :key="index"
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
      return this.baseClasses.fieldClasses || field.fieldClasses;
    }
  }
};
</script>
