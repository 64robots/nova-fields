<template>
  <r64-panel-item
      :field="field"
      :hide-label="hideLabelInDetail"
      :label-classes="panelLabelClasses"
      :field-classes="panelFieldClasses"
  >
    <template #value>
      <p v-if="fieldHasValue || usesCustomizedDisplay" :title="field.value">
        {{ formattedDateTime }}
      </p>
      <p v-else>&mdash;</p>
    </template>
  </r64-panel-item>
</template>

<script>
import {DateTime} from 'luxon'
import {FieldValue} from '@/mixins'

export default {
  mixins: [FieldValue],

  props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    formatTime(){
      if (this.field.value) {
        let date = new Date(this.field.value);
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
    formattedDateTime() {
      if (this.usesCustomizedDisplay) {
        return this.field.displayedAs
      }
      if(this.field.hideTimezone){
        return DateTime.fromISO(this.field.value)
            .setZone(Nova.config('userTimezone') || Nova.config('timezone'))
            .toLocaleString({
              year: 'numeric',
              month: '2-digit',
              day: '2-digit',
            }) + ' ' + this.formatTime
      }
      return DateTime.fromISO(this.field.value)
          .setZone(Nova.config('userTimezone') || Nova.config('timezone'))
          .toLocaleString({
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            timeZoneName: 'short',
          })
    },
  },
}
</script>
