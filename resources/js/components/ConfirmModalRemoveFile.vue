<template>
    <portal to="modals" name="Deselect File" transition="fade-transition">
        <modal v-if="active" @modal-close="handleClose">
            <div
                class="bg-white rounded-lg shadow-lg overflow-hidden"
                style="width: 460px"
            >
                <div class="p-8">
                    <heading :level="2" class="mb-6">{{__('Deselect File')}}</heading>
                    <p class="text-80">{{__('Are you sure you want to deselect this file?')}}</p>
                    <p class="text-sm text-80 mt-2">{{ __('Remember: The file will not be delete from your storage') }}</p>
                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button dusk="cancel-upload-delete-button" type="button" data-testid="cancel-button" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>
                        <button dusk="confirm-upload-delete-button" ref="confirmButton" data-testid="confirm-button" @click.prevent="handleConfirm" class="btn btn-default btn-danger">{{__('Deselect')}}</button>
                    </div>
                </div>
            </div>
        </modal>
    </portal>
</template>

<script>
export default {
    props: {
        active: {
            default: false,
            required: true,
            type: Boolean,
        },
    },

    mounted() {
        //
    },

    watch: {
        // active(value) {
        //     if (value) {
        //         this.$refs.confirmButton.focus();
        //     }
        // }
    },

    methods: {
        handleClose() {
            this.$emit('close');
        },

        handleConfirm() {
            this.$emit('confirm');
        },
    },
};
</script>
