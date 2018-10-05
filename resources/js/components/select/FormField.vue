<template>
  <r64-default-field
    :hide-field="hideField"
    :field="field"
    :hide-label="hideLabelInForms"
    :field-classes="fieldClasses"
    :wrapper-classes="wrapperClasses"
    :label-classes="labelClasses"
  >
    <template slot="field">
      <select
        :id="field.name"
        v-model="value"
        :class="[inputClasses, errorClasses]"
        :disabled="readOnly"
      >
        <option value="" selected disabled>
          {{ placeholder }}
        </option>

        <option
          v-for="option in options"
          :key="option.value"
          :value="option.value"
          :selected="option.value == value"
          :disabled="option.group"
        >
          {{ option.label }}
        </option>
      </select>

      <p v-if="hasError" class="my-2 text-danger">
          {{ firstError }}
      </p>
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import R64Field from '../../mixins/R64Field'

export default {
  mixins: [HandlesValidationErrors, FormField, R64Field],

  computed: {
    options() {
      const withoutGroup = this.field.options.filter(
        option => typeof option.label !== 'object'
      )

      if (withoutGroup.length === this.field.options.length) {
        return this.field.options
      }

      const grouped = this.field.options.filter(
        option => typeof option.label === 'object'
      )

      const options = [...withoutGroup]

      grouped.forEach(option => {
        if (typeof option.label === 'object') {
          options.push({ label: option.value, value: '', group: true })
          Object.keys(option.label).map(key =>
            options.push({
              value: key,
              label: option.label[key]
            })
          )
        } else {
          options.push(option)
        }
      })

      return options
    },

    /**
     * Get the placeholder.
     */
    placeholder() {
      return this.field.placeholder === undefined
        ? this.__('Choose an option')
        : this.field.placeholder
    }
  },

  watch: {
    value(value) {
      this.$emit('value', value)
    }
  },

  methods: {
    /**
     * Provide a function that fills a passed FormData object with the
     * field's internal value attribute. Here we are forcing there to be a
     * value sent to the server instead of the default behavior of
     * `this.value || ''` to avoid loose-comparison issues if the keys
     * are truthy or falsey
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value)
    }
  }
}
</script>
