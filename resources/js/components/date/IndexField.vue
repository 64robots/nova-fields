<template>
  <span
    v-if="field.value"
    :class="field.indexClasses"
  >{{ formattedDate }}</span>
  <span v-else>&mdash;</span>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  computed: {
    formattedDate() {
      return this.field.format
        ? moment(this.field.value).format(this.field.format)
        : this.field.value
    }
  }
}
</script>
