<template>
    <div class="stack-uploads fixed pin-b bg-white shadow" v-if="files.length > 0">

        <div class="files p-4" v-if="type == 'folders'">
            <transition name="fade" >
                <div class="flex flex-wrap w-full items-center">
                    <div class="preview w-1/3 text-70">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-12 h-12 flex justify-center items-center fill-current"><path d="M20 6a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h7.41l2 2H20zM4 6v12h16V8h-7.41l-2-2H4z"/></svg>
                    </div>
                    <div class="w-2/3 text-xs">
                        <template v-if="error">
                            <div class="text-danger">{{ __('Error on upload') }}</div>
                        </template>
                        <template v-else>
                            {{ __('Uploading Folder') }} <small v-if="totalPercent == 100" class="text-success uppercase">{{ __('Success') }}</small>
                            <progress-module :percent="totalPercent" type="folder"></progress-module>
                        </template>
                    </div>
                </div>
            </transition>
        </div>


        <div class="files p-4" v-for="(file, indexFiles) in files" v-bind:key="indexFiles" v-else>
            <transition name="fade" >
                <div class="flex flex-wrap w-full items-center"  v-bind:key="indexFiles" v-if="file.upload == true">
                    <div class="preview w-1/3">
                        <img class="rounded-full w-12 h-12" v-if="isImage(file)" :src="file.preview">
                        <div v-else class="rounded-full bg-50 w-12 h-12 flex justify-center items-center">
                            <div class="uppercase">
                                {{ file.preview }}
                            </div>
                        </div>
                    </div>
                    <div class="w-2/3 text-xs">
                        <template v-if="file.error">
                            <div class="text-danger">{{ __('Error on upload') }}</div>
                        </template>
                        <template v-else>
                            {{ file.name | truncate(15) }} <small v-if="file.progress == 100" class="text-success uppercase">{{ __('Success') }}</small>
                            <progress-module :file="file"></progress-module>
                        </template>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
import Progress from '../modules/Progress';

let token = document.head.querySelector('meta[name="csrf-token"]');

export default {
    props: {
        current: {
            type: String,
            default: '/',
            required: true,
        },
        visibility: {
            type: String,
            default: 'public',
        },

        rules: {
            type: Array,
            default: () => [],
            required: false,
        },
    },

    components: {
        'progress-module': Progress,
    },

    data: () => ({
        token: token.content,
        files: [],
        filesUploaded: [],
        type: 'files',
        totalPercent: 0,
        error: false,
    }),

    methods: {
        isImage(file) {
            return file.type.includes('image'); //returns true or false
        },

        getRandomArbitrary(min, max) {
            return Math.random() * (max - min) + min;
        },

        startUploadingFiles(files, type) {
            this.files = files;
            this.type = type;
            this.filesUploaded = [];
            this.processFiles();
        },

        async processFiles() {
            Array.from(this.files).forEach(file => {
                this.startUpload(file);
            });
        },

        startUpload(file) {
            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
                onUploadProgress: progressEvent => {
                    file.progress = parseInt(
                        Math.round((progressEvent.loaded * 100) / progressEvent.total)
                    );
                },
            };

            let filePath;

            if (file.file.webkitRelativePath) {
                filePath = file.file.webkitRelativePath.replace('/' + file.file.name, '');
            } else if (file.file.filepath) {
                filePath = file.file.filepath;
            } else {
                filePath = '/';
            }

            let data = new FormData();
            data.append('file', file.file);
            data.append('current', this.current + '/' + filePath);
            data.append('visibility', this.visibility);

            if (this.type == 'files') {
                data.append('rules', JSON.stringify(this.rules));
                this.uploadFileToServer(file, data, config);
            } else {
                this.uploadFolderToServer(file, data, config);
            }
        },

        uploadFileToServer(file, data, config) {
            window.axios
                .post('/nova-r64-api/uploads/add', data, config)
                .then(response => {
                    if (response.data.success == true) {
                        _.forEach(this.files, fileUpload => {
                            if (fileUpload.name == response.data.name) {
                                fileUpload.upload = true;
                            }
                        });
                        this.filesUploaded.push(file.id);

                        setTimeout(() => {
                            this.$emit('removeFile', file.id);
                        }, 2000);
                    } else {
                        this.$toasted.show(
                            this.__(
                                'Error uploading the file. Check your MaxFilesize or permissions'
                            ),
                            { type: 'error' }
                        );
                    }
                })
                .catch(error => {
                    if (error.response.data.errors) {
                        let errors = error.response.data.errors;
                        let errorsArray = Object.values(errors).flat();

                        let errorMessage = errorsArray.join('<br>');

                        this.$toasted.show(errorMessage, { type: 'error' });
                    } else {
                        this.$toasted.show(
                            this.__(
                                'Error uploading the file. Check your MaxFilesize or permissions'
                            ),
                            { type: 'error' }
                        );
                    }

                    file.error = true;

                    setTimeout(() => {
                        this.$emit('removeFile', file.id);
                    }, 1000);
                });
        },

        uploadFolderToServer(file, data, config) {
            data.append('folder', true);

            window.axios
                .post('/nova-r64-api/uploads/add', data, config)
                .then(response => {
                    if (response.data.success == true) {
                        _.forEach(this.files, fileUpload => {
                            if (fileUpload.name == response.data.name) {
                                fileUpload.upload = true;
                            }
                        });

                        this.filesUploaded.push(file.id);

                        this.totalPercent = (100 * this.filesUploaded.length) / this.files.length;

                        setTimeout(() => {
                            this.$emit('removeFile', file.id);
                        }, 2000);
                    } else {
                        this.$toasted.show(
                            this.__(
                                'Error uploading the file. Check your MaxFilesize or permissions'
                            ),
                            { type: 'error' }
                        );
                    }
                })
                .catch(() => {
                    this.error = true;
                    this.$toasted.show(
                        this.__('Error uploading the file. Check your MaxFilesize or permissions'),
                        { type: 'error' }
                    );
                    setTimeout(() => {
                        this.$emit('removeFile', file.id);
                    }, 1000);
                });
        },
    },

    filters: {
        truncate: function(text, stop, clamp) {
            return text.slice(0, stop) + (stop < text.length ? clamp || '...' : '');
        },
    },
};
</script>

<style scoped lang="scss">
.stack-uploads {
    right: 10px;
    bottom: 10px;
    width: 300px;
    z-index: 99;
}
</style>
