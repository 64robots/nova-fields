<template>
    <portal to="portal-filemanager" name="Remove File" transition="fade-transition">
        <modal v-if="active"  @modal-close="handleClose" class="z-100">
            <div class="bg-white rounded-lg shadow-lg " style="width: 600px;">
                <div class="p-8">
                    <heading :level="2" class="mb-6">{{ __('Create folder') }}</heading>
                    <input type="text" class="w-full h-full form-control form-input form-input-bordered py-3" :placeholder="__('Write a folder name')" v-model="folderName" autofocus required v-on:keyup.enter="createFolder">
                    <p class="my-2 text-danger" v-if="error">{{ errorMsg }}</p>
                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button" data-testid="cancel-button" @click.prevent="cancelCreate" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{ __('Cancel') }}</button>
                        <button ref="confirmButton" data-testid="confirm-button" :disabled="isSaving" @click.prevent="createFolder" class="btn btn-default btn-primary" :class="{ 'cursor-not-allowed': isSaving, 'opacity-50': isSaving }">
                            <span v-if="isSaving">{{ __('Creating') }}</span>
                            <span v-else>{{ __('Create') }}</span>
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
    }),

    methods: {
        createFolder() {
            if (this.folderName == null) {
                this.errorMsg = this.__('The folder name is required');
                this.error = true;
                return false;
            }

            return api.createFolder(this.folderName, this.current).then(result => {
                this.error = false;
                this.folderName = null;
                if (result == true) {
                    this.$emit('closeCreateFolderModal', true);
                    this.$toasted.show(this.__('Folder created successfully'), { type: 'success' });
                    this.$emit('refresh', true);
                } else {
                    this.error = true;
                    if (result.error) {
                        this.errorMsg = result.error;
                        this.$toasted.show(this.__('Error:') + ' ' + result.error, {
                            type: 'error',
                        });
                    } else {
                        this.errorMsg = this.__('The folder name is required');
                        this.$toasted.show(this.__('Error creating the folder'), { type: 'error' });
                    }
                }
            });
        },

        cancelCreate() {
            this.error = false;
            this.folderName = null;
            this.$emit('closeCreateFolderModal', true);
        },

        handleClose() {
            this.cancelCreate();
        },
    },
};
</script>

<style>
/* Scoped Styles */
</style>
