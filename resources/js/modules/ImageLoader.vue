<template>
    <transition name="fade">
        <template v-if="view == 'grid'">

            <div @click="clickStrategy"
                 ref="card"
                 :loading="loading"
                 class="card relative flex flex-wrap justify-center border border-lg border-50 overflow-hidden px-0 py-0 cursor-pointer"
            >
                <template v-if="loading">
                    <div class="rounded-lg flex items-center justify-center absolute pin z-50">
                        <loader class="text-60" />
                    </div>
                </template>

                <div v-if="file.mime != 'image'" v-html="file.thumb" class="mime-icon flex items-center justify-center  h-5/6">

                </div>

                <div  v-if="file.mime == 'image'" ref="image" class="image-block block w-full h-5/6">

                </div>

                <div class="actions-grid absolute pin-t pin-r pr-2 pt-2 pb-1 pl-2 "
                     :class="{ 'hidden': !multiSelecting , 'bg-50' : shouldShowHover}"
                >
                    <div v-if="multiSelecting">
                        <input :checked="selected" type="checkbox">
                    </div>

                    <div v-else class="flex flex-wrap text-70" >
                        <div class="cursor-pointer" :class="{ ' mr-2' : renamePermission }" v-if="deletePermission" @click.prevent="deleteFile($event)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                        </div>
                        <div class="cursor-pointer" v-if="renamePermission" @click.prevent="renameFile($event)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" class="fill-current"><path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="missing p-8" v-if="missing">
                    <p class="text-center leading-normal">
                        <a :href="file.name" class="text-primary dim" target="_blank">{{__('This image')}}</a> {{__('could not be found.')}}
                    </p>
                </div>

                <div class="h-1/6 w-full text-center text-xs  border-t border-30 bg-50 flex items-center justify-center">
                    {{ file.name | truncate(25) }}
                </div>
            </div>
        </template>

        <template v-if="view == 'list'">

            <tr @click="clickStrategy" :loading="loading" v-bind:key="file.id" class="cursor-pointer">
                <td v-if="multiSelecting" class="w-8">
                    <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="block">
                        <g v-if="selected"><rect width="20" height="20" rx="4" fill="var(--primary)"></rect> <path fill="#FFF" d="M7.7 9.3c-.23477048-.3130273-.63054226-.46037132-1.01285927-.37708287-.38231702.08328846-.68093514.38190658-.7642236.7642236C5.83962868 10.0694577 5.9869727 10.4652295 6.3 10.7l2 2c.38884351.3811429 1.01115649.3811429 1.4 0l4-4c.3130273-.23477048.4603713-.63054226.3770829-1.01285927-.0832885-.38231702-.3819066-.68093514-.7642236-.7642236C12.9305423 6.83962868 12.5347705 6.9869727 12.3 7.3L9 10.58l-1.3-1.3v.02z"></path></g>
                        <g v-else><rect width="20" height="20" fill="#FFF" rx="4"></rect> <rect width="19" height="19" fill="none" x=".5" y=".5" stroke="#CCD4DB" rx="4"></rect></g>
                    </svg>
                </td>
                <td>
                    <template v-if="loading">
                        <div class="rounded-lg flex items-center justify-center absolute pin z-50">
                            <loader class="text-60" />
                        </div>
                    </template>

                    <div class="w-full h-full flex justify-center items-center">
                        <div v-if="file.mime != 'image'" v-html="file.thumb" class="mime-icon flex items-center justify-center w-1/3 h-full">

                        </div>

                        <div  v-if="file.mime == 'image'" ref="image" class="image-block block w-full h-full">

                        </div>
                    </div>

                    <div class="w-full missing p-8" v-if="missing">
                        <p class="text-center leading-normal">
                            <a :href="file.name" class="text-primary dim" target="_blank">{{__('This image')}}</a> {{__('could not be found.')}}
                        </p>
                    </div>
                </td>

                <td>
                    {{ file.name }}
                </td>

                <td>
                    {{ file.size_human }}
                </td>

                <td>
                    {{ file.date }}
                </td>

                <td>
                    <div class="flex flex-wrap text-70">
                        <div class="cursor-pointer mr-2" v-if="deletePermission" @click.prevent="deleteFile($event)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                        </div>
                        <div class="cursor-pointer" v-if="renamePermission" @click.prevent="renameFile($event)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" class="fill-current"><path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>
                        </div>
                    </div>
                </td>
            </tr>

        </template>

    </transition>
</template>

<script>
import findIndex from 'lodash/findIndex';
import { Minimum } from 'laravel-nova';

export default {
    components: {
        //
    },

    props: {
        file: {
            type: Object,
            default: function() {
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
        if (this.file.mime == 'image') {
            Minimum(
                window.axios.get(this.file.thumb, {
                    responseType: 'blob',
                })
            )
                .then(({ headers, data }) => {
                    const blob = new Blob([data], { type: headers['content-type'] });
                    let imageDiv = document.createElement('div');
                    let imageBlog = null;

                    imageBlog = window.URL.createObjectURL(blob);
                    imageDiv.style.backgroundImage = "url('" + imageBlog + "')";
                    imageDiv.className = this.getClassContainer();
                    imageDiv.draggable = false;
                    this.$refs.image.appendChild(imageDiv);
                    this.loading = false;
                })
                .catch(error => {
                    if (error && this.$refs.image) {
                        //defaulImage
                        let imageDiv = document.createElement('div');
                        imageDiv.style.backgroundImage = "url('" + this.file.thumb + "')";
                        imageDiv.className = this.getClassContainer();
                        imageDiv.draggable = false;
                        this.$refs.image.appendChild(imageDiv);
                        this.loading = false;
                    }
                });
        } else {
            this.loading = false;
        }
    },
    methods: {
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

        getClassContainer() {
            if (this.view == 'list') {
                return 'block w-full h-full bg-center bg-cover h-2/3';
            }

            return 'block w-full h-full bg-center bg-cover h-2/3';
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
    filters: {
        truncate: function(text, stop, clamp) {
            return text.slice(0, stop) + (stop < text.length ? clamp || '...' : '');
        },
    },
    watch: {
        'file.loading': function(value) {
            if (value == true) {
                this.loading = true;
            } else {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped  lang="scss">
.card {
    padding: 0 !important;

    &:hover {
        > .image-block,
        > .mime-icon {
            opacity: 0.5;
        }

        > .actions-grid {
            display: flex;
        }
    }
}

.h-5\/6 {
    height: 83.33333%;
}

.h-1\/6 {
    height: 16.66667%;
}

.actions-grid {
    border-bottom-left-radius: 0.5rem;
}
</style>

<style>
.svg-mime {
    width: 80px;
    height: 75%;
    fill: #62676d;
}
</style>
