<template>
    <div>
        <div class="card relative">
            <vue-dropzone
                ref="dropzone"
                :id="'upload'"
                :options="dropzoneOptions"
                v-on:vdropzone-success="vsuccess"
                v-on:vdropzone-sending="sendingEvent"
            />
        </div>
    </div>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';

let token = document.head.querySelector('meta[name="csrf-token"]');

export default {
    props: {
        options: {
            type: Object,
            default: function() {
                return { message: 'hello' };
            },
            required: false,
        },
        current: {
            type: String,
            default: '/',
            required: true,
        },
    },

    components: {
        vueDropzone: vue2Dropzone,
    },

    data: () => ({
        token: token.content,
        dropzoneOptions: {
            url: '/nova-r64-api/uploads/add',
            thumbnailWidth: 200,
            addRemoveLinks: true,
            dictDefaultMessage:
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M13 5.41V17a1 1 0 0 1-2 0V5.41l-3.3 3.3a1 1 0 0 1-1.4-1.42l5-5a1 1 0 0 1 1.4 0l5 5a1 1 0 1 1-1.4 1.42L13 5.4zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"/></svg><br><br> Drag and Drop or Click',
            headers: { 'X-CSRF-TOKEN': token.content },
        },
    }),
    mounted() {
        //
    },

    methods: {
        sendingEvent(file, xhr, formData) {
            formData.append('current', this.current);
        },

        vsuccess(file, response) {
            if (response.success == true) {
                this.$refs.dropzone.removeFile(file);
                this.$toasted.show(
                    this.__('File') + ' ' + response.name + ' ' + this.__('uploaded successfully'),
                    { type: 'success' }
                );
                this.$emit('refresh', true);
            } else {
                this.$toasted.show(
                    this.__('Error uploading the file. Check your MaxFilesize or permissions'),
                    { type: 'error' }
                );
            }
        },
    },
};
</script>

<style scoped lang="scss">
.h-16 {
    height: 4rem;
}
</style>
