<template>
  <span
    :class="field.indexClasses"
    v-html="maskLabel"
  />
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  computed: {
    maskLabel() {
      return (
        this.field.maskLabel ||
        '&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;'
      )
    }
  }
}
</script>
