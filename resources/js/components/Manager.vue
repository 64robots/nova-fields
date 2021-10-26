<template>
    <div ref="fileManagerContainer" id="filemanager-manager-container" class="p-3"  :class="cssFilemenagerContainer" v-cloak>
        <nav class="bg-grey-light rounded font-sans w-full m-4">
            <ol class="list-reset flex text-grey-dark" >
                <li><span class="text-blue font-bold cursor-pointer" @click="goToFolderNav(home)">{{ __('Home') }}</span></li>
                <li v-if="pathsLength > 0"><span class="mx-2">/</span></li>

                <template v-for="(folder ,index) in path">
                    <template v-if="checkIsLastItem(index)">
                        <li  v-bind:key="index"><span href="#" class="text-blue">{{ folder.name }}</span></li>
                    </template>
                    <template v-else>
                        <li  v-bind:key="index"><span href="#" class="text-blue cursor-pointer font-bold" @click="goToFolder(folder.path)">{{ folder.name }}</span></li>
                        <li  v-bind:key="index+'_separator'"><span class="mx-2">/</span></li>
                    </template>
                </template>
            </ol>
        </nav>

        <transition name="fade">
            <template v-if="uploadingFiles">
                <div class="px-2 overflow-y-auto files">
                    <div class="drop-files flex flex-wrap items-center border-2 border-primary border-dashed -mx-2">
                        <div class="w-full text-lg text-center my-4">
                            {{ __('Drop your files here!') }}
                        </div>
                    </div>
                </div>
            </template>

            <div v-else class="px-2 overflow-y-auto files">
                <div class="flex flex-wrap -mx-2">
                    <template v-if="files.error">
                        <div class="w-full text-lg text-center my-4">
                            {{ __('You don\'t have permissions to view this folder') }}
                        </div>
                    </template>

                    <template v-if="loading">
                        <div class="w-full text-lg text-center my-4">
                            <loading />
                        </div>
                    </template>

                    <template v-else-if="!files.length">
                        <div class="w-full text-lg text-center my-4">
                            {{ __(`No ${filter || 'files or folders'} in current directory`) }}<br><br>
                            <button v-if="buttons.delete_folder && !filter" class="btn btn-default btn-danger" @click="removeDirectory">
                                {{ __('Remove directory') }}
                            </button>
                        </div>
                    </template>

                    <template v-if="!files.error">
                        <template v-if="view == 'grid'">
                            <template v-if="!files.error">
                                <template v-if="parent.id">
                                    <div :class="filemanagerClass" :key="parent.id" >
                                        <Folder v-drag-and-drop:folder :ref="'folder_' + parent.id" :file="parent" :data-key="parent.id" class="h-40 folder-item" :class="{'loading': loadingInfo}" v-on:goToFolderEvent="goToFolder" />
                                    </div>
                                </template>

                                <template v-for="file in filteredFiles">
                                    <div :class="filemanagerClass" :key="file.id" >
                                        <template v-if="file.type == 'file'">
                                            <ImageLoader
                                                v-drag-and-drop:file
                                                :ref="'file_' + file.id"
                                                :file="file"
                                                :data-key="file.id"
                                                :multi-selecting="multiSelecting"
                                                :selected-files="selectedFiles"
                                                :delete-permission="buttons.delete_file"
                                                :rename-permission="buttons.rename_file"
                                                class="h-40 file-item"
                                                @missing="(value) => missing = value"
                                                v-on:showInfo="showInfo"
                                                v-on:rename="rename"
                                                v-on:delete="deleteData"
                                                v-on:select="select"
                                            />
                                        </template>
                                        <template v-if="file.type == 'dir'">
                                            <Folder
                                                v-drag-and-drop:folder
                                                :ref="'folder_' + file.id"
                                                :file="file"
                                                :data-key="file.id"
                                                :multi-selecting="multiSelecting"
                                                :selected-files="selectedFiles"
                                                :delete-permission="buttons.delete_folder"
                                                :rename-permission="buttons.rename_folder"
                                                class="h-40 folder-item"
                                                :class="{'loading': loadingInfo}"
                                                v-on:goToFolderEvent="goToFolder"
                                                v-on:rename="rename"
                                                v-on:delete="deleteData"
                                                v-on:select="select"
                                            />
                                        </template>
                                    </div>
                                </template>
                            </template>
                        </template>

                        <template v-if="view == 'list'">

                            <table class="table custom-table w-full" v-if="files.length > 0">
                                <thead>
                                    <tr>
                                        <th v-if="multiSelecting" class="w-8"></th>
                                        <th class="w-16">
                                            {{ __('Type') }}
                                        </th>
                                        <th class="text-left">
                                            {{ __('Name') }}
                                        </th>
                                        <th class="text-left">
                                            {{ __('Size') }}
                                        </th>
                                        <th class="text-left">
                                            {{ __('Last Modification') }}
                                        </th>
                                        <th class="text-left">
                                            {{ __('Options') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <template v-if="parent.id">

                                        <Folder
                                            :ref="'folder_' + parent.id"
                                            :key="parent.id"
                                            :file="parent"
                                            :view="view"
                                            class="folder-item"
                                            :class="{'loading': loadingInfo}"
                                            v-on:goToFolderEvent="goToFolder"
                                        />

                                    </template>


                                    <template  v-for="file in filteredFiles">
                                        <template v-if="file.type == 'dir'">
                                            <Folder :key="file.id"
                                                    :data-key="file.id"
                                                    :file="file"
                                                    :view="view"
                                                    :multi-selecting="multiSelecting"
                                                    :selected-files="selectedFiles"
                                                    :delete-permission="buttons.delete_folder"
                                                    :rename-permission="buttons.rename_folder"
                                                    class="folder-item"
                                                    :class="{'loading': loadingInfo}"
                                                    v-on:goToFolderEvent="goToFolder"
                                                    v-on:rename="rename"
                                                    v-on:delete="deleteData"
                                                    v-on:select="select"
                                            />
                                        </template>
                                        <template v-if="file.type == 'file'">
                                            <ImageLoader
                                                :key="file.id"
                                                :data-key="file.id"
                                                :file="file"
                                                :view="view"
                                                :multi-selecting="multiSelecting"
                                                :selected-files="selectedFiles"
                                                :delete-permission="buttons.delete_file"
                                                :rename-permission="buttons.rename_file"
                                                class="file-item"
                                                :class="{'loading': loadingInfo}"
                                                @missing="(value) => missing = value"
                                                v-on:showInfo="showInfo"
                                                v-on:rename="rename"
                                                v-on:delete="deleteData"
                                                v-on:select="select"
                                            />
                                        </template>
                                    </template>
                                </tbody>
                            </table>
                        </template>
                    </template>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import _ from 'lodash';
import filesize from 'filesize';
import MD5 from 'md5';
import api from '../api';
import ImageLoader from '../modules/ImageLoader';
import Folder from '../modules/Folder';
import Loading from './Loading';
import { DragAndDrop } from '../tools/DragAndDrop';

export default {
    name: 'Manager',

    components: {
        ImageLoader,
        Folder,
        Loading,
    },

    props: {
        files: {
            default: () => [],
            required: true,
        },
        path: {
            default: () => [],
            required: true,
        },
        current: {
            type: String,
            default: '/',
            required: true,
        },
        parent: {
            type: Object,
            required: true,
        },
        loading: {
            type: Boolean,
            default: false,
            required: true,
        },
        popupLoaded: {
            type: Boolean,
            default: false,
            required: false,
        },
        view: {
            type: String,
            default: 'grid',
            required: false,
        },
        home: {
            type: String,
            required: false,
            default: '/',
        },
        search: {
            type: String,
            required: false,
            default: '',
        },
        filter: {
            type: String,
            required: false,
        },
        filters: {
            type: Array,
            required: false,
            default: () => [],
        },
        multiSelecting: {
            type: Boolean,
            default: false,
            required: false,
        },
        selectedFiles: {
            type: Array,
            default: () => [],
            required: false,
        },
        buttons: {
            default: () => [],
            required: true,
        },
    },

    data: () => ({
        info: {},
        activeInfo: false,
        eventsLoaded: false,
        cssDragAndDrop: null,
        filesToUpload: [],
        filesFromDrop: [],
        filesDropProcessed: false,
        loadingInfo: false,
        busy: false,
        currentDraggedFile: null,
        uploadingFiles: false,
        firstUploadFolder: null,
    }),

    directives: {
        'drag-and-drop': DragAndDrop,
    },

    methods: {
        goToFolder(path) {
            this.$emit('goToFolderManager', path);
        },

        goToFolderNav(path) {
            this.$emit('goToFolderManagerNav', path);
        },

        checkIsLastItem(index) {
            return _.size(this.path) == parseInt(index) + 1 ? true : false;
        },

        removeDirectory() {
            return api.removeDirectory(this.current).then(result => {
                if (result == true) {
                    this.$toasted.show(this.__('Folder removed successfully'), { type: 'success' });
                    this.$emit('goToFolderManager', this.getParentFolder());
                } else {
                    this.$toasted.show(
                        this.__('Error removing the folder. Please check permissions'),
                        { type: 'error' }
                    );
                }
            });
        },

        showInfo(file) {
            file.loading = true;
            return api
                .getInfo(file.path)
                .then(result => {
                    this.$emit('showInfoItem', result);
                    file.loading = false;
                })
                .catch(error => {
                    file.loading = false;
                    let errorMessage = this.__('Error opening the file. Check your logs');
                    if (error.response.data) {
                        errorMessage = error.response.data.message;
                    }

                    this.$toasted.show(errorMessage, {
                        type: 'error',
                    });
                });
        },

        closePreview() {
            this.activeInfo = false;
            this.info = {};
        },

        refresh() {
            this.$emit('refresh');
        },

        selectFile(file) {
            this.$emit('selectFile', file);
        },

        setDragAndDropEvents() {
            if (this.buttons.upload_drag == false) {
                return false;
            }

            let filemanagerContainer = document.querySelector('#filemanager-manager-container');

            filemanagerContainer.addEventListener('dragenter', e => {
                e.preventDefault();
                if (this.currentDraggedFile === null) {
                    this.uploadingFiles = true;
                    this.cssDragAndDrop = 'inside';

                    let dropperContainer = document.querySelector('.drop-files');
                    this.droppedListener(dropperContainer);
                }
            });

            filemanagerContainer.addEventListener('dragleave', e => {
                e.preventDefault();
                this.uploadingFiles = false;
                this.cssDragAndDrop = 'outside';
            });

            filemanagerContainer.addEventListener('dragover', e => {
                e.preventDefault();
                if (this.currentDraggedFile === null) {
                    this.uploadingFiles = true;
                    this.cssDragAndDrop = 'inside';
                    let dropperContainer = document.querySelector('.drop-files');
                    this.droppedListener(dropperContainer);
                }
            });
        },

        droppedListener(element) {
            if (element) {
                element.removeEventListener('drop', this.droppedListener);
                element.addEventListener('drop', this.dropNewFiles, false);
            }
        },

        async dropNewFiles(e) {
            e.preventDefault();
            this.cssDragAndDrop = 'drop';
            this.uploadingFiles = false;

            let files = await this.getFilesAsync(e.dataTransfer);

            this.uploadFiles(files);
        },

        async getFilesAsync(dataTransfer) {
            const files = [];
            const folders = [];

            this.firstUploadFolder = null;
            for (let i = 0; i < dataTransfer.items.length; i++) {
                let item = dataTransfer.items[i];
                let entry = item.webkitGetAsEntry();

                if (entry.isDirectory) {
                    if (this.firstUploadFolder == null) {
                        this.firstUploadFolder = entry.name;
                    }

                    if (item.kind === 'file') {
                        if (typeof item.webkitGetAsEntry === 'function') {
                            let entryContent = await this.readEntryContentAsync(entry);
                            folders.push(...entryContent);
                        }
                        let file = item.getAsFile();
                        if (file) {
                            files.push(file);
                        }
                    }
                } else {
                    let file = item.getAsFile();
                    if (file) {
                        files.push(file);
                    }
                }
            }

            return { files: files, folders: folders };
        },

        readEntryContentAsync(entry) {
            return new Promise(resolve => {
                let reading = 0;
                const contents = [];
                readEntry(entry);
                function readEntry(entry) {
                    if (isFile(entry)) {
                        reading++;
                        entry.file(file => {
                            reading--;
                            file.filepath = entry.fullPath.replace('/' + entry.name, '');
                            contents.push(file);
                            if (reading === 0) {
                                resolve(contents);
                            }
                        });
                    } else if (isDirectory(entry)) {
                        readReaderContent(entry.createReader());
                    }
                }
                function readReaderContent(reader) {
                    reading++;
                    reader.readEntries(function(entries) {
                        reading--;
                        for (const entry of entries) {
                            readEntry(entry);
                        }
                        if (reading === 0) {
                            resolve(contents);
                        }
                    });
                }
                function isDirectory(entry) {
                    return entry.isDirectory;
                }
                function isFile(entry) {
                    return entry.isFile;
                }
            });
        },

        uploadFiles(data) {
            let files = this.formatFiles(data.files || []);
            let folders = this.formatFiles(data.folders || []);

            if (files.length > 0) {
                this.$emit('uploadFiles', files, 'files');
            }

            if (folders.length > 0) {
                this.$emit('uploadFiles', folders, 'folders', this.firstUploadFolder);
            }
        },

        formatFiles(files) {
            let arrayFiles = [];

            files.forEach(file => {
                if (file.name != '.DS_Store') {
                    arrayFiles.push({
                        id: MD5(file.name),
                        preview: this.getPreview(file),
                        type: file.type,
                        name: file.name,
                        size: filesize(file.size),
                        upload: true,
                        progress: 0,
                        error: false,
                        file: file,
                    });
                }
            });

            return arrayFiles;
        },

        getPreview(file) {
            if (this.isImage(file)) {
                return URL.createObjectURL(file);
            }

            return file.name.split('.').pop();
        },

        isImage(file) {
            return file.type.includes('image'); //returns true or false
        },

        moveFile(oldPath, newPath) {
            return api
                .moveFile(oldPath, newPath)
                .then(result => {
                    if (result.success == true) {
                        this.refresh();
                        this.$toasted.show(this.__('File moved successfully'), {
                            type: 'success',
                            duration: 2000,
                        });
                    } else {
                        this.$toasted.show(
                            this.__('Error opening the file. Check your permissions'),
                            {
                                type: 'error',
                                duration: 3000,
                            }
                        );
                    }
                })
                .catch(error => {
                    this.$toasted.show(error.response.data.message, {
                        type: 'error',
                        duration: 3000,
                    });
                });
        },

        getFileById(type, id) {
            if (id == 'folder_back') {
                return this.$refs[type + '_' + id];
            }

            let found = this.files.find(file => file.id == id);

            if (found) {
                return this.$refs[type + '_' + id][0];
            }
        },

        getParentFolder() {
            let pathData = this.current.split('/');
            pathData.pop();

            return pathData.join('/');
        },

        rename(type, path) {
            this.$emit('rename', type, path);
        },

        deleteData(type, path) {
            this.$emit('delete', type, path);
        },

        select(file) {
            this.$emit('select', file);
        },
    },

    updated: function() {
        //
    },

    mounted() {
        if (!this.eventsLoaded) {
            this.$nextTick(function() {
                setTimeout(() => {
                    this.setDragAndDropEvents();
                    // this.dragFilesEvents();
                    this.eventsLoaded = true;
                }, 500);
            });
        }
    },

    computed: {
        pathsLength() {
            return _.size(this.path);
        },

        filesCount() {
            return _.size(this.files);
        },

        filemanagerClass() {
            if (this.view == 'grid') {
                return 'w-1/6 h-40  px-2 mb-3';
            } else {
                return 'w-full h-16  px-2 mb-3';
            }
        },

        filemanagerIconClass() {
            if (this.view == 'grid') {
                return 'h-40';
            } else {
                return '';
            }
        },

        cssFilemenagerContainer() {
            if (this.cssDragAndDrop == 'inside') {
                return 'bg-20';
            }

            if (this.cssDragAndDrop == 'outside') {
                return '';
            }
            return '';
        },

        filteredFiles() {
            let filtered = this.files;

            if (this.search) {
                filtered = this.files.filter(m => m.name.toLowerCase().indexOf(this.search) > -1);
            }

            if (this.filters.length > 0) {
                filtered = _.filter(filtered, file => {
                    if (file.type == 'dir') {
                        return true;
                    }

                    return _.includes(this.filters, file.ext.toLowerCase());
                });
            }

            return filtered;
        },
    },
};
</script>

<style lang="scss">
/* Scoped Styles */
.w-1\/8 {
    width: 12.5%;
}

.w-1\/10 {
    width: 9%;
}

.w-40 {
    width: 10rem;
}

.h-40 {
    height: 10rem;
}

.h-16 {
    height: 6rem;
}

.obfit-cover {
    object-fit: cover;
}

.files {
    max-height: 60vh;
}

.drop-files {
    min-height: 40vh;
}

.custom-table {
    th {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    td {
        height: 3rem;
    }

    tr {
        white-space: nowrap;

        td {
            white-space: nowrap;
        }
    }
}
</style>
