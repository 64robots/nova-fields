<template>
  <portal to="portal-filemanager" name="Remove File" transition="fade-transition">
    <Modal
      data-testid="confirm-action-modal"
      tabindex="-1"
      role="dialog"
      :closes-via-backdrop="true"
      @modal-close="handleClose"
      show="true"
      class="z-100"
      v-if="active"
    >
      <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg" style="width: 600px;">
        <div class="p-8">
          <heading :level="2" class="mb-6">{{ __('Create folder') }}</heading>
          <input type="text"
                 class="w-full h-full form-control form-input form-input-bordered py-3 dark:bg-gray-800"
                 :placeholder="__('Write a folder name')" v-model="folderName" autofocus
                 required v-on:keyup.enter="createFolder">
          <p class="my-2 text-danger" v-if="error">{{ errorMsg }}</p>
        </div>

        <div class="bg-30 dark:bg-gray-700 px-6 py-3 flex rounded-lg shadow-lg">
          <div class="ml-auto">
            <button type="button" data-testid="cancel-button" @click.prevent="cancelCreate"
                    class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{
                __('Cancel')
              }}
            </button>
            <button ref="confirmButton" data-testid="confirm-button" :disabled="isSaving"
                    @click.prevent="createFolder" class="shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900"
                    :class="{ 'cursor-not-allowed': isSaving, 'opacity-50': isSaving }">
              <span v-if="isSaving">{{ __('Creating') }}</span>
              <span v-else>{{ __('Create') }}</span>
            </button>
          </div>
        </div>
      </div>
    </modal>
    <Modal
      data-testid="confirm-action-modal"
      tabindex="-1"
      role="dialog"
      :closes-via-backdrop="true"
      @modal-close="handleErrorPromptClose"
      show="true"
      class="z-100"
      v-if="errorPrompt"
    >
      <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg " style="width: 600px;">
        <div class="p-8">
          <heading :level="2" class="mb-6">{{ __('Warning!') }}</heading>
          <p>Oops! A folder with the same name already exists.</p>
        </div>

        <div class="bg-30 dark:bg-gray-700 rounded-lg shadow-lg px-6 py-3 flex">
          <div class="ml-auto">
            <button type="button" data-testid="cancel-button" @click.prevent="cancelCreatePrompt" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{ __('Cancel') }}</button>
            <button ref="confirmButton" data-testid="confirm-button" @click.prevent="confirmErrorPrompt" class="shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900" :class="{ 'cursor-not-allowed': isSaving, 'opacity-50': isSaving }">
              <span>{{ __('Confirm') }}</span>
            </button>
          </div>
        </div>
      </div>
    </modal>
  </portal>
</template>

<script>
import api from '../api';

export default {
  props: {
    active: {
      type: Boolean,
      default: false,
      required: true,
    },
    current: {
      type: String,
      default: '/',
      required: true,
    },
  },

  data: () => ({
    folderName: null,
    error: false,
    errorMsg: '',
    showUpload: false,
    isSaving: false,
    errorPrompt:false,
    isCreateSameName:false
  }),
  emits: ['confirm', 'close'],
  methods: {
    createFolder() {
      if (this.folderName == null) {
        Nova.error(this.__('The folder name is required'), {type: 'error'});
        return false;
      }
      this.isSaving = true;
      return api.createFolder(this.folderName, this.current,this.isCreateSameName).then(result => {
        this.error = false;
        this.errorPrompt = false;
        if (result == true) {
          this.folderName = null;
          this.isCreateSameName = false;
          this.$emit('closeCreateFolderModal', true);
          Nova.success(this.__('Folder created successfully'), {type: 'success'});
          this.isSaving = false;
          this.$emit('refresh', true);
        } else {
          this.error = true;
          if (result.error) {
            this.$emit('closeCreateFolderModal', true);
            this.errorPrompt = true;
            Nova.error(this.__('Error:') + ' ' + result.error, {
              type: 'error',
            });
          } else {
            this.folderName = null;
            this.errorMsg = this.__('The folder name is required');
            Nova.error(this.__('Error creating the folder'), {type: 'error'});
          }
          this.isSaving = false;
        }
      });
    },

    cancelCreate() {
      this.error = false;
      this.folderName = null;
      this.$emit('closeCreateFolderModal', true);
    },
    cancelCreatePrompt() {
      this.errorPrompt = false;
      this.cancelCreate();
    },

    handleClose() {
      this.cancelCreate();
    },
    handleErrorPromptClose() {
      this.errorPrompt = false;
    },
    confirmErrorPrompt(){
      this.isCreateSameName = true;
      this.createFolder();
    }
  },
};
</script>

<style>
/* Scoped Styles */
</style>
