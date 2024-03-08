<template>
  <div :class="`text-${field.textAlign}`">
    <div v-if="field.value">
      <span class='mb-1 whitespace-nowrap' :title="field.value">{{ formatDate }} <br/></span>
      <span class=''>{{ formatTime}} </span>
    </div>
    <span v-else class="whitespace-no-wrap">&mdash;</span>
  </div>
</template>

<script>
import {FieldValue} from '@/mixins'

export default {
  mixins: [FieldValue],

  props: ['resourceName', 'field'],

  computed: {
    timezone() {
      return Nova.config('userTimezone') || Nova.config('timezone')
    },
    date(){
      return this.field.value;
    },
    formatDate(){
      if (this.field.value) {
        let date = new Date(this.date);
        date = new Date(date.toLocaleString('en-US', {
          timeZone: 'America/Los_Angeles',
        }));
        return ('0' + date.getDate()).slice(-2) +'/'+ ('0' + (date.getMonth()+1)).slice(-2) +'/'+ date.getFullYear();
      }
    },
    formatTime(){
      if (this.field.value) {
        let date = new Date(this.date);
        date = new Date(date.toLocaleString('en-US', {
          timeZone: 'America/Los_Angeles',
        }));
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        return hours + ':' + minutes + ' ' + ampm;
      }
    },
  }
}
</script>
