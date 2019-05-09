<template>
  <div :class="headingClasses">
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
    headingClasses: {
      type: String,
      default: 'flex text-80 pt-2'
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
