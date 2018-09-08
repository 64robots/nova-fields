<template>
  <span
    class="font-bold"
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
      );
    }
  }
};
</script>
