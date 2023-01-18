<template>
  <r64-default-field
      :field="field"
      :show-help-text="showHelpText"
      :hide-label="hideLabelInForms"
      :field-classes="fieldClasses"
      :wrapper-classes="wrapperClasses"
      :label-classes="labelClasses"
  >
    <template #field>
      <div class="flex items-center">
        <input
            type="datetime-local"
            class="form-control form-input form-input-bordered"
            ref="dateTimePicker"
            :id="currentField.uniqueKey"
            :dusk="field.attribute"
            :name="field.name"
            :value="formattedDate"
            :class="currentField.hideTimezone ? 'w-full' : ''"
            :disabled="currentlyIsReadonly"
            @change="handleChange"
            :min="currentField.min"
            :max="currentField.max"
            :step="currentField.step"
        />
        <a
            v-if="field.nullable && !isReadonly"
            @click.prevent="formattedDate = value = ''"
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
        <span class="ml-3" v-if="!currentField.hideTimezone">
          {{ timezone }}
        </span>
      </div>

      <p
          v-if="hasError"
          class="my-2 text-danger"
      >
        {{ firstError }}
      </p>
    </template>
  </r64-default-field>
</template>

<script>
import isNil from 'lodash/isNil'
import { DateTime } from 'luxon'
import { DependentFormField, HandlesValidationErrors } from '@/mixins'
import filled from '@/util/filled'
import R64Field from "../../mixins/R64Field";

export default {
  mixins: [HandlesValidationErrors, DependentFormField,R64Field],

  data: () => ({
    formattedDate: '',
  }),

  methods: {
    /*
    * Set the initial value for the field
    */
    setInitialValue() {
      if (!isNil(this.currentField.value)) {
        let isoDate = DateTime.fromISO(this.currentField.value || this.value, {
          zone: Nova.config('timezone'),
        })

        this.value = isoDate.toString()

        isoDate = isoDate.setZone(this.timezone)
        this.formattedDate = [
          isoDate.toISODate(),
          isoDate.toFormat(this.timeFormat),
        ].join('T')
      }
    },

    /**
     * On save, populate our form data
     */
    fill(formData) {
      this.fillIfVisible(formData, this.field.attribute, this.value || '')

      if (this.currentlyIsVisible && filled(this.value)) {
        let isoDate = DateTime.fromISO(this.value, { zone: this.timezone })

        this.formattedDate = [
          isoDate.toISODate(),
          isoDate.toFormat(this.timeFormat),
        ].join('T')
      }
    },

    /**
     * Update the field's internal value
     */
    handleChange(event) {
      let value = event?.target?.value ?? event
      if(this.field.setDefaultMinuteZero == true && value !== ''){
        let date = new Date(value);
        let onlyDate = date.getFullYear()+'-'+ ('0' + (date.getMonth()+1)).slice(-2) +'-'+ ('0' + date.getDate()).slice(-2) +" "+('0' + date.getHours()).slice(-2)+":00"+":00";
        this.value = onlyDate;
        this.formattedDate = onlyDate;
      }else{
        let date = new Date(value);
        let onlyDate = date.getFullYear()+'-'+ ('0' + (date.getMonth()+1)).slice(-2) +'-'+ ('0' + date.getDate()).slice(-2) +" "+('0' + date.getHours()).slice(-2)+":"+('0' + date.getMinutes()).slice(-2)+":00";
        this.value = onlyDate;
        this.formattedDate = onlyDate;
      }
    }
  },

  computed: {
    timeFormat() {
      return this.currentField.step % 60 === 0 ? 'HH:mm' : 'HH:mm:ss'
    },

    timezone() {
      return Nova.config('userTimezone') || Nova.config('timezone')
    },
  }
}
</script>
