<template>
    <div  ref="filemanager-container">
        <heading class="mb-6">{{ __('Filemanager') }}</heading>

        <portal-target name="portal-filemanager"></portal-target>

        <create-folder :active="showCreateFolder" :current="currentPath" v-on:closeCreateFolderModal="closeModalCreateFolder" v-on:refresh="refreshCurrent" />

        <rename-modal ref="renameModal" v-on:refresh="refreshCurrent" />

        <confirm-modal-delete ref="confirmDelete" v-on:refresh="refreshCurrent" />

        <confirm-modal-multi-delete ref="confirmMultiDelete" :selected-files="selectedFiles" v-on:refresh="refreshCurrent" />

        <div class="card relative" id="filemanager-manager">

            <div class="p-3 flex items-center justify-between border-b border-50">
                <div class="w-full flex flex-wrap">
                    <div class="w-2/3 flex flex-wrap justify-start">
                        <button @click="refreshCurrent" class="btn btn-default btn-small btn-primary text-white mr-3" :class="{'rotate': loadingfiles}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M6 18.7V21a1 1 0 0 1-2 0v-5a1 1 0 0 1 1-1h5a1 1 0 1 1 0 2H7.1A7 7 0 0 0 19 12a1 1 0 1 1 2 0 9 9 0 0 1-15 6.7zM18 5.3V3a1 1 0 0 1 2 0v5a1 1 0 0 1-1 1h-5a1 1 0 0 1 0-2h2.9A7 7 0 0 0 5 12a1 1 0 1 1-2 0 9 9 0 0 1 15-6.7z"/></svg>
                        </button>

                        <label v-if="buttons.upload_button" class="manual_upload cursor-pointer">
                            <div @click="showUpload = !showUpload" class="btn btn-default btn-primary mr-3">
                                {{ __('Upload') }}
                            </div>
                            <input type="file" multiple="true" @change="uploadFilesByButton"/>
                        </label>

                        <button v-if="buttons.create_folder" @click="showModalCreateFolder" class="btn btn-default btn-primary mr-3">
                            {{ __('Create folder') }}
                        </button>

                        <button v-if="view == 'list'" @click="viewAs('grid')" class="btn btn-default btn-small btn-primary text-white mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M5 3h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5h-4zM5 13h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4h-4z"/></svg>
                        </button>
                        <button v-if="view == 'grid'" @click="viewAs('list')" class="btn btn-default btn-small btn-primary text-white mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20"><path d="M1 4h2v2H1V4zm4 0h14v2H5V4zM1 9h2v2H1V9zm4 0h14v2H5V9zm-4 5h2v2H1v-2zm4 0h14v2H5v-2z"/></svg>
                        </button>

                        <button v-if="buttons.select_multiple" type="button" class="btn btn-default btn-primary flex items-center text-white mr-3 px-3"  @click="multiSelecting=!multiSelecting">
                            <svg class="w-6 h-6 fill-current pt-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="6.5" cy="6.75" r="1.5"/><path d="M17.75 10.25A6.25 6.25 0 1024 16.5a6.257 6.257 0 00-6.25-6.25zm3.163 5.028L18.13 18.99a1.46 1.46 0 01-1.076.583h-.107a1.454 1.454 0 01-1.035-.434l-1.435-1.436a.75.75 0 011.06-1.06l1.234 1.234a.251.251 0 00.2.072.247.247 0 00.182-.1l2.563-3.475a.751.751 0 111.2.9z"/><path d="M0 3.25v7a1.981 1.981 0 001.957 2h7.858a1 1 0 000-2H2.5a.5.5 0 01-.5-.5l-.037-6a.5.5 0 01.5-.5H21.5a.5.5 0 01.5.5v3.5a1 1 0 002 0v-4a1.981 1.981 0 00-1.956-2H1.957A1.982 1.982 0 000 3.25z"/><circle cx="12.5" cy="6.75" r="1.5"/></svg>
                            <span v-if="selectedFiles.length > 0" class="ml-2 text-sm">{{ selectedFiles.length }}</span>
                        </button>
                        <button v-if="multiSelecting && selectedFiles.length > 0" type="button" class="btn btn-default btn-small btn-danger text-white mr-3" @click="openMultiDeleteModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                        </button>
                    </div>

                    <!-- Search -->
                    <div class="w-1/3 flex flex-wrap justify-end">
                        <div class="relative w-1/2 max-w-xs pr-3">
                            <template v-if="showFilters">
                                <select class="pl-search form-control form-input form-input-bordered w-full" v-model="filterBy" @change="filterFiles">
                                    <option value="">{{ __('Filter by ...') }}</option>
                                    <option v-for="(filter, key) in filters" :key="'filter_' + key" :value="key">{{ key }}</option>
                                </select>
                            </template>
                        </div>

                        <div class="relative w-1/2 max-w-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="search" role="presentation" class="fill-current absolute search-icon-center ml-3 text-70"><path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path></svg>
                            <input v-on:input="searchItems" v-model="search" dusk="filemanager-search" type="search" :placeholder="this.__('Search')" class="pl-search form-control form-input form-input-bordered w-full">
                        </div>
                    </div>
                </div>
            </div>

            <manager
                ref="manager"
                :files="files"
                :path="path"
                :current="currentPath"
                :parent="parent"
                :view="view"
                :loading="loadingfiles"
                :search="search"
                :filters="filteredExtensions"
                :multi-selecting="multiSelecting"
                :selected-files="selectedFiles"
                :buttons="buttons"
                v-on:goToFolderManager="goToFolder"
                v-on:goToFolderManagerNav="goToFolderNav"
                v-on:refresh="refreshCurrent"
                v-on:uploadFiles="uploadFiles"
                v-on:showInfoItem="showInfoItem"
                v-on:rename="openRenameModal"
                v-on:delete="openDeleteModal"
                v-on:select="select"
            />

            <DetailPopup
                ref="detailPopup"
                :info="info"
                :active="activeInfo"
                :buttons="buttons"
                v-on:closePreview="closePreview"
                v-on:refresh="refreshCurrent"
                v-on:rename="fileRenamed"
            >
            </DetailPopup>

            <UploadProgress ref="uploader" :current="currentPath" v-on:removeFile="removeFileFromUpload"></UploadProgress>
        </div>
    </div>
</template>

<script>
import URI from 'urijs';
import _ from 'lodash';
import api from '../api';
import CreateFolderModal from './CreateFolderModal';
import ConfirmModalDelete from './ConfirmModalDelete';
import ConfirmModalMultiDelete from './ConfirmModalMultiDelete';
import RenameModal from './RenameModal';
import DetailPopup from './DetailPopup';
import UploadProgress from './UploadProgress';
import Manager from './Manager';

export default {
    name: 'Filemanager',

    components: {
        'create-folder': CreateFolderModal,
        'rename-modal': RenameModal,
        'confirm-modal-delete': ConfirmModalDelete,
        'confirm-modal-multi-delete': ConfirmModalMultiDelete,
        manager: Manager,
        DetailPopup: DetailPopup,
        UploadProgress: UploadProgress,
    },

    data: () => ({
        loaded: false,
        loadingfiles: false,
        activeDisk: null,
        activeDiskBackups: [],
        backupStatusses: [],
        showUpload: false,
        showCreateFolder: false,
        currentPath: '/',
        files: [],
        parent: {},
        path: [],
        view: 'grid',
        info: {},
        filesToUpload: [],
        uploadType: null,
        folderUploadedName: null,
        activeInfo: false,
        search: '',
        filters: [],
        filterBy: '',
        filteredExtensions: [],
        showFilters: false,
        multiSelecting: false,
        selectedFiles: [], // { type: 'folder/file', path: '...'' }
        buttons: [],
    }),

    async created() {
        let currentUrl = new URI();

        if (currentUrl.hasQuery('path')) {
            let params = currentUrl.query(true);
            this.currentPath = params.path;
        }

        if (localStorage.getItem('nova-filemanager-view')) {
            let viewS = localStorage.getItem('nova-filemanager-view');
            if (['grid', 'list'].includes(viewS)) {
                this.view = viewS;
            } else {
                localStorage.setItem('nova-filemanager-view', 'grid');
            }
        }

        await this.getData(this.currentPath);

        this.loaded = true;
    },

    methods: {
        getData(pathToList) {
            this.files = [];
            this.parent = {};
            this.path = [];
            this.loadingfiles = true;
            return api
                .getData(pathToList)
                .then(result => {
                    this.files = result.files;
                    this.path = result.path;
                    this.filters = result.filters;
                    this.parent = result.parent;
                    this.buttons = result.buttons;

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
            this.showCreateFolder = true;
        },
        closeModalCreateFolder() {
            this.showCreateFolder = false;
        },

        refreshCurrent() {
            this.refreshMultiSelected();
            this.getData(this.currentPath);
        },

        refreshCurrentAfterUpload() {
            this.getData(this.currentPath);
            this.filesToUpload = [];
        },

        goToFolder(path) {
            this.getData(path);
            this.currentPath = path;
            history.pushState(null, null, '?path=' + path);
        },

        goToFolderNav(path) {
            this.getData(path);
            this.currentPath = path;
            if (this.currentPath == '/') {
                history.pushState(null, null, '?path=' + path);
            } else {
                history.pushState(null, null, '?path=' + path);
            }
        },

        changeToList() {
            this.view = 'list';
            localStorage.setItem('view', 'list');
        },

        changeToGrid() {
            this.view = 'grid';
            localStorage.setItem('view', 'grid');
        },

        showInfoItem(item) {
            this.activeInfo = true;
            this.info = item;
        },

        closePreview() {
            this.info = {};
            this.activeInfo = false;
            this.popupDetailsLoaded = false;
        },

        fileRenamed(item) {
            this.info = item;
        },

        viewAs(type) {
            this.view = type;
            localStorage.setItem('nova-filemanager-view', type);
        },

        uploadFiles(files, type, firstFolderName) {
            this.filesToUpload = files;
            this.uploadType = type;
            this.folderUploadedName = firstFolderName;
            this.$refs.uploader.startUploadingFiles(files, type);
        },

        removeFileFromUpload(uploadedFileId) {
            let index = this.filesToUpload.map(item => item.id).indexOf(uploadedFileId);

            this.$delete(this.filesToUpload, index);
            if (this.filesToUpload.length === 0) {
                if (this.uploadType == 'folders') {
                    this.callFolderEvent(this.folderUploadedName);
                }

                this.folderUploadedName = null;
                this.uploadType = null;
                this.refreshCurrent();
            }
        },

        uploadFilesByButton(e) {
            this.$refs.manager.uploadFiles({ files: Array.from(e.target.files) }, 'files');
        },

        openRenameModal(type, path) {
            this.$refs.renameModal.openModal(type, path);
        },

        openDeleteModal(type, path) {
            this.$refs.confirmDelete.openModal(type, path);
        },

        openMultiDeleteModal() {
            this.$refs.confirmMultiDelete.openModal();
        },

        refreshMultiSelected() {
            this.multiSelecting = false;
            this.selectedFiles = [];
        },

        callFolderEvent(path) {
            api.eventFolderUploaded(this.currentPath + '/' + path);
        },

        filterFiles() {
            let extensions = _.get(this.filters, this.filterBy);

            if (extensions == null) {
                this.filteredExtensions = [];
            }

            if (extensions != null && extensions.length > 0) {
                this.filteredExtensions = extensions;
            }
        },

        searchItems: _.debounce(function(e) {
            this.search = e.target.value;
        }, 300),

        select(file) {
            const findIndex = _.findIndex(this.selectedFiles, file);

            if (findIndex >= 0) {
                this.selectedFiles.splice(findIndex, 1);
                return;
            }
            this.selectedFiles.push(file);
        },
    },

    computed: {
        cssFilemenagerContainer() {
            if (this.cssDragAndDrop == 'inside') {
                return 'bg-20';
            }

            if (this.cssDragAndDrop == 'outside') {
                return '';
            }
            return '';
        },
    },

    watch: {
        currentPath() {
            this.multiSelecting = false;
            this.selectedFiles = [];
        },

        filters() {
            if (this.filters) {
                let size = _.size(this.filters);
                if (size > 1) {
                    this.showFilters = true;
                    return true;
                }
            }

            this.showFilters = false;
        },
    },
};
</script>

<style scoped lang="scss">
.manual_upload > input[type='file'] {
    display: none;
}

.btn-small {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    padding-top: 0.3rem;
    fill: currentColor;
}

.rotate {
    svg {
        animation: fa-spin 2s infinite linear;
    }
}

@keyframes fa-spin {
    0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(359deg);
        transform: rotate(359deg);
    }
}
</style>
