<template>
    <transition name="fade">

        <div
            ref="card"
            :loading="loading"
            :class="css"
        >
            <template v-if="loading">
                <div class="rounded-lg flex items-center justify-center absolute pin z-50">
                    <loader class="text-60" />
                </div>
            </template>

            <div v-if="!this.preview" ref="imageDiv" class="image-block flex justify-center w-full h-5/6">

            </div>

            <viewer :options="voptions" :images="images"
                    @inited="inited"
                    class="viewer" ref="viewer"
                    v-if="preview"
                    @keydown.esc="closePreview"
            >
                <template slot-scope="scope">
                    <img :src="file.image" :key="file.id">
                </template>
            </viewer>

            <div class="missing p-8" v-if="missing">
                <p class="text-center leading-normal">
                    <a :href="file.name" class="text-primary dim" target="_blank">{{__('This image')}}</a> {{__('could not be found.')}}
                </p>
            </div>
        </div>
    </transition>
</template>

<script>
import { Minimum } from 'laravel-nova';

import 'viewerjs/dist/viewer.css'
import { component as Viewer }  from 'v-viewer';

export default {
    components: {
        Viewer,
    },

    props: {
        file: {
            type: Object,
            default: function() {
                return { name: '' };
            },
            required: true,
        },
        css: {
            type: String,
            default:
                'card relative w-2/3 flex flex-wrap justify-center items-center overflow-hidden px-0 py-0',
            required: false,
        },
        preview: {
            type: Boolean,
            default: false,
            required: false,
        },
    },

    data: () => ({
        loading: true,
        missing: false,
        voptions: {
            inline: true,
            button: true,
            navbar: false,
            loading: true,
            title: false,
            toolbar: false,
            tooltip: false,
            movable: false,
            zoomable: false,
            rotatable: false,
            scalable: true,
            transition: true,
            fullscreen: true,
            keyboard: true,
            toggleOnDblclick: true,
            url: 'data-source',
        },
        $viewer: null,
        images: [],
    }),

    methods: {
        inited(viewer) {
            this.$viewer = viewer;
            this.loading = false;
            this.$viewer.show();
        },

        closePreview(e) {
            e.preventDefault();
            e.stopPropagation();
        },
    },

    mounted() {
        if (this.preview) {
            this.images.push(this.file.image);
        } else {
            Minimum(
                window.axios.get(this.file.image, {
                    responseType: 'blob',
                })
            )
                .then(({ headers, data }) => {
                    const blob = new Blob([data], { type: headers['content-type'] });
                    let newImage = new Image();
                    newImage.src = window.URL.createObjectURL(blob);
                    newImage.className = 'image block w-full self-center';
                    newImage.draggable = false;
                    this.$refs.imageDiv.appendChild(newImage);
                    this.loading = false;
                })
                .catch(error => {
                    if (error && this.file.image) {
                        // this.missing = true;
                        // this.$emit('missing', true);
                        // this.loading = false;

                        let newImage = new Image();
                        newImage.src = this.file.image;
                        newImage.className = 'image block w-full self-center';
                        newImage.draggable = false;
                        this.$refs.imageDiv.appendChild(newImage);
                        this.loading = false;
                    }
                });
        }
    },
};
</script>

<style scoped lang="scss">
.card {
    padding: 0 !important;
    box-shadow: none;
    border-radius: 0;
}

.h-5\/6 {
    height: 83.33333%;
}

.h-1\/6 {
    height: 16.66667%;
}

.image-block {
    max-height: 500px;

    .image {
        object-fit: contain;
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
    }
}
</style>

<style lang="scss">
.svg-mime {
    width: 80px;
    height: 75%;
    fill: #62676d;
}

.image-block {
    .image {
        object-fit: contain;
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
    }
}
</style>
