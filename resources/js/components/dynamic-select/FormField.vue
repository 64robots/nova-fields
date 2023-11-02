<template>
  <r64-default-field
      :field="field"
      :showHelpText="showHelpText"
      :errors="errors"
      :hide-label="hideLabelInForms"
      :field-classes="fieldClasses"
      :wrapper-classes="wrapperClasses"
      :label-classes="labelClasses"
  >
    <template #field>
      <Multiselect
          v-model="value"
          :options="options"
          :searchable="true"
          :object="true"
          track-by="value"
          label="label"
          placeholder="Choose an option"
          @input="onChange"
          @open="handleOpen"
      >
      </Multiselect>
      <p v-if="hasError" class="my-2 text-red-600">
        {{ firstError }}
      </p>
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import Multiselect from '@vueform/multiselect';
import R64Field from '../../mixins/R64Field'

export default {
  mixins: [FormField, HandlesValidationErrors,R64Field],
  props: ['resourceName', 'resourceId', 'field','Errors'],
  components: { Multiselect },
  data() {
    return {
      value:[],
      options: [],
      parentValue:null
    };
  },

  created() {
    if (this.field.dependsOn) {
      Nova.$on("nova-fields-dynamic-select-changed-" + this.field.dependsOn, this.onDependencyChanged);
    }
  },
  mounted(){
    if (this.field.dependsOn) {
      this.onDependencyChanged(null);
    }
    window.addEventListener('scroll', this.repositionDropdown);
  },
  beforeDestroy() {
    if (this.field.dependsOn) {
      Nova.$off("nova-fields-dynamic-select-changed-" + this.field.dependsOn, this.onDependencyChanged);
    }
  },

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.options = this.field.options;
      this.parentValue = this.field.dependValues;
      this.value = this.field.value;
      if(this.field.value && typeof this.options == "object") {
        this.value = this.options.find(item => item['value'] === parseInt(this.field.value));
      }
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      if(this.value) {
        formData.append(this.field.attribute, this.value.value)
      }
    },

    /**
     * Update the field's internal value.
     */
    handleChange(value) {
      this.value = value;
      this.$nextTick(() => this.repositionDropdown());
    },

    handleOpen() {
      this.repositionDropdown(true);
    },
    repositionDropdown(onOpen = false) {
      const ms = this.$refs.multiselect;
      if (!ms) return;

      const el = ms.$el;

      const handlePositioning = () => {
        const { top, height, bottom } = el.getBoundingClientRect();
        if(ms.$refs.list != undefined){
          if (onOpen) ms.$refs.list.scrollTop = 0;

          const fromBottom = (window.innerHeight || document.documentElement.clientHeight) - bottom;

          ms.$refs.list.style.position = 'fixed';
          ms.$refs.list.style.width = `${el.clientWidth}px`;

          if (fromBottom < 300) {
            ms.$refs.list.style.top = 'auto';
            ms.$refs.list.style.bottom = `${fromBottom + height}px`;
            ms.$refs.list.style['border-radius'] = '5px 5px 0 0';
          } else {
            ms.$refs.list.style.bottom = 'auto';
            ms.$refs.list.style.top = `${top + height}px`;
            ms.$refs.list.style['border-radius'] = '0 0 5px 5px';
          }
        }
      };

      if (onOpen) this.$nextTick(handlePositioning);
      else handlePositioning();
    },

    getDependValues(value, field) {
      if(value != null && typeof value == "object" && value.hasOwnProperty("value")){
        return value.value;
      }
      return value;
    },

    async onChange(row) {
      Nova.$emit("nova-fields-dynamic-select-changed-" + this.field.attribute.toLowerCase(), {
        value: row,
        field: this.field
      });
    },

    async onDependencyChanged(dependsOnValue) {
      let parentValue = dependsOnValue != null ? dependsOnValue.value : this.parentValue;
      parentValue = this.getDependValues(parentValue);
      Nova.$emit("nova-fields-dynamic-select-changed-" + this.field.attribute.toLowerCase(), {
        value: this.value,
        field: this.field
      });
      if(parentValue != undefined && typeof parentValue != "object"){
        this.options = (await Nova.request().get(`/nova-r64-api/options/${this.resourceName}`, {
          params: {
            attribute: this.field.attribute,
            parent: parentValue
          }
        })).data;
        if(this.field.value) {
          this.value = this.options.find(item => item['value'] == this.field.value);
        }
        this.$forceUpdate();
      }
    }
  },
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style>
.multiselect-field {
  .reorder__tag {
    background: #41b883;
    border-radius: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: all 0.25s ease;
    margin-bottom: 5px;

    &:hover {
      cursor: pointer;
      background: #3dab7a;
      transition-duration: 0.05s;
    }
  }

  .multiselect__clear {
    position: absolute;
    right: 41px;
    height: 40px;
    width: 40px;
    display: block;
    cursor: pointer;
    z-index: 2;

    &::before,
    &::after {
      content: '';
      display: block;
      position: absolute;
      width: 3px;
      height: 16px;
      background: #aaa;
      top: 12px;
      right: 4px;
    }

    &::before {
      transform: rotate(45deg);
    }

    &::after {
      transform: rotate(-45deg);
    }
  }
}
</style>
