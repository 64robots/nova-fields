<template>
    <template v-if="view == 'grid'">
        <div
            ref="card"
            :loading="loading"
            class="w-full h-full relative flex flex-col justify-center border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden cursor-pointer"
            @click="clickStrategy"
        >
            <div
                v-if="loading"
                class="absolute inset-0 bg-white dark:bg-gray-800 flex items-center justify-center flex-grow z-50"
            >
                <Loader />
            </div>
  
            <div
                v-if="file.mime != 'image'"
                class="flex-grow flex items-center justify-center p-4"
                style="height: 160px"
            >
                <!-- <Icon :type="mimeIcons[file.mime] || mimeIcons.text" width="48" height="48" /> -->
            </div>
  
            <img
                v-if="file.mime == 'image'"
                :src="file.thumb"
                class="block w-full flex-grow"
                style="object-fit: contain; height: 160px"
                @load="imageOnLoad"
                @error="imageOnError"
            />
  
            <div
                class="absolute inset-0 bg-white dark:bg-gray-800 flex-grow flex flex-col items-center justify-center text-center leading-normal p-4"
                v-if="missing"
            >
                <a :href="file.name" class="text-primary" target="_blank">
                    {{ __('This image') }}
                </a>
                {{ __('could not be found.') }}
            </div>
  
            <div
                class="w-full h-8 flex-shrink-0 text-center text-xs border-t border-gray-200 dark:border-gray-700 flex items-center justify-center"
            >
                <p class="px-2 truncate">
                    {{ truncate(file.name, 25) }}
                </p>
  
                <div
                    class="flex items-center justify-center ml-auto"
                    :class="{ 'bg-50': shouldShowHover }"
                >
                    <div
                        class="h-8 w-8 cursor-pointer hover:opacity-50 border-l border-gray-200 dark:border-gray-700 px-2 inline-flex items-center justify-center"
                        v-if="multiSelecting"
                    >
                        <input :checked="selected" type="checkbox" />
                    </div>
  
                    <template v-else>
                        <div
                            class="h-8 w-8 cursor-pointer hover:opacity-50 border-l border-gray-200 dark:border-gray-700 px-2 inline-flex items-center justify-center"
                            v-if="renamePermission"
                            @click.prevent="renameFile($event)"
                        >
                            <Icon type="pencil-alt" width="18" height="18" />
                        </div>
                        <div
                            class="h-8 w-8 cursor-pointer hover:opacity-50 border-l border-gray-200 dark:border-gray-700 px-2 inline-flex items-center justify-center text-red-500"
                            v-if="deletePermission"
                            @click.prevent="deleteFile($event)"
                        >
                            <Icon type="trash" width="18" height="18" />
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
  
    <template v-if="view == 'list'">
        <tr @click="clickStrategy" :loading="loading" v-bind:key="file.id" class="cursor-pointer">
            <td
                v-if="multiSelecting"
                class="text-center py-2 pr-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900"
            >
                <svg
                    width="20"
                    height="20"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    class="block"
                >
                    <g v-if="selected">
                        <rect width="20" height="20" rx="4" fill="var(--colors-primary-500)"></rect>
                        <path
                            fill="#FFF"
                            d="M7.7 9.3c-.23477048-.3130273-.63054226-.46037132-1.01285927-.37708287-.38231702.08328846-.68093514.38190658-.7642236.7642236C5.83962868 10.0694577 5.9869727 10.4652295 6.3 10.7l2 2c.38884351.3811429 1.01115649.3811429 1.4 0l4-4c.3130273-.23477048.4603713-.63054226.3770829-1.01285927-.0832885-.38231702-.3819066-.68093514-.7642236-.7642236C12.9305423 6.83962868 12.5347705 6.9869727 12.3 7.3L9 10.58l-1.3-1.3v.02z"
                        ></path>
                    </g>
                    <g v-else>
                        <rect width="20" height="20" fill="#FFF" rx="4"></rect>
                        <rect
                            width="19"
                            height="19"
                            fill="none"
                            x=".5"
                            y=".5"
                            stroke="#CCD4DB"
                            rx="4"
                        ></rect>
                    </g>
                </svg>
            </td>
            <td class="text-center py-1 pr-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
                <div
                    v-if="loading"
                    class="absolute inset-0 bg-white dark:bg-gray-800 flex items-center justify-center flex-grow z-50"
                >
                    <Loader />
                </div>
  
                <div
                    v-if="file.mime != 'image'"
                    class="w-10 h-10 flex items-center justify-start"
                >
                    <!-- <Icon :type="mimeIcons[file.mime] || mimeIcons.text" width="32" height="32" /> -->
                </div>
  
                <img
                    v-if="file.mime == 'image'"
                    :src="file.thumb"
                    class="block w-10 h-10"
                    style="object-fit: contain"
                    @load="imageOnLoad"
                    @error="imageOnError"
                />
  
                <div
                    class="absolute inset-0 bg-white dark:bg-gray-800 flex-grow flex flex-col items-center justify-center text-center leading-normal p-1"
                    v-if="missing"
                >
                    <a :href="file.name" class="text-primary" target="_blank">
                        {{ __('This image') }}
                    </a>
                    {{ __('could not be found.') }}
                </div>
            </td>
  
            <td class="p-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
                {{ file.name }}
            </td>
  
            <td class="text-center p-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
                {{ file.size_human }}
            </td>
  
            <td class="text-center p-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
                {{ file.date }}
            </td>
  
            <td class="text-center py-1 pl-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
                <div class="flex items-center justify-end" v-if="file.id != 'folder_back'">
                    <Icon
                        type="pencil-alt"
                        width="20"
                        height="20"
                        class="cursor-pointer hover:opacity-50 mr-2"
                        v-if="renamePermission"
                        @click.prevent="renameFile($event)"
                    />
  
                    <Icon
                        type="trash"
                        width="20"
                        height="20"
                        class="cursor-pointer hover:opacity-50 text-red-500"
                        v-if="deletePermission"
                        @click.prevent="deleteFile($event)"
                    />
                </div>
            </td>
        </tr>
    </template>
  </template>
  
  <script>
  import findIndex from 'lodash/findIndex';
  // import MimeIconsEnum from '../tools/MimeIconsEnum'
  export default {
    components: {
        //
    },
    props: {
        file: {
            type: Object,
            default: function () {
                return { name: '' };
            },
            required: true,
        },
        view: {
            type: String,
            default: 'grid',
            required: false,
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
        deletePermission: {
            type: Boolean,
            required: false,
            default: true,
        },
        renamePermission: {
            type: Boolean,
            required: false,
            default: true,
        },
    },
    data: () => ({
        loading: true,
        missing: false,
        // mimeIcons: MimeIconsEnum,
    }),
    computed: {
        selected() {
            return (
                findIndex(this.selectedFiles, { type: this.file.type, path: this.file.path }) >= 0
            );
        },
        shouldShowHover() {
            if (this.deletePermission) {
                return true;
            }
            if (this.renamePermission) {
                return true;
            }
            if (this.multiSelecting) {
                return true;
            }
            return false;
        },
    },
    mounted() {
        if (this.file.mime !== 'image') {
            this.loading = false;
        }
    },
    methods: {
        imageOnLoad() {
            this.loading = false;
        },
        imageOnError() {
            this.missing = true;
            this.$emit('missing', true);
            this.loading = false;
        },
        truncate(text, stop, clamp = '...') {
            return text.slice(0, stop) + (stop < text.length ? clamp : '');
        },
        clickStrategy() {
            return this.multiSelecting ? this.select() : this.showInfo();
        },
        select() {
            this.$emit('select', {
                type: this.file.type,
                path: this.file.path,
            });
        },
        showInfo() {
            this.$emit('showInfo', this.file);
        },
        deleteFile(e) {
            this.stopDefaultActions(e);
            this.$emit('delete', 'file', this.file.path);
        },
        renameFile(e) {
            this.stopDefaultActions(e);
            this.$emit('rename', 'file', this.file.path);
        },
        stopDefaultActions(e) {
            e.preventDefault();
            e.stopPropagation();
        },
    },
    watch: {
        'file.loading': function (value) {
            if (value == true) {
                this.loading = true;
            } else {
                this.loading = false;
            }
        },
    },
  };
  </script>
  
  <style>
  .bg-center {
    background-position: center;
  }
  .bg-cover {
    background-size: cover;
  }
  </style>