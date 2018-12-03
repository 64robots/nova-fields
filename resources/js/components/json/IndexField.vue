<template>
  <div :class="field.indexClasses">
    <div
      v-for="(value, index) in values"
      :key="index"
      class="mr-1"
    >
      <span class="font-bold">{{ index }}:</span>
      <span>{{ value }}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  data() {
    return {
      values: []
    }
  },

  created() {
    const values = this.field.value || '{}'
    try {
      this.values = JSON.parse(values)
    } catch (e) {
      this.values = values
    }
  }
}
</script>
