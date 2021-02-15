<template>
  <div :class="`text-${field.textAlign}`">
    <span v-if="field.value" class="whitespace-no-wrap">{{
        localizedDateTime
      }}</span>
    <span v-else class="whitespace-no-wrap">&mdash;</span>
  </div>
</template>

<script>
import { InteractsWithDates } from 'laravel-nova'

export default {
  mixins: [InteractsWithDates],

  props: ['resourceName', 'field'],

  computed: {
    /**
     * Get the localized date time.
     */
    localizedDateTime() {
      return this.localizeDateTimeField(this.field)
    },
  },
}
</script>
