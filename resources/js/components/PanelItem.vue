<template>
  <div class="flex border-b border-40">
    <div
      v-if="!hideLabel"
      class="w-1/4 py-4"
    >
      <slot>
        <h4 class="font-normal text-80">
          {{ label }}
        </h4>
      </slot>
    </div>
    <div class="w-3/4 py-4">
      <slot name="value">
        <p v-if="field.value" class="text-90">{{ field.value }}</p>
        <p v-else>&mdash;</p>
      </slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    field: {
      type: Object,
      required: true
    },
    fieldName: {
      type: String,
      default: ''
    },
    hideLabel: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    label() {
      return this.fieldName || this.field.name;
    }
  }
};
</script>
