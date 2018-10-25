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
        v-for="(row, index) in values"
        :key="row.row_id"
        class="flex items-center border-40 border relative"
      >
        <component
          class="remove-bottom-border w-full"
          :key="`${row.row_id}${f.attribute}`"
          :ref="`${row.row_id}${f.attribute}`"
          v-for="f in fields"
          v-model="row[f.attribute]"
          :is="`form-${f.component}`"
          :resource-name="resourceName"
          :resource-id="resourceId"
          :field="f"
          :base-classes="field.childConfig"
          :errors="errors"
        />
        <span
          class="flex items-center justify-center bg-danger text-white p-2 m-2 w-6 h-6 rounded-full cursor-pointer font-bold"
          @click="rowToRemove = row.row_id"
        >x</span>
      </div>
      <div class="flex justify-end">
        <a
          class="btn btn-primary p-3 rounded cursor-pointer mt-3"
          @click="addRow"
        >{{ addRowText }}</a>
      </div>
      <portal to="modals">
        <transition name="fade">
          <RowDeleteModal
            v-if="rowToRemove"
            @confirm="removeRow"
            @close="rowToRemove = null"
          />
        </transition>
      </portal>
      <p v-if="hasError" class="my-2 text-danger">
        {{ firstError }}
      </p>
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import RowField from './RowField';
import R64Field from '../../mixins/R64Field';
import RowHeading from './RowHeading';
import RowDeleteModal from './RowDeleteModal';

export default {
  mixins: [FormField, HandlesValidationErrors, RowField, R64Field],

  components: { RowHeading, RowDeleteModal },

  props: ['resourceName', 'resourceId', 'field'],

  data() {
    return {
      rowToRemove: null,
      fields: this.field.fields
    };
  },

  watch: {
    values: {
      deep: true,
      handler(values) {
        const value = values.map(value => {
          const copy = Object.assign({}, value);
          delete copy.row_id;
          return copy;
        });
        this.value = JSON.stringify(value);
        this.$emit('input', value);
      }
    }
  },

  computed: {
    addRowText() {
      return this.field.addRowText || this.__('Add Row');
    },

    firstError() {
      const errors = this.itemErrors;
      return errors[Object.keys(errors)[0]].shift();
    },

    /**
     * Get errors with correct fieldname
     */
    itemErrors() {
      return Object.keys(this.errors.errors).reduce((acc, curr) => {
        // replace fieldname in error messages
        acc[curr] = this.errors.errors[curr].map((error) => {
          const split = curr.split('.');
          const fieldAttribute = split[split.length - 1];

          // find fieldname
          return error.replace(curr, this.field.fields.reduce((fieldname, field) => {
            return (field.attribute == fieldAttribute) ? field.name : fieldname;
          }, ""));
        });
        return acc;
      }, {});
    }
  },

  mounted() {
    this.values.forEach(value => {
      Object.keys(value).forEach(key => {
        const ref = this.$refs[`${value.row_id}${key}`];
        if (!ref) return;

        const element = ref[0];
        element.handleChange(value[key]);
      });
    });
  },

  methods: {
    addRow() {
      const obj = this.fields.reduce(function(result, field) {
        result[field.attribute] = '';
        return result;
      }, {});

      this.addItemToRow(obj);
    },

    removeRow() {
      const index = this.values.findIndex(
        row => row.row_id === this.rowToRemove
      );
      this.values.splice(index, 1);
      this.rowToRemove = null;
    },

    /*
    * Set the initial, internal value for the field.
    */
    setInitialValue() {
      this.value = this.field.value || [];
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      this.values.forEach((row, index) => {
        Object.keys(row).forEach((key) => {
          formData.append(`${this.field.attribute}[${index}][${key}]`, row[key]);
        })
      })
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
