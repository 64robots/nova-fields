<template>
  <div class="bg-20 flex border-b border-t border-40 -mx-6 -my-px px-2">
    <div class="w-full py-4 px-4">
      <slot name="value">
        <p v-if="fieldValue && !shouldDisplayAsHtml" class="text-90">
          {{ fieldValue }}
        </p>
        <div
          v-else-if="fieldValue && shouldDisplayAsHtml"
          v-html="field.value"
        ></div>
        <p v-else>&mdash;</p>
      </slot>
    </div>
  </div>
</template>

<script>
export default {
  props: ['resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    fieldValue() {
      if (
        this.field.value === '' ||
        this.field.value === null ||
        this.field.value === undefined
      ) {
        return false
      }

      return String(this.field.value)
    },

    shouldDisplayAsHtml() {
      return this.field.asHtml
    },
  },
}
</script>
