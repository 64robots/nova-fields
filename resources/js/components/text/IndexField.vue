<template>
  <div v-if="field.asHtml" v-html="field.value"></div>
  <span v-else class="whitespace-no-wrap">{{ field.value }}</span>
</template>

<script>
export default {
  props: ['resourceName', 'field']
};
</script>
