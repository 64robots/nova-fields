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
      <Multiselect
        :value="selectedValue"
        track-by="value"
        label="label"
        :group-select="false"
        :searchable="true"
        :placeholder="placeholder"
        :disabled="readOnly"
        :options="options"
        @input="selectValue"
      />

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
import Multiselect from 'vue-multiselect'
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import R64Field from '../../mixins/R64Field'
import Computable from '../../mixins/Computable'

export default {
  components: { Multiselect },

  mixins: [HandlesValidationErrors, FormField, R64Field, Computable],

  data() {
    return {
      options: this.field.options
    }
  },

  computed: {
    /**
     * Get the placeholder.
     */
    placeholder() {
      return this.field.placeholder === undefined
        ? this.__('Choose an option')
        : this.field.placeholder
    },

    selectedValue() {
      if (!this.options) {
        return null
      }

      return this.options.find(o => o.value == this.value)
    }
  },

  watch: {
    value(value) {
      this.$emit('input', this.value)
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
    },

    selectValue(obj) {
      this.value = obj ? obj.value : null
    },

    computedOptionsReceived(data) {
      if (data && JSON.stringify(data) !== JSON.stringify(this.options)) {
        this.value = null
        this.options = data
      }
    },

    computedValueReceived(data) {
      this.value = data
    }
  }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
