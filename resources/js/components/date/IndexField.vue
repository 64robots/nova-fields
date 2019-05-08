<template>
  <div
    v-if="field.asHtml"
    v-html="field.value"
  />
  <span
    v-else
    :class="[field.indexClasses, applyColor]"
  >
    <template v-if="!field.showLinkInIndex">{{ field.value }}</template>
    <router-link
      v-else
      :class="linkClasses"
      :to="{
        name: 'detail',
        params: {
          resourceName: resourceName,
          resourceId: field.id
        }
      }"
      :title="field.value"
    >{{ field.value }}</router-link>
  </span>
</template>

<script>
import Colors from '../../mixins/Colors'

export default {
  mixins: [Colors],

  props: ['resourceName', 'field'],

  computed: {
    linkClasses() {
      return this.field.indexLinkClasses
    }
  }
}
</script>
