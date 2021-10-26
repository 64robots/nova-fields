<template>
    <portal to="portal-filemanager" name="Remove Multi" transition="fade-transition">
        <modal v-if="active" @modal-close="handleClose">
            <div
                class="bg-white rounded-lg shadow-lg overflow-hidden"
                style="width: 460px"
            >
                <div class="p-8">
                    <heading :level="2" class="mb-6">
                        {{ __('Remove selected?') }}
                    </heading>
                    <p class="text-80">{{__('Are you sure you want to remove selected files or folders?')}}</p>
                    <p class="text-sm text-80 mt-2">{{ __('Remember: The file and folder and all his contents will be delete from your storage') }}</p>
                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button dusk="cancel-upload-delete-button" type="button" data-testid="cancel-button" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>
                        <button ref="confirmButton" data-testid="confirm-button" :disabled="isDeleting" @click.prevent="deleteData" class="btn btn-default btn-danger" :class="{ 'cursor-not-allowed': isDeleting, 'opacity-50': isDeleting }">
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
    props: {
        selectedFiles: {
            type: Array,
            required: true,
        },
    },

    data: () => ({
        active: false,
        error: false,
        errorMsg: '',
        isDeleting: false,
    }),

    methods: {
        openModal() {
            this.active = true;
        },

        handleClose() {
            this.active = false;
        },

        async deleteData() {
            this.isDeleting = true;

            for (const file of this.selectedFiles) {
                if (file.type == 'dir') {
                    await this.deleteFolder(file);
                } else {
                    await this.deleteFile(file);
                }
            }

            this.isDeleting = false;
            this.$emit('refresh', true);
            this.handleClose();
        },

        deleteFolder(file) {
            return api.removeDirectory(file.path).then(result => {
                this.error = false;
                if (result == true) {
                    this.$toasted.show(this.__('Deleted successfully'), { type: 'success' });
                } else {
                    this.error = true;
                    if (result.error) {
                        this.errorMsg = result.error;
                        this.$toasted.show(this.__('Error:') + ' ' + result.error, {
                            type: 'error',
                        });
                    } else {
                        this.$toasted.show(this.__('Error deleting. Please, see your logs'), {
                            type: 'error',
                        });
                    }
                }
            });
        },

        deleteFile(file) {
            return api.removeFile(file.path).then(result => {
                this.error = false;
                if (result == true) {
                    this.$toasted.show(this.__('Deleted successfully'), { type: 'success' });
                } else {
                    this.error = true;
                    if (result.error) {
                        this.errorMsg = result.error;
                        this.$toasted.show(this.__('Error:') + ' ' + result.error, {
                            type: 'error',
                        });
                    } else {
                        this.$toasted.show(this.__('Error deleting. Please, see your logs'), {
                            type: 'error',
                        });
                    }
                }
            });
        },
    },
};
</script>
