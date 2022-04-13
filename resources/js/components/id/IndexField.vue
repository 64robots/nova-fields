<template>
  <div :class="`text-${field.textAlign}`">
    <router-link
        v-if="hasValue"
        :to="{
        name: 'detail',
        params: {
          resourceName: resourceName,
          resourceId: field.value,
        },
      }"
        class="no-underline dim text-primary font-bold"
    >
      {{ field.pivotValue || field.value }}
    </router-link>
    <p v-else-if="hasValue">
      {{ field.pivotValue || field.value }}
    </p>
    <p v-else>&mdash;</p>
  </div>
</template>

<script>
export default {
  props: ['resource', 'resourceName', 'field'],

  computed: {
    /**
     * Determine if the field has a value other than null.
     */
    hasValue() {
      return this.field.value !== null
    },
  }
}
</script>
