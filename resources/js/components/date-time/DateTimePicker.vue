<template>
  <input
      :disabled="disabled"
      :class="{ '!cursor-not-allowed': disabled }"
      :value="value"
      ref="datePicker"
      type="text"
      :placeholder="placeholder"
  />
</template>

<script>
import flatpickr from "flatpickr";
import 'flatpickr/dist/themes/airbnb.css';


export default {
  props: {
    value: {
      required: false,
    },
    placeholder: {
      type: String,
      default: () => {
        return moment().format('YYYY-MM-DD HH:mm:ss')
      },
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    hourIncrement: {
      type: Number,
      default: 1,
    },
    minuteIncrement: {
      type: Number,
      default: 5,
    },
    dateFormat: {
      type: String,
      default: 'Y-m-d H:i:S',
    },
    altFormat: {
      type: String,
      default: 'Y-m-d H:i:S',
    },
    twelveHourTime: {
      type: Boolean,
      default: false,
    },
    enableTime: {
      type: Boolean,
      default: true,
    },
    enableSeconds: {
      type: Boolean,
      default: true,
    },
    firstDayOfWeek: {
      type: Number,
      default: 0,
    },
  },

  data: () => ({ flatpickr: null }),

  watch: {
    value: function (newValue, oldValue) {
      if (this.flatpickr) {
        this.flatpickr.setDate(newValue)
      }
    },
  },

  mounted() {
   this.$nextTick(() => this.createFlatpickr())
  },

  methods: {
    createFlatpickr() {
      this.flatpickr = flatpickr(this.$refs.datePicker, {
        defaultHour: this.defaultHour,
        defaultMinute: this.defaultMinute,
        enableTime: this.enableTime,
        enableSeconds: this.enableSeconds,
        onOpen: this.onOpen,
        onClose: this.onClose,
        onChange: this.onChange,
        dateFormat: this.dateFormat,
        altInput: true,
        altFormat: this.altFormat,
        allowInput: true,
        // static: true,
        time_24hr: !this.twelveHourTime,
        hourIncrement: this.hourIncrement,
        minuteIncrement: this.minuteIncrement,
        locale: { firstDayOfWeek: this.firstDayOfWeek },
      })
    },

    onOpen(event) {
      Nova.$emit('datepicker-opened', event)
    },

    onClose(event) {
      this.onChange(event)
      Nova.$emit('datepicker-closed', event)
    },

    onChange(event) {
      this.$emit('change', this.$refs.datePicker.value)
    },

    getUpdatedValue(value) {
      if (this.flatpickr) {
        this.flatpickr.setDate(value);
      }
    },

    clear() {
      this.flatpickr.clear()
    },
  },

  beforeDestroy() {
    this.flatpickr.destroy()
  },
}
</script>

<style scoped>
.\!cursor-not-allowed {
  cursor: not-allowed !important;
}
</style>
