<template>
    <div class="flex flex-wrap items-stretch w-full mb-2 relative">
        <input type="text" class="flex-shrink flex-grow flex-auto leading-normal w-px flex-1 form-control form-input form-input-bordered-l relative" 
               :placeholder="__('Select a file')" v-model="value" :disabled="isReadonly">
        <div class="flex -mr-px">
            <span class="filemanager-open flex items-center leading-normal rounded-lg rounded-l-none border border-l-0 border-grey-light bg-40 px-3 whitespace-no-wrap text-grey-dark text-sm cursor-pointer" @click="openModalFilemanager">{{ __('Open FileManager') }}</span>
        </div>  
    </div>  
</template>

<script>
export default {
    props: ['value', 'field', 'isReadonly'],

    methods: {
        openModalFilemanager() {
            this.$emit('open-modal');
        },
    },
};
</script>

<style scoped>
.form-input-bordered-l {
    background-color: var(--white);
    border-width: 1px;
    border-color: var(--60);
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    color: var(--80);
    border-radius: 0.5rem;
    -webkit-box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.05);
    border-bottom-right-radius: initial;
    border-top-right-radius: initial;
}

.filemanager-open {
    border-color: var(--60);
}
</style>
