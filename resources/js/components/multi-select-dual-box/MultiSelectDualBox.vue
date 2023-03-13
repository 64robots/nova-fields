<template>
  <div>
    <div class="flex">

      <div class="flex-1 px-2">
        <div class="my-1">
          <label v-html="options.subHeader1"></label>
        </div>
        <input type="text" v-model="searchOptions" :placeholder="options.searchText1"
               class="w-full form-control form-input form-input-bordered">
        <div class="overflow-x-auto multi-select-box p-0 mt-2 form-input-bordered">
          <ul class="p-0" v-if="filterOptions.length > 0">
            <li class="p-2 cursor-pointer hover:bg-40" v-for="(item,index) in filterOptions"
                v-bind:key="item.id" @click="changeValue('right',item)">
              {{ item.label }}
            </li>
          </ul>
          <ul class="p-0 bg-40 h-full text-center" v-else>
            <li class="p-2 hover:bg-40"> {{ options.noData1 }}</li>
          </ul>
        </div>
        <div class="flex items-center justify-center py-2">
          <button type="button" class="btn btn-default btn-primary inline-flex items-center relative mr-3"
                  @click="changeValue('right',-1)">Move All Right
          </button>
          <a class="btn btn-link dim cursor-pointer text-80" v-if="searchOptions.length > 0"
             @click="searchOptions = ''">Clear</a>
        </div>
      </div>

      <div class="flex-1 px-2">
        <div class="my-1">
          <label v-html="options.subHeader2"></label>
        </div>
        <input type="text" v-model="searchSelectedOptions" :placeholder="options.searchText2"
               class="w-full form-control form-input form-input-bordered">
        <div class="overflow-x-auto multi-select-box p-0 mt-2 form-input-bordered">
          <draggable :move="checkMove" :list="filterSelected" class="list-group" group="people" @start="drag=true" @end="drag=false" v-if="field.sortable">
            <div v-if="filterSelected.length > 0" class="p-2 cursor-pointer hover:bg-40" v-for="(item,index) in filterSelected"
                 v-bind:key="item.id" @click="changeValue('left',item)">
              {{ item.label }}
            </div>
            <div class="p-0 bg-40 h-full text-center" v-else>
              <li class="p-2"> {{ options.noData2 }}</li>
            </div>
          </draggable>
          <div v-else>
            <ul class="p-0" v-if="filterSelected.length > 0">
              <li class="p-2 cursor-pointer hover:bg-40" v-for="(item,index) in filterSelected"
                  v-bind:key="item.id" @click="changeValue('left',item)">
                {{ item.label }}
              </li>
            </ul>
            <ul class="p-0 bg-40 h-full text-center" v-else>
              <li class="p-2"> {{ options.noData2 }}</li>
            </ul>
          </div>
        </div>
        <div class="flex items-center justify-center py-2">
          <button type="button" class="btn btn-default btn-primary inline-flex items-center relative mr-3"
                  @click="changeValue('left',-1)">Move All Left
          </button>
          <a class="btn btn-link dim cursor-pointer text-80" v-if="searchSelectedOptions.length > 0"
             @click="searchSelectedOptions = ''">Clear</a>
        </div>
      </div>
    </div>

    <custom-modal v-if="openModal" @close="handleClose" @confirm="handleConfirm" :confirmation-message="confirmationMessage" :confirmation-title="`Warning!`"></custom-modal>

  </div>
</template>


<script>
import {VueDraggableNext} from 'vue-draggable-next';
import CustomModal from '../CustomModal.vue';
export default {
  name: 'MultiSelectDualBox',
  props: ["options","field","parentValue",'resourceId'],
  components:{
    VueDraggableNext,
    CustomModal
  },
  data() {
    return {
      selected: [],
      options: [],
      searchOptions: "",
      searchSelectedOptions: "",
      value: [],
      openModal: false,
      cloneType:"",
      cloneitem:null,
      confirmationMessage : this.options.confirmationMessage,
      isVisibleModal: ((this.resourceId != undefined && this.options.confirmationOnUpdate) || (this.resourceId == undefined && this.options.confirmationOnCreate)  || this.options.confirmation) ? true : false
    };
  },
  updated() {
    this.updateSelectedOptions();
  },
  methods: {
    handleClose(){
      this.searchOptions = '';
      this.searchSelectedOptions = '';
      this.openModal = false;
      this.$emit('close');
    },
    handleConfirm(){
      this.searchOptions = '';
      this.searchSelectedOptions = '';
      this.openModal = false;
      if(this.cloneType == 'right'){
        this.moveRight(this.cloneitem);
      }else{
        this.moveLeft(this.cloneitem);
      }
    },
    changeValue(type,item){
      if(this.isVisibleModal){
        this.cloneType = type;
        this.cloneitem = item;
        this.openModal = true;
      }else{
        if(type == 'right'){
          this.moveRight(item);
        }else{
          this.moveLeft(item);
        }
      }
    },
    updateSelectedOptions(){
      if(this.filterSelected.length > 0){
        let ids = [];
        this.filterSelected.forEach((element) => {
          ids.push(element.value);
        });
        let values = {
          value : ids,
          parentValue : this.parentValue != null ? this.parentValue.value : null
        }
        if(this.field.sortable)
        {
          this.options.selectedIds = ids;
          this.options.finalSelectedIds = ids;
        }
        Nova.$emit(this.field.attribute+'-change',values);
      }
    },
    moveRight(item) {
      let vue = this;
      if (typeof item == 'object') {
        let index = vue.options.options.indexOf(item);
        vue.options.selected.push(vue.options.options[index]);
        vue.options.selectedIds.push(vue.options.options[index].value);
        vue.options.options.splice(index, 1);
      } else {
        for (var cont = 0; cont < vue.options.options.length; cont++) {
          vue.options.selected.push(vue.options.options[cont]);
          vue.options.selectedIds.push(vue.options.options[cont].value);
        }
        vue.options.options.splice(0, vue.options.options.length);
      }
      vue.options.cloneSelected = vue.options.selected;
    },
    moveLeft(item) {
      let vue = this;
      if (typeof item == 'object') {
        let index = (vue.options.options.indexOf(item) != -1 ? vue.options.options.indexOf(item) : vue.options.selected.indexOf(item)) ?? null;
        if(index != null){
          vue.options.options.push(vue.options.selected[index]);
          let idIndex = vue.options.selectedIds.indexOf(vue.options.selected[index].value);
          if (idIndex >= 0) {
            vue.options.selectedIds.splice(idIndex, 1);
          }
          vue.options.selected.splice(index, 1);
        }
      }else{
        for (var cont = 0; cont < vue.options.selected.length; cont++) {
          vue.options.options.push(vue.options.selected[cont]);
          let idIndex = vue.options.selectedIds.indexOf(vue.options.selected[cont].value);
          if (idIndex >= 0) {
            vue.options.selectedIds.splice(idIndex, 1);
          }
        }
        vue.options.selected.splice(0, vue.options.selected.length);
      }
      vue.options.cloneSelected = vue.options.selected;
    }
  },
  computed: {
    filterOptions() {
      let vue = this;
      if (vue.searchOptions) {
        let val = vue.options.options.filter(item => {
          return item.label.toLowerCase().indexOf(vue.searchOptions.toLowerCase()) !== -1;
        });
        return val;
      }
    },
    filterSelected() {
      let vue = this;
      if (vue.searchSelectedOptions) {
        return vue.options.selected.filter(item => {
          return (
              item.label.toLowerCase().indexOf(vue.searchSelectedOptions.toLowerCase()) !== -1
          );
        });
      }
      return vue.options.cloneSelected || [];
    }
  }
};
</script>
<style scoped>
.multi-select-box {
  max-height: 250px;
  height: 250px;
}
</style>
