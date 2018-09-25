<template>
  <r64-default-field
    :field="field"
    :hide-label="field.hideLabelInForms"
    :field-classes="field.fieldClasses"
    :wrapper-classes="field.wrapperClasses"
    :label-classes="field.labelClasses"
  >
    <template slot="field">
      <component
        :class="{'remove-bottom-border': index == fields.length - 1}"
        :key="index"
        :ref="f.attribute"
        v-for="(f, index) in fields"
        :is="`form-${f.component}`"
        :validationErrors="validationErrors"
        :resource-name="resourceName"
        :resource-id="resourceId"
        :field="f"
        :base-classes="field.childConfig"
      />
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import HasChildFields from '../../mixins/HasChildFields';

export default {
  mixins: [FormField, HandlesValidationErrors, HasChildFields],

  props: ['resourceName', 'resourceId', 'field'],

  mounted() {
    this.fields.forEach(field => {
      this.$watch(
        () => {
          return this.$refs[field.attribute][0].value;
        },
        value => {
          let currentJson = JSON.parse(this.value || '{}');
          if (field.attribute in currentJson) {
            currentJson[field.attribute] = value;
          } else {
            currentJson = Object.assign(currentJson, {
              [field.attribute]: value
            });
          }
          this.value = JSON.stringify(currentJson);
        }
      );
    });
  },

  methods: {
    /**
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || '{}';
      if (typeof this.value === 'object') {
        this.value = JSON.stringify(this.value) || '{}';
        this.value = Object.assign({}, JSON.parse(this.value)) || {};
        this.value = JSON.stringify(this.value) || '{}';
      }
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      // fake formdata object that we can more easily consume
      function JsonObject(obj) {
        this.obj = {};
      }
      JsonObject.prototype.append = function(attr, value) {
        this.obj[attr] = value;
      };
      JsonObject.prototype.toJSON = function() {
        return this.obj;
      };

      let data = _.tap(new JsonObject(), data => {
        _(this.fields).each(field => {
          field.fill(data);
        });
      });

      formData.append(this.field.attribute, JSON.stringify(data));
    },

    /**
     * Update the field's internal value.
     */
    handleChange(value) {
      this.value = value;
    }
  }
};
</script>
