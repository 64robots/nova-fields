<template>
  <r64-default-field
    :field="field"
    :hide-label="hideLabelInForms"
    :field-classes="fieldClasses"
    :wrapper-classes="wrapperClasses"
    :label-classes="labelClasses"
  >
    <template slot="field">
      <RowHeading
        v-if="!field.hideHeading"
        :fields="fields"
        :base-classes="field.childConfig"
      />
      <div
        v-for="(row, indexV) in values"
        :key="row.row_id"
        class="flex items-center border-40 border relative"
      >
        <component
          class="remove-bottom-border w-full"
          :key="`${row.row_id}${f.attribute}`"
          :ref="`${row.row_id}${f.attribute}`"
          v-for="(f, indexF) in fields"
          v-model="row[f.attribute]"
          :is="`form-${f.component}`"
          :resource-name="resourceForField(f)"
          :resource-id="resourceId"
          :field="f"
          :base-classes="field.childConfig"
          :errors="errors"
          :with-data-set="indexV"
          :data-set="dataSets[indexF + '-' + f.component]"
          :row-values="row"
          @data-set-available="data => dataSets[indexF + '-' + f.component] = data"
          @input="forceInputEvent"
        />
        <span
          class="flex items-center justify-center bg-danger text-white p-2 m-2 w-6 h-6 rounded-full cursor-pointer font-bold"
          @click="rowToRemove = row.row_id"
        >x</span>
      </div>
      <div class="flex justify-end">
        <a
          :class="{ 'pointer-events-none opacity-50': shouldDisableButton }"
          class="btn btn-primary p-3 rounded cursor-pointer mt-3"
          @click="addRow"
        >{{ addRowText }}</a>
      </div>

      <portal to="modals">
        <transition name="fade">
          <delete-resource-modal
            v-if="rowToRemove"
            @confirm="removeRow"
            @close="rowToRemove = false"
          >
            <div
              slot-scope
              class="p-8"
            >
              <heading
                :level="2"
                class="mb-6"
                v-text="__('Delete Row')"
              />

              <p
                class="text-80 leading-normal"
                v-text="__('Are you sure you want to delete this row?')"
              />
            </div>
          </delete-resource-modal>
        </transition>
      </portal>
      <p
        v-if="hasError"
        class="my-2 text-danger"
      >{{ firstError }}</p>
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import RowField from './RowField'
import R64Field from '../../mixins/R64Field'
import RowHeading from './RowHeading'

export default {
  mixins: [FormField, HandlesValidationErrors, RowField, R64Field],

  components: { RowHeading },

  props: ['resourceName', 'resourceId', 'field'],

  data() {
    return {
      rowToRemove: null,
      fields: this.field.fields,
      dataSets: {}
    }
  },

  watch: {
    values: {
      deep: true,
      handler(values) {
        const value = values.map(value => {
          const copy = Object.assign({}, value)
          delete copy.row_id
          return copy
        })
        this.value = JSON.stringify(value)
        this.$emit('input', this.value)
      }
    }
  },

  computed: {
    shouldDisableButton() {
      if (this.field.maxRows === undefined) {
        return false
      }

      return Number(this.field.maxRows) <= this.values.length
    },

    addRowText() {
      return this.field.addRowText || this.__('Add Row')
    },

    firstError() {
      const errors = this.itemErrors
      return errors[Object.keys(errors)[0]].shift()
    },

    /**
     * Get errors with correct fieldname.
     */
    itemErrors() {
      return Object.keys(this.errors.errors).reduce((acc, curr) => {
        const split = curr.split('.')

        if (split[0] !== this.field.attribute) {
          return acc
        }

        // replace fieldname in error messages
        acc[curr] = this.errors.errors[curr].map(error => {
          const fieldAttribute = split[split.length - 1]

          // find fieldname
          return error.replace(
            curr,
            this.field.fields.reduce((fieldname, field) => {
              return field.attribute == fieldAttribute ? field.name : fieldname
            }, '')
          )
        })

        return acc
      }, {})
    }
  },

  created() {
    this.fields.forEach((value, key) => {
      if (value.component === 'nova-fields-belongs-to') {
        this.$set(this.dataSets, key + '-' + value.component, [])
      }
    })
  },

  mounted() {
    this.values.forEach(value => {
      Object.keys(value).forEach(key => {
        const ref = this.$refs[`${value.row_id}${key}`]
        if (!ref) return

        const element = ref[0]
        element.handleChange(value[key])
      })
    })
  },

  methods: {
    forceInputEvent(event) {
      this.$nextTick(() => {
        this.$emit('input', event)
      })
    },

    addRow() {
      const obj = this.fields.reduce(function(result, field) {
        result[field.attribute] = ''
        return result
      }, {})

      this.addItemToRow(obj)
    },

    removeRow() {
      const index = this.values.findIndex(
        row => row.row_id === this.rowToRemove
      )
      this.rowToRemove = false
      this.$nextTick(() => this.$delete(this.values, index))
    },

    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || []
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      this.values.forEach((row, index) => {
        Object.keys(row).forEach(key => {
          formData.append(`${this.field.attribute}[${index}][${key}]`, row[key])
        })
      })
    },

    /**
     * Update the field's internal value.
     */
    handleChange(value) {
      this.value = value
    },

    /**
     * Get the resource name diferent between relationships and others fields.
     */
    resourceForField(field) {
      switch (field.component) {
        case 'nova-fields-belongs-to':
          return this.field.sanitizedAttribute

        default:
          return this.resourceName
      }
    }
  }
}
</script>
