<template>
  <span v-if="field.value" :class="field.indexClasses">
      {{ formattedDate }}
  </span>
  <span v-else>&mdash;</span>
</template>

<script>
import { DateTime } from 'luxon'
import { FieldValue } from '@/mixins'
export default {
  mixins: [FieldValue],

  props: ['resourceName', 'field'],

  computed: {
    timezone() {
      return Nova.config('userTimezone') || Nova.config('timezone')
    },
    formattedDate() {
      let date = new Date(this.field.value);
      // return ('0' + date.getDate()).slice(-2) +'/'+ ('0' + (date.getMonth()+1)).slice(-2) +'/'+date.getFullYear();
      return ('0' + (date.getMonth()+1)).slice(-2) +'/' + ('0' + date.getDate()).slice(-2) +'/'+ date.getFullYear();
    }
  },
}
</script>
