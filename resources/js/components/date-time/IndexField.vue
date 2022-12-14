<template>
  <div :class="`text-${field.textAlign}`">
    <div v-if="field.value">
      <span class='mb-1'>{{ formatDate }} <br/></span>
      <span class=''>{{ formatTime}} </span>
    </div>
    <span v-else class="whitespace-no-wrap">&mdash;</span>
  </div>
</template>

<script>
// import { InteractsWithDates } from '../../../../../../laravel/nova/resources/js/mixins';

export default {
  mixins: [],

  props: ['resourceName', 'field'],

  computed: {
    /**
     * Get the localized date time.
     */
    localizedDateTime() {
      return this.localizeDateTimeField(this.field);
    },

    formatDate(){
      if (this.field.value) {
        return moment(this.localizedDateTime).format('MM/DD/YYYY')
      }
    },
    formatTime(){
      if (this.field.value) {
        return moment(this.localizedDateTime).format('hh:mm A')
      }
    }
  },
}
</script>
