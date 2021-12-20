<template>
  <div class="filemanager-image-block flex flex-row border-lg border-50 px-0 py-0 max-w-xs mb-2">
    <div v-if="field.url || url" class="image-block card flex justify-center w-full h-5/6 px-2">
      <img v-if="!url && field.url" :src="field.url" class="image block w-full self-center" draggable="false">
      <img v-if="url" :src="url" class="image block w-full self-center" draggable="false">
    </div>
    <div v-else-if="!url && !field.url && field.value" class="image-block card flex justify-center w-full h-5/6 px-2">
      <img :src="field.value" class="image block w-full self-center" draggable="false">
    </div>
    <div class="items-stretch w-full mb-2 relative pt-1">
      <div class="flex -mr-px max-w-xs">
        <button type="button" class="filemanager-open flex items-center leading-normal rounded border border-grey-light bg-40 px-3 whitespace-no-wrap text-grey-dark text-sm cursor-pointer" @click="openModalFilemanager">{{ __('Open FileManager') }}</button>
      </div>
    </div>
  </div>
</template>

<script>
import ImageDetail from '../../../modules/Image';
import ModalFileManager from "../../ModalFileManager";
import CreateFolderModal from "../../CreateFolderModal";
import DetailPopup from "../../DetailPopup";
import UploadProgress from "../../UploadProgress";
import ConfirmModalRemoveFile from "../../ConfirmModalRemoveFile";
export default {
  props: ['value', 'field', 'isReadonly','classes','url'],
  components: {
    ImageDetail: ImageDetail,
  },
  methods: {
    openModalFilemanager() {
      this.$emit('open-modal');
    },
  },
};
</script>

<style scoped>
.form-input-bordered-l {
  background-color: var(--white);
  border-width: 1px;
  border-color: var(--60);
  padding-left: 0.75rem;
  padding-right: 0.75rem;
  color: var(--80);
  border-radius: 0.5rem;
  -webkit-box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.05);
  border-bottom-right-radius: initial;
  border-top-right-radius: initial;
}

.filemanager-open {
  border-color: var(--60);
}
</style>
