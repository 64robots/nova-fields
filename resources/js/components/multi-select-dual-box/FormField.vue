<template>
  <r64-default-field
      :field="field"
      :showHelpText="showHelpText"
      :errors="errors"
      :hide-field="hideField"
      :hide-label="hideLabelInForms"
      :field-classes="fieldClasses"
      :wrapper-classes="wrapperClasses"
      :label-classes="labelClasses"
  >
    <template slot="field">
      <div
          v-if="field.asHtml"
          v-html="field.value"
      />

      <MultiSelectDualBox ref="multi-select-dual-box" :resourceId="resourceId" :parentValue="parentValue" :field="field" :options="options"></MultiSelectDualBox>
      <p v-if="hasError" class="my-2 text-danger">
        {{ firstError }}
      </p>
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import MultiSelectDualBox from './MultiSelectDualBox';
import R64Field from '../../mixins/R64Field'
import HandlesFieldValue from "../../mixins/HandlesFieldValue";
export default {
  mixins: [FormField, HandlesValidationErrors,R64Field],
  props: ['resourceName', 'resourceId', 'field'],
  components: {
    MultiSelectDualBox
  },
  data() {
    return {
      options: {
        searchText1: this.field.leftPlaceholder || "Search Here...",
        searchText2: this.field.rightPlaceholder || "Search Here...",
        subHeader1: this.field.leftHeader || "",
        subHeader2: this.field.rightHeader ||  "",
        noData1: this.field.leftEmptyMessage || "No Data Found",
        noData2: this.field.rightEmptyMessage || "No Data Found",
        selected: [], // Array of pre-selected elements (list 2)
        cloneSelected: [],
        selectedIds:[],
        finalSelectedIds:[],
        options: this.field.options || [], // Array of options (list 1)
        confirmationOnUpdate: this.field.confirmationOnUpdate == true ? true : false,
        confirmationOnCreate: this.field.confirmationOnCreate == true ? true : false,
        confirmation: this.field.confirmation == true ? true : false,
        confirmationMessage: this.field.confirmationMessage || "Are you sure you want to change ?",
      },
      defaultOptions: this.field.options || [],
      parentValue: null,
    };
  },
  mounted() {
    this.watchedComponents.forEach(component => {
      let attribute = "value";

      if (component.field.component === "belongs-to-field") {
        attribute = "selectedResource";
      }

      component.$watch(
          attribute,
          value => {
            this.parentValue =
                value && attribute == "selectedResource"
                    ? value.value
                    : value;

            this.updateOptions();
          },
          { immediate: true }
      );
    });
  },
  computed: {
    watchedComponents() {
      return this.$parent.$children.filter(component => {
        return this.isWatchingComponent(component);
      });
    },

    disabled() {
      return this.options.length == 0;
    }
  },
  methods:{
    fill(formData){
      let ids = [];
      this.options.cloneSelected.forEach((element) => {
        ids.push(element.value);
      });
      formData.append(this.field.attribute, ids || '');
    },

    updateOptions() {
      if (this.parentValue != null && this.parentValue != "") {
        this.options.options = this.defaultOptions;
        Nova.request()
            .get(`/nova-r64-api/options/${this.resourceName}`, {
              params: {
                attribute: this.field.attribute,
                parent: this.parentValue
              }
            })
            .then(response => {
              this.loaded = true;
              if (response.data.length > 0){
                let options = response.data;
                this.options.selected = options || [];
                this.options.cloneSelected = options || [];
                let vue = this;
                options.filter(function(val){
                  vue.options.selectedIds.push(val.value);
                });
                vue.options.finalSelectedIds = vue.options.selectedIds;
                if(vue.options.options.length > 0){
                  vue.options.options = vue.options.options.filter(o1 => !options.some(o2 => o1.value === o2.value));
                }
              }
            });
      }
    },

    isWatchingComponent(component) {
      return (
          component.field !== undefined &&
          component.field.attribute == this.field.parentAttribute
      );
    }
  }
}
</script>

<style scoped>

</style>
