<template>
    <portal to="portal-filemanager" name="Rename Modal" transition="fade-transition">
        <modal v-if="active" @modal-close="handleClose">
            <div class="bg-white rounded-lg shadow-lg " style="width: 600px;">
                <div class="p-8">

                    <template v-if="type == 'folder'">
                        <heading :level="2" class="mb-6">
                            {{ __('Rename folder') }}
                        </heading>
                        <input type="text" class="w-full h-full form-control form-input form-input-bordered py-3" :placeholder="name" v-model="name" autofocus required v-on:keyup.enter="renamePath">
                    </template>

                    <template v-else>
                        <heading :level="2" class="mb-6">
                            {{ __('Rename file') }}
                        </heading>

                        <div class="flex flex-wrap items-stretch w-full mb-4 relative">
                            <input type="text" class="flex-shrink flex-grow flex-auto h-full form-control form-input form-input-bordered-left py-3" :placeholder="nameWithoutExtension" v-model="nameWithoutExtension" autofocus required v-on:keyup.enter="renamePath">
                            <div class="flex -mr-px">
                                <span class="flex items-center leading-normal bg-50 rounded rounded-l-none form-input-bordered-right px-3 whitespace-no-wrap text-grey-dark text-sm">{{ extension }}</span>
                            </div>  
                        </div>
                            
                        <p class="my-2 text-danger" v-if="error">{{ errorMsg }}</p>

                    </template>
                    
                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button" data-testid="cancel-button" @click.prevent="cancelRename" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{ __('Cancel') }}</button>
                        <button ref="confirmButton" data-testid="confirm-button" :disabled="isSaving" @click.prevent="renamePath" class="btn btn-default btn-primary" :class="{ 'cursor-not-allowed': isSaving, 'opacity-50': isSaving }">
                            <span v-if="isSaving">{{ __('Renaming') }}</span>
                            <span v-else>{{ __('Rename') }}</span>
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
        //
    },

    data: () => ({
        active: false,
        name: null,
        type: null,
        path: null,
        error: false,
        errorMsg: '',
        isSaving: false,
        nameWithoutExtension: null,
    }),

    methods: {
        openModal(type, path) {
            this.type = type;
            this.path = path;
            this.name = path.replace(/^.*[\\/]/, '');
            this.active = true;
        },

        renamePath() {
            let nameToSave = null;

            if (this.type == 'folder') {
                if (this.name == null) {
                    this.error = true;
                    return false;
                }
                nameToSave = this.name;
            } else {
                if (this.nameWithoutExtension == null) {
                    this.error = true;
                    return false;
                }

                nameToSave = this.nameWithoutExtension + this.extension;
            }

            if (!nameToSave) {
                this.error = true;
                return false;
            }

            return api.rename(this.path, nameToSave).then(result => {
                this.error = false;
                this.name = null;
                if (result.success == true) {
                    this.$toasted.show(this.__('Renamed successfully'), { type: 'success' });
                    this.$emit('refresh', true);
                    this.cancelRename();
                } else {
                    this.error = true;
                    if (result.error) {
                        this.errorMsg = result.error;
                        this.$toasted.show(this.__('Error:') + ' ' + result.error, {
                            type: 'error',
                        });
                    } else {
                        this.errorMsg = this.__('The name is required');
                        this.$toasted.show(this.__('The name is required'), { type: 'error' });
                    }
                }
            });
        },

        cancelRename() {
            this.error = false;
            this.name = null;
            this.type = null;
            this.path = null;
            this.active = false;
            // this.$emit('closeCreateFolderModal', true);
        },

        handleClose() {
            this.cancelRename();
        },
    },

    computed: {
        extension() {
            if (this.type != 'folder') {
                var re = /(?:\.([^.]+))?$/;

                let ext = re.exec(this.name);

                if (ext) {
                    return ext[0];
                }
            }

            return '';
        },
    },

    watch: {
        extension(value) {
            if (value) {
                let tempName = this.name;
                this.nameWithoutExtension = tempName.replace(value, '');
            }
        },
    },
};
</script>

<style>
/* Scoped Styles */

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
