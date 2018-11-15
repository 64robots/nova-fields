<template>
  <div v-if="shouldShow && hasContent">
    <slot name="content">
      <div class="markdown leading-normal" v-html="content" />
    </slot>
  </div>
  <div v-else-if="hasContent">
    <slot
      v-if="expanded"
      name="content"
    >
      <div class="markdown" v-html="content" />
    </slot>

    <a
      v-if="!shouldShow"
      @click="toggle"
      :class="[{ 'mt-6': expanded }, excerptClasses]"
      aria-role="button"
    >
      {{ showHideLabel }}
    </a>
  </div>
  <div v-else>
    &mdash;
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
    },
    shouldShow: {
      type: Boolean,
      default: false
    }
  },

  data: () => ({ expanded: false }),

  methods: {
    toggle() {
      this.expanded = !this.expanded
    }
  },

  computed: {
    hasContent() {
      return this.content !== '' && this.content !== null
    },

    showHideLabel() {
      return !this.expanded
        ? this.showLabel || this.__('Show Content')
        : this.hideLabel || this.__('Hide Content')
    }
  }
}
</script>
