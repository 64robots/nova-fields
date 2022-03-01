<template>
  <r64-default-field
      :hide-field="hideField"
      :field="field"
      :hide-label="hideLabelInForms"
      :field-classes="fieldClasses"
      :wrapper-classes="wrapperClasses"
      :label-classes="labelClasses"
      class="simple-repeatable"
  >
    <template slot="field">
      <div class="flex flex-col">
        <!-- Title columns -->
        <div class="simple-repeatable-header-row flex border-b border-40 py-2">
          <div v-for="(repField, i) in field.repeatableFields" :key="i" class="font-bold text-90 text-md w-full mr-3">
            {{ repField.name }}
          </div>
        </div>

        <draggable v-model="fieldsWithValues" :options="{ handle: '.vue-draggable-handle' }">
          <div
              v-for="(fields, i) in fieldsWithValues"
              :key="fields[0].attribute"
              :class="[inputClasses, errorClasses]"
          >
            <div v-if="field.canDraggable" class="vue-draggable-handle flex justify-center items-center cursor-pointer">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" class="fill-current">
                <path
                    d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"
                />
              </svg>
            </div>

            <component
                v-for="(repField, i) in fields"
                :key="i"
                :is="`form-${repField.component}`"
                :field="repField"
                class="mr-3"
            />

            <div
                class="delete-icon flex justify-center items-center cursor-pointer"
                @click="deleteRow(i)"
                v-if="field.canDeleteRows"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" class="fill-current">
                <path
                    d="M8 6V4c0-1.1.9-2 2-2h4a2 2 0 012 2v2h5a1 1 0 010 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V8H3a1 1 0 110-2h5zM6 8v12h12V8H6zm8-2V4h-4v2h4zm-4 4a1 1 0 011 1v6a1 1 0 01-2 0v-6a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 01-2 0v-6a1 1 0 011-1z"
                />
              </svg>
            </div>
          </div>
        </draggable>

        <button
            v-if="field.canAddRows"
            @click="addRow"
            class="add-button btn btn-default btn-primary mt-3"
            :class="{ 'delete-width': field.canDeleteRows }"
            type="button"
        >
          {{ __('simpleRepeatable.addRow') }}
        </button>
      </div>
    </template>
  </r64-default-field>
</template>

<script>
import Draggable from 'vuedraggable';
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import R64Field from '../../mixins/R64Field'

let UNIQUE_ID_INDEX = 0;

export default {
  mixins: [FormField, HandlesValidationErrors,R64Field],

  components: { Draggable },

  props: ['resourceName', 'resourceId', 'field'],

  data() {
    return {
      fieldsWithValues: [],
    };
  },

  methods: {
    handleChange(e) {
      Nova.$emit(this.field.attribute + '-change', this.getAllValues())

    },
    setInitialValue() {
      let value = [];
      try {
        if (!this.field.value) {
          value = [];
        } else if (typeof this.field.value === 'string') {
          value = JSON.parse(this.field.value) || [];
        } else if (Array.isArray(this.field.value)) {
          value = this.field.value;
        }
      } catch (e) {
        value = [];
      }

      this.fieldsWithValues = value.map(this.copyFields);

    },

    copyFields(value) {
      return this.field.repeatableFields.map(field => ({
        ...field,
        attribute: `${field.attribute}---${UNIQUE_ID_INDEX++}`,
        value: value && value[field.attribute],
      }));
    },

    getAllValues() {
      const allValues = [];

      for (const fields of this.fieldsWithValues) {
        const rowValues = {};

        for (const field of fields) {
          const formData = new FormData();
          field.fill(formData);

          const normalizedAttribute = field.attribute.replace(/---\d+/, '');
          rowValues[normalizedAttribute] = formData.get(field.attribute);
        }

        allValues.push(rowValues);
      }
      return allValues
    },

    fill(formData) {
      formData.append(this.field.attribute, JSON.stringify(this.getAllValues()));
    },

    addRow() {
      this.fieldsWithValues.push(this.copyFields());
    },

    deleteRow(index) {
      this.fieldsWithValues.splice(index, 1);
      console.log(this.field.attribute + '-change');
      Nova.$emit(this.field.attribute + '-change', this.getAllValues())
    },
  },

  watch: {
    fieldsWithValues: function(fieldGroups) {
      fieldGroups.forEach(function(fieldGroup) {
        fieldGroup.forEach(function(field) {
          Nova.$off(field.attribute+'-change', this.handleChange)
          Nova.$on(field.attribute+'-change', this.handleChange)
        }.bind(this))
      }.bind(this))
    }
  },

  computed: {
    defaultAttributes() {
      return {
        type: 'number',
        min: this.field.min,
        max: this.field.max,
        step: this.field.step,
        pattern: this.field.pattern,
        placeholder: this.field.placeholder || this.field.name,
        class: this.errorClasses,
      };
    },

    extraAttributes() {
      return {
        ...this.defaultAttributes,
        ...this.field.extraAttributes,
      };
    },
  },
};
</script>

<style lang="scss" scoped>
.simple-repeatable {
  .simple-repeatable-header-row {
    width: calc(100% - 10px);
  }

  .simple-repeatable-row {
    width: calc(100% + 68px);

    // Select field
    > * {
      width: 100%;
      border: none !important;

      // Hide name
      > :not(svg):nth-child(1) {
        display: none;
      }

      // Fix field width and padding
      > :nth-child(2) {
        width: 100% !important;
        padding: 0 !important;
      }
    }

    margin-left: -46px;

    .delete-icon {
      width: 36px;
      height: 36px;
      margin-right: 10px;
      cursor: pointer;

      &:hover {
        cursor: pointer;

        > svg > path {
          fill: var(--danger);
        }
      }
    }

    .vue-draggable-handle {
      height: 36px;
      width: 36px;
      margin-right: 10px;

      &:hover {
        opacity: 0.8;
      }
    }

    &:hover {
      background: var(--40);
    }
  }

  .add-button {
    width: calc(100% + 11px);

    &.delete-width {
      width: calc(100% - 22px);
    }
  }

  > :nth-child(1) {
    min-width: 20%;
  }

  // Make field area full width
  > :nth-child(2) {
    width: 100% !important;
  }
}
</style>
