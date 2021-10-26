<template>
    <portal to="modals" name="Modal FileManager" transition="fade-transition">
        <modal v-if="active">

            <portal-target name="portal-filemanager">

            </portal-target>

            <div class="bg-white rounded-lg shadow-lg" style="width: 900px;">
                <div class="bg-30 flex flex-wrap border-b border-70">
                    <div class="w-3/4 px-4 py-3 ">
                        {{ __('FileManager') }}
                    </div>

                    <div class="w-1/4 flex flex-wrap justify-end">
                        <button class="btn buttons-actions" v-on:click="closeModal">X</button>
                    </div>
                </div>

                <div class="flex flex-wrap">
                    <div class="card relative w-full">
                        <div class="p-3 flex flex-wrap items-center border-b border-50">
                            <div class="w-auto flex flex-wrap justify-start">
                                <label v-if="buttons.upload_button" class="manual_upload cursor-pointer">
                                    <div @click="showUpload = !showUpload" class="btn btn-default btn-primary mr-3">
                                        {{ __('Upload') }}
                                    </div>
                                    <input type="file" multiple="true" @change="uploadFilesByButton"/>
                                </label>

                                <button  v-if="buttons.create_folder" @click="showModalCreateFolder" class="btn btn-default btn-primary mr-3">
                                    {{ __('Create folder') }}
                                </button>

                                <button v-if="view == 'list'" @click="viewAs('grid')" class="btn btn-default btn-small btn-primary text-white mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M5 3h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5h-4zM5 13h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4h-4z"/></svg>
                                </button>

                                <button v-if="view == 'grid'" @click="viewAs('list')" class="btn btn-default btn-small btn-primary text-white mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20"><path d="M1 4h2v2H1V4zm4 0h14v2H5V4zM1 9h2v2H1V9zm4 0h14v2H5V9zm-4 5h2v2H1v-2zm4 0h14v2H5v-2z"/></svg>
                                </button>
                            </div>

                            <!-- Search -->
                            <div class="w-auto flex flex-1 flex-wrap justify-end">
                                <div class="relative w-1/3 max-w-xs mr-3">
                                    <div class="relative">
                                        <div class="relative">
                                            <template v-if="showFilters">
                                                <select class="pl-search form-control form-input form-input-bordered w-full" v-model="filterBy">
                                                    <option value>{{ __('Filter by ...') }}</option>
                                                    <option v-for="(filter, key) in filters" :key="'filter_' + key" :value="key">{{ key }}</option>
                                                </select>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative w-1/2 max-w-xs">
                                    <div class="relative">
                                        <div class="relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="search" role="presentation" class="fill-current absolute search-icon-center ml-3 text-70"><path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path></svg>
                                            <input v-on:input="searchItems" v-model="search" dusk="filemanager-search" type="search" :placeholder="this.__('Search')" class="pl-search form-control form-input form-input-bordered w-full">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <manager
                            ref="manager"
                            :home="home"
                            :files="files"
                            :path="path"
                            :current="currentPath"
                            :parent="parent"
                            :view="view"
                            :selector="value"
                            :popupLoaded="true"
                            :loading="loadingfiles"
                            :search="search"
                            :filter="filter"
                            :filters="filteredExtensions"
                            :buttons="buttons"
                            v-on:goToFolderManager="goToFolder"
                            v-on:goToFolderManagerNav="goToFolderNav"
                            v-on:refresh="refreshCurrent"
                            v-on:selectFile="setFileValue"
                            v-on:showInfoItem="showInfoItem"
                            v-on:uploadFiles="uploadFiles"
                            v-on:rename="openRenameModal"
                            v-on:delete="openDeleteModal"
                        />

                        <rename-modal ref="renameModal" v-on:refresh="refreshCurrent" />

                        <confirm-modal-delete ref="confirmDelete" v-on:refresh="refreshCurrent" />
                    </div>
                </div>
            </div>
        </modal>
    </portal>
</template>

<script>
import _ from 'lodash';
import URI from 'urijs';
import api from '../api';
import Manager from './Manager';
import Upload from './Upload';
import UploadProgress from './UploadProgress';

import ConfirmModalDelete from './ConfirmModalDelete';
import RenameModal from './RenameModal';

export default {
    props: {
        resource: {
            type: String,
            required: true,
        },
        name: {
            type: String,
            required: true,
        },
        active: {
            type: Boolean,
            default: false,
            required: true,
        },
        value: {
            type: String,
            required: true,
        },
        currentPath: {
            type: String,
            required: true,
        },
        defaultFolder: {
            type: String,
            required: true,
        },
        home: {
            type: String,
            required: false,
            default: '/',
        },
        filter: {
            type: String,
            required: false,
            default: null,
        },
        buttons: {
            default: () => [],
            required: true,
        },
        rules: {
            type: Array,
            default: () => [],
            required: false,
        },
    },

    components: {
        ConfirmModalDelete,
        Manager,
        RenameModal,
        Upload,
        UploadProgress,
    },

    data: () => ({
        loaded: false,
        loadingfiles: false,
        activeDisk: null,
        activeDiskBackups: [],
        backupStatusses: [],
        showUpload: false,
        showCreateFolder: false,
        currentPathFolder: this.currentPath,
        files: [],
        parent: {},
        path: [],
        view: 'grid',
        filesToUpload: [],
        firstTime: true,
        search: '',
        filters: [],
        filterBy: '',
        showFilters: false,
    }),

    computed: {
        filteredExtensions() {
            const filter = _.get(this.filters, this.filterBy);

            if (filter) {
                return filter;
            }

            return [];
        },
    },

    methods: {
        getData(folder) {
            this.files = [];
            this.parent = {};
            this.path = [];
            this.loadingfiles = true;

            api.getDataField(this.resource, this.name, folder, this.filter)
                .then(result => {
                    this.files = result.files;
                    this.path = result.path;
                    this.filters = result.filters;

                    if (folder != this.defaultFolder) {
                        this.parent = result.parent;
                    }

                    this.loadingfiles = false;
                })
                .catch(() => {
                    this.loadingfiles = false;
                    this.filters = [];
                    this.$toasted.show(
                        this.__('Error reading the folder. Please check your logs'),
                        { type: 'error' }
                    );
                });
        },

        showModalCreateFolder() {
            this.$emit('open-modal');
        },

        refreshCurrent() {
            this.getData(this.currentPathFolder);
        },

        goToFolder(path) {
            let pathDefault = this.defaultFolder.split('/');

            if (path == pathDefault[0]) {
                this.getData(this.defaultFolder);
                this.currentPathFolder = this.defaultFolder;
            } else {
                this.getData(path);
                this.currentPathFolder = path;
            }
        },

        goToFolderNav(path) {
            this.getData(path);
            this.currentPathFolder = path;
            if (this.currentPathFolder == '/') {
                // history.pushState(null, null, '?path=' + path);
            } else {
                // history.pushState(null, null, '?path=' + path);
            }
        },

        closeModal() {
            this.$emit('close-modal');
        },

        viewAs(type) {
            this.view = type;
            localStorage.setItem('nova-filemanager-view', type);
        },

        setFileValue(file) {
            this.closeModal();
            this.$emit('setFileValue', file);
        },

        uploadFilesByButton(e) {
            this.$refs.manager.uploadFiles({ files: Array.from(e.target.files) });
        },

        showInfoItem(item) {
            this.$emit('showInfoItem', item);
        },

        uploadFiles(files, type) {
            this.$emit('uploadFiles', files, type);
        },

        openRenameModal(type, path) {
            this.$refs.renameModal.openModal(type, path);
        },

        openDeleteModal(type, path) {
            this.$refs.confirmDelete.openModal(type, path);
        },

        searchItems: _.debounce(function(e) {
            this.search = e.target.value;
        }, 300),
    },

    watch: {
        active(val) {
            if (val) {
                const currentUrl = new URI();

                if (currentUrl.hasQuery('path')) {
                    const params = currentUrl.query(true);

                    this.currentPath = params.path;
                }

                this.getData(this.currentPath);
            }
        },

        currentPathFolder(val) {
            this.$emit('update-current-path', val);
        },

        filters() {
            if (this.filters) {
                const size = _.size(this.filters);

                if (size > 1) {
                    this.showFilters = true;

                    return true;
                }
            }

            this.showFilters = false;
        },
    },

    created() {
        if (localStorage.getItem('nova-filemanager-view')) {
            const view = localStorage.getItem('nova-filemanager-view');

            if (['grid', 'list'].includes(view)) {
                this.view = view;
            } else {
                localStorage.setItem('nova-filemanager-view', 'grid');
            }
        }
    },
};
</script>

<style scoped>
.buttons-actions {
    padding-left: 1rem;
    padding-right: 1rem;
    border-left: 1px solid rgb(221, 221, 221);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.btn-small {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    padding-top: 0.3rem;
    fill: currentColor;
}

.manual_upload > input[type='file'] {
    display: none;
}
</style>
