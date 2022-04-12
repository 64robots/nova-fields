<template>
  <r64-panel-item
      :hide-field="hideField"
      :field="field"
      :hide-label="hideLabelInDetail"
      :label-classes="panelLabelClasses"
      :field-classes="panelFieldClasses"
      :wrapper-classes="panelWrapperClasses"
  >
    <div slot="value">
      <div v-if="value.length > 0" class="py-1 flex flex-wrap" >
        <span  v-for="(item,index) in value" :key="index" class='bg-70 font-bold text-white px-2 py-1 m-1 text-xs rounded-full whitespace-no-wrap'>{{ item.label }}</span>
      </div>
    </div>
  </r64-panel-item>
</template>

<script>
import R64Field from '../../mixins/R64Field'
export default {
  mixins: [R64Field],
  props: ['resource', 'resourceName', 'resourceId', 'field'],
  data(){
    return{
      value:[],
      parentValue:null
    }
  },
  methods:{
    updateOptions() {
      if (this.parentValue != null && this.parentValue != "") {
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
                this.value = response.data;
              }
            });
      }
    },

    /*getElementValue(root, elemName){
        let value = null
        root.$children.forEach(component => {
            if (component.field !== undefined && component.field.attribute == elemName) {
                value = component.field.value
            }
        })
        return value
    }*/
  },
  created(){
    //console.log(this.getElementValue(this.$parent, this.field.parentAttribute));
    this.parentValue = this.field.value ?? null;
    this.updateOptions();
  }
}
</script>

<style scoped>

</style>
