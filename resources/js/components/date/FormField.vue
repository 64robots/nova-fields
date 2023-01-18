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
            type="date"
            class="w-full form-control form-input form-input-bordered"
            ref="dateTimePicker"
            :id="currentField.uniqueKey"
            :dusk="field.attribute"
            :name="field.name"
            :value="formattedDate"
            :class="errorClasses"
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
        >
          <icon type="x-circle" width="22" height="22" viewBox="0 0 22 22" />
        </a>
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
      let onlyDate = this.currentField.value;
      if (!isNil(this.currentField.value)) {
        let date = new Date(this.currentField.value);
        onlyDate = date.getFullYear()+'-'+ ('0' + (date.getMonth()+1)).slice(-2) +'-'+ ('0' + date.getDate()).slice(-2);
      }
      this.value = onlyDate;
      this.formattedDate = onlyDate
    },

    /**
     * On save, populate our form data
     */
    fill(formData) {
      this.fillIfVisible(formData, this.field.attribute, this.value || '')

      if (this.currentlyIsVisible && filled(this.value)) {
        this.formattedDate = this.value
      }
    },

    /**
     * Update the field's internal value
     */
    handleChange(event) {
      let value = event?.target?.value ?? event

      this.value = DateTime.fromISO(value, {
        setZone: Nova.config('userTimezone') || Nova.config('timezone'),
      }).toISODate()

      if (this.field) {
        this.emitFieldValueChange(this.field.attribute, this.value)
      }
    },
  },

  computed: {
    timezone() {
      return Nova.config('userTimezone') || Nova.config('timezone')
    },
  },
}
</script>
