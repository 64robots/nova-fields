<template>
  <div>
    <slot
      v-if="expanded"
      name="content"
    >
      <div class="markdown" v-html="content" />
    </slot>

    <a
      @click="toggle"
      :class="[{ 'mt-6': expanded }, excerptClasses]"
      aria-role="button"
    >
      {{ showHideLabel }}
    </a>
  </div>
</template>

<script>
export default {
  props: {
    content: {
      type: String,
      default: ''
    },
    showLabel: {
      type: String,
      default: ''
    },
    hideLabel: {
      type: String,
      default: ''
    },
    excerptClasses: {
      type: String,
      default: 'cursor-pointer dim inline-block text-primary font-bold'
    }
  },

  data: () => ({ expanded: false }),

  methods: {
    toggle() {
      this.expanded = !this.expanded;
    }
  },

  computed: {
    showHideLabel() {
      return !this.expanded
        ? this.showLabel || this.__('Show Content')
        : this.hideLabel || this.__('Hide Content');
    }
  }
};
</script>
