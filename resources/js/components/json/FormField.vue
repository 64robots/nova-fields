<template>
  <div>
    <r64-default-field
            v-if="! field.flatten"
            :field="field"
            :hide-label="field.hideLabelInForms"
            :field-classes="field.fieldClasses"
            :wrapper-classes="field.wrapperClasses"
            :label-classes="field.labelClasses"
    >
      <template slot="field">
        <FormFieldItem
                :class="{'remove-bottom-border': index === fields.length - 1}"
                :key="index"
                :ref="f.attribute"
                v-for="(f, index) in fields"
                v-bind="{ errors, resourceName, resourceId }"
                :parent="field"
                :field="f"
                :base-classes="field.childConfig"
                :current-value="value"
        />
      </template>
    </r64-default-field>
    <template v-else>
      <FormFieldItem
              :class="{'remove-bottom-border': index === fields.length - 1}"
              :key="index"
              :ref="f.attribute"
              v-for="(f, index) in fields"
              v-bind="{ errors, resourceName, resourceId }"
              :parent="field"
              :field="f"
              :base-classes="field.childConfig"
              :current-value="value"
      />
    </template>
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import HasChildFields from '../../mixins/HasChildFields'
import FormFieldItem from './FormFieldItem'

export default {
  mixins: [FormField, HandlesValidationErrors, HasChildFields],

  components: { FormFieldItem },

  props: ['resourceName', 'resourceId', 'field'],

  mounted() {
    this.fields.forEach(field => {
      this.$watch(
        () => {
          return this.$refs[field.attribute][0].$children[0].value
        },
        value => {
          let currentJson = JSON.parse(this.value || '{}')
          if (field.attribute in currentJson) {
            currentJson[field.attribute] = value
          } else {
            currentJson = Object.assign(currentJson, {
              [field.attribute]: value
            })
          }
          this.value = JSON.stringify(currentJson)
        }
      )
    })
  },

  methods: {
    /**
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || '{}'
      if (typeof this.value === 'object') {
        this.value = JSON.stringify(this.value) || '{}'
        this.value = Object.assign({}, JSON.parse(this.value)) || {}
        this.value = JSON.stringify(this.value) || '{}'
      }
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      function MergeObject(attribute) {
        this.obj = {}
        this.attribute = attribute
      }
      MergeObject.prototype.append = function(attr, value) {
        const parts = attr.split('[')
        const key = parts[0]
        const rest = parts.slice(1).length ? '[' + parts.slice(1).join('[') : ''

        this.obj[`${this.attribute}[${key}]${rest}`] = value
      }
      MergeObject.prototype.fill = function(data) {
        _.forEach(this.obj, (val, key) => {
          formData.append(key, val)
        })
      }

      let data = _.tap(new MergeObject(this.field.attribute), data => {
        _(this.fields).each(field => {
          field.fill(data)
        })
      })

      data.fill(formData)
    },

    /**
     * Update the field's internal value.
     */
    handleChange(value) {
      this.value = value
    }
  }
}
</script>
