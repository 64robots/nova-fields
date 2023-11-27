<template>
  <div class="filemanager-image-block flex flex-col border-lg border-50 px-0 py-0">
    <div v-if="field.url || url" class="image-block card flex justify-center w-full h-5/6 px-2">
      <img v-if="!url && field.url" :src="field.url" class="image block w-full self-center" draggable="false">
      <img v-if="url" :src="url" class="image block w-full self-center" draggable="false">
    </div>
    <div v-else-if="!url && !field.url && field.value"
         class="image-block card flex justify-center w-full h-5/6 px-2 relative">
        <span v-if="fileType=='video'"
              class="absolute inset-0 flex items-center justify-center h-full w-full play-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="#fff">
  <path fill-rule="evenodd"
        d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
        clip-rule="evenodd"/>
</svg>
        </span>
      <img :src="field.value" class="image block w-full self-center" draggable="false">
    </div>
    <div class="items-stretch w-full mb-2 relative pt-1">
      <div class="flex -mr-px max-w-xs">
        <button v-if="selectMultiple" type="button"
                class="filemanager-open filemanager-open shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900"
                @click="openModalFilemanager">Add Multiple Media
        </button>
        <button v-else type="button"
                class="filemanager-open shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900"
                @click="openModalFilemanager">{{ __('Open FileManager') }}
        </button>
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
  props: ['value', 'field', 'isReadonly','classes','url','selectMultiple','fileType'],
  components: {
    ImageDetail: ImageDetail,
  },
  methods: {
    openModalFilemanager() {
      document.body.style.overflow = 'hidden';
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
