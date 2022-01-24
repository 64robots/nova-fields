<template>
    <div class="simple-repeatable">
      <div class="flex flex-col">
        <!-- Title columns -->
        <div class="simple-repeatable-header-row flex border-b border-40 py-2">
          <div v-for="(repField, i) in field.repeatableFields" :key="i" class="font-bold text-90 text-md w-full">
            {{ repField.name }}
          </div>
        </div>

          <div
            v-for="(fields, i) in fieldsWithValues"
            :key="fields[0].attribute"
            class="simple-repeatable-row flex py-2"
          >

            <component
              v-for="(repField, i) in fields"
              :key="i"
              :is="`detail-${repField.component}`"
              :field="repField"
              :resource-name="resourceName"
              :resource-id="resourceId"
            />
          </div>
      </div>
    </div>
</template>

<script>
import { BehavesAsPanel, FormField } from 'laravel-nova';

let UNIQUE_ID_INDEX = 0;

export default {
  mixins: [BehavesAsPanel, FormField],

  props: ['resourceName', 'resourceId', 'field'],

  data() {
    return {
      fieldsWithValues: [],
    };
  },

  methods: {

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
      return this.field.repeatableFields.map(field => {
        let thisValue = value && value[field.attribute]
        if(field.options !== undefined) {
          let o = field.options.find((o) => o.value == thisValue)
          if(typeof o !== "undefined") {
            thisValue = o.label
          }
        }
        return {

          ...field,
          attribute: `${field.attribute}---${UNIQUE_ID_INDEX++}`,
          value: thisValue,
        }
      });
    },


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
    width: calc(100%);
  }

  .simple-repeatable-row {
    width: calc(100%);

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
  }
}
</style>
