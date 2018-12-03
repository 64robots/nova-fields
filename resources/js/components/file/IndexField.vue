<template>
  <p>
    <img
      v-if="field.thumbnailUrl"
      :src="field.thumbnailUrl"
      style="object-fit: cover;"
      :class="field.indexClasses"
    >
    <span v-else>&mdash;</span>
  </p>
</template>

<script>
export default {
  props: ['viaResource', 'viaResourceId', 'resourceName', 'field']
}
</script>
