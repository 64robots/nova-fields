<template>
  <div :class="field.sumWrapperClasses">
    <div :class="field.sumFieldClasses">
      <span>{{ sumField.name }}:</span>
      <span>{{ sumValue }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SumField',

  props: ['field', 'fields', 'values'],

  computed: {
    sumField() {
      return this.fields.find(field => field.attribute === this.field.sum)
    },

    sumValue() {
      return this.values
        .map(value => Number(value[this.field.sum]))
        .reduce((prev, cur) => prev + cur, 0)
    }
  }
}
</script>
