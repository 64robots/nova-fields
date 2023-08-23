<template>
  <portal to="portal-filemanager" name="Confirm Delete" transition="fade-transition">
    <Modal
        data-testid="confirm-action-modal"
        tabindex="-1"
        role="dialog"
        :closes-via-backdrop="true"
        @modal-close="handleClose"
        show="true"
        size="lg"
        v-if="active"
        class="z-100"
    >
      <div
          class="bg-white rounded-lg shadow-lg overflow-hidden"
      >
        <div class="p-8">
          <heading :level="2" class="mb-6">
            <template v-if="type == 'folder'">
              {{ __('Remove folder') }}: {{ name }}
            </template>
            <template v-else>
              {{ __('Remove file') }}: {{ name }}
            </template>
          </heading>
          <template v-if="type == 'folder'">
            <p class="text-80">{{__('Are you sure you want to remove this folder?')}}</p>
            <p class="text-sm text-80 mt-2">{{ __('Remember: The folder and all his contents will be delete from your storage') }}</p>
            <div class="deleteModalPassword">
              <label for="password">Enter Password : </label>
              <input type="password" name="password" class="w-full h-full form-control form-input form-input-bordered py-3" id="password" v-model="password">
              <span class="text-red-500" v-if="passwordMessage.length > 0">{{ passwordMessage }}</span>
            </div>
          </template>
          <template v-else>
            <p class="text-80">{{__('Are you sure you want to remove this file?')}}</p>
            <p class="text-sm text-80 mt-2">{{ __('Remember: The file will be delete from your storage') }}</p>
            <div class="deleteModalPassword">
              <label for="password">Enter Password : </label>
              <input type="password" name="password" class="w-full h-full form-control form-input form-input-bordered py-3" id="password" v-model="password">
              <span class="text-red-500" v-if="passwordMessage.length > 0">{{ passwordMessage }}</span>
            </div>
          </template>

        </div>

        <div class="bg-30 px-6 py-3 flex">
          <div class="ml-auto">
            <button dusk="cancel-upload-delete-button" type="button" data-testid="cancel-button" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>
            <button ref="confirmButton" data-testid="confirm-button" :disabled="isDeleting" @click.prevent="deleteData" class="shadow relative bg-red-500 hover:bg-red-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-red-200 dark:ring-red-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-red-500 hover:bg-red-400 text-white dark:text-gray-900" :class="{ 'cursor-not-allowed': isDeleting, 'opacity-50': isDeleting }">
              <span v-if="isDeleting">{{ __('Deleting') }}</span>
              <span v-else>{{ __('Delete') }}</span>
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
  data: () => ({
    active: false,
    name: null,
    type: null,
    path: null,
    error: false,
    errorMsg: '',
    password:'',
    isDeleted:false,
    isDeleting: false,
  }),
  emits: ['confirm', 'close'],
  methods: {
    openModal(type, path) {
      this.type = type;
      this.path = path;
      this.name = path.replace(/^.*[\\/]/, '');
      this.active = true;
      this.password = '';
    },
    handleClose() {
      let $this = this;
      this.isDeleting = false;
      this.$emit('close');
      setTimeout(function () {
        $this.password = '';
        $this.isDeleted = false;
        $this.name = null;
      },1500);
      this.active = false;
    },
    deleteData() {
      if(this.password.length == 0 ){
        Nova.error(this.__('Please enter valid password'), {
          type: 'error',
        });
      }else{
        let $this = this;
        this.isDeleting = true;
        api.validatePassword(this.password).then(result => {
          if (result == true) {
            if (this.type == 'folder') {
              this.deleteFolder();
            } else {
              this.deleteFile();
            }
            $this.isDeleted = true;
          } else {
            this.isDeleting = false;
            Nova.error(this.__('Your password is incorrect. Please enter valid password'), {
              type: 'error',
            });
          }
        });
      }
    },
    deleteFolder() {
      return api.removeDirectory(this.path).then(result => {
        this.error = false;
        if (result.success == true) {
          Nova.success(this.__('Deleted successfully'), { type: 'success' });
          this.$emit('refresh', true);
          this.handleClose();
        } else {
          this.error = true;
          if (result.error != null) {
            Nova.error(result.error, {
              type: 'error',
            });
          } else {
            Nova.error(this.__('Error deleting. Please, see your logs'), {
              type: 'error',
            });
          }
          this.isDeleting = false;
        }
      });
    },
    deleteFile() {
      return api.removeFile(this.path).then(result => {
        this.error = false;
        if (result.success == true) {
          Nova.success(this.__('Deleted successfully'), { type: 'success' });
          this.$emit('refresh', true);
          this.handleClose();
        } else {
          this.error = true;
          if (result.error != null) {
            Nova.error(result.error, {
              type: 'error',
            });
          } else {
            Nova.error(this.__('Error deleting. Please, see your logs'), {
              type: 'error',
            });
          }
          this.isDeleting = false;
        }
      });
    },
  },
};
</script>
<style>
.deleteModalPassword{
  display: flex;
  flex-direction: column;
  margin:20px 0;
}
.form-input-bordered-left {
  background-color: var(--white);
  border-width: 1px;
  border-color: var(--60);
  padding-left: 0.75rem;
  padding-right: 0.75rem;
  color: var(--80);
  border-top-left-radius: 0.5rem;
  border-bottom-left-radius: 0.5rem;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
  -webkit-box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.05);
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.05);
}
.form-input-bordered-right {
  border-width: 1px;
  border-color: var(--60);
  padding-left: 0.75rem;
  padding-right: 0.75rem;
  color: var(--80);
  border-left: 0;
  border-top-right-radius: 0.5rem;
  border-bottom-right-radius: 0.5rem;
  -webkit-box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.05);
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.05);
}
</style>
