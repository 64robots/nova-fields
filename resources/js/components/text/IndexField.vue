<template>
  <div
    v-if="field.asHtml"
    v-html="field.value"
  />
  <span
    v-else
    class="whitespace-no-wrap"
    :class="applyColor"
  >{{ field.value }}</span>
</template>

<script>
import Colors from '../../mixins/Colors';

export default {
  mixins: [Colors],

  props: ['resourceName', 'field']
};
</script>
