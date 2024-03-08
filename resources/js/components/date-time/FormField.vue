<template>
  <r64-default-field
      :hide-field="hideField"
      :field="field"
      :show-help-text="showHelpText"
      :hide-label="hideLabelInForms"
      :field-classes="fieldClasses"
      :wrapper-classes="wrapperClasses"
      :label-classes="labelClasses"
  >
    <template #field>
      <div class="flex items-center">
        <DateTimePicker
            class="w-full form-control form-input form-input-bordered"
            ref="dateTimePicker"
            :dusk="field.attribute"
            :name="field.name"
            :placeholder="placeholder"
            :dateFormat="pickerFormat"  
            :alt-format="pickerDisplayFormat"
            :hour-increment="pickerHourIncrement"
            :minute-increment="pickerMinuteIncrement"
            :value="localizedValue"
            :twelve-hour-time="usesTwelveHourTime"
            :first-day-of-week="firstDayOfWeek"
            :class="errorClasses"
            @change="handleChange"
            :default-hour="defaultHour"
            :default-minute="defaultMinute"
            :enable-seconds="enableSeconds"
            :enable-time="enableTime"
            :disabled="isReadonly"
        />

        <a
            v-if="field.nullable && !isReadonly"
            @click.prevent="$refs.dateTimePicker.clear()"
            href="#"
            :title="__('Clear value')"
            tabindex="-1"
            class="p-1 px-2 cursor-pointer leading-none focus:outline-none"
            :class="{
            'text-50': !value.length,
            'text-black hover:text-danger': value.length,
          }"
        >
          <icon type="x-circle" width="22" height="22" viewBox="0 0 22 22" />
        </a>
        <span v-if="!field.hideTimezone" class="text-80 text-sm ml-2">({{ userTimezone }})</span>
      </div>

      <p
          v-if="hasError"
          class="my-2 text-red-500"
      >
        {{ firstError }}
      </p>
    </template>
  </r64-default-field>
</template>

<script>
import moment from 'moment';
import { DependentFormField, HandlesValidationErrors } from '@/mixins'
import R64Field from "../../mixins/R64Field";
import DateTimePicker from "./DateTimePicker";

export default {
  mixins: [HandlesValidationErrors, DependentFormField,R64Field],

  data: () => ({ localizedValue: ''}),

  components: { DateTimePicker },

  methods: {
    /*
     * Set the initial value for the field
     */
    setInitialValue() {
      // Set the initial value of the field
      this.value = this.field.value || '';

      // If the field has a value let's convert it from the app's timezone
      // into the user's local time to display in the field
      if (this.value !== '') {
        this.localizedValue = this.value;
      }
    },

    /**
     * On save, populate our form data
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value || '')
    },

    /**
     * Update the field's internal value when it's value changes
     */
    handleChange(value) {
     if(this.field.setDefaultMinuteZero == true && value !== ''){
       let date = new Date(value);
       if (!isNaN(date) && date instanceof Date) {
         let onlyDate = date.getFullYear()+'-'+ ('0' + (date.getMonth()+1)).slice(-2) +'-'+ ('0' + date.getDate()).slice(-2) +" "+('0' + date.getHours()).slice(-2)+":00"+":00";
         this.value = onlyDate;
         this.$refs.dateTimePicker.getUpdatedValue(onlyDate);
       } else {
         this.value = '';
       }
     }else{
       this.value = value;
     }
    },
  },

  computed: {
    firstDayOfWeek() {
      return this.field.firstDayOfWeek || 0
    },

    format() {
      return this.field.format || 'YYYY-MM-DD HH:mm:ss'
    },

    placeholder() {
      return this.field.placeholder || moment().format(this.format)
    },

    pickerFormat() {
      return this.field.pickerFormat || 'Y-m-d H:i:S'
    },

    pickerDisplayFormat() {
      return this.field.pickerDisplayFormat || 'Y-m-d H:i:S'
    },

    pickerHourIncrement() {
      return this.field.pickerHourIncrement || 1
    },

    pickerMinuteIncrement() {
      return this.field.pickerMinuteIncrement || 5
    },
    enableSeconds() {
      return this.field.enableSeconds === false ? false : true
    },
    enableTime() {
      return this.field.enableTime === false ? false : true
    },
    defaultHour() {
      return this.field.defaultHour || 12
    },
    defaultMinute() {
      return this.field.defaultMinute || 0
    },
  },

}
</script>