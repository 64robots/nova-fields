<template>
    <div class="flex border-b border-40">
        <div class="w-1/4 py-4">
            <slot>
                <h4 class="font-normal text-80">
                    {{ label }}
                </h4>
            </slot>
        </div>
        <div class="w-3/4 py-4 text-90 flex items-center">
            <slot name="value">

                <template v-if="field.type == 'image'">
                    <ImageDetail v-if="loaded" :file="field" :css="'card relative card relative border border-lg border-50 overflow-hidden px-0 py-0 max-w-xs'"></ImageDetail>
                    <div class="ml-2">{{ field.path }}</div>
                </template>
            
                <template v-else>
                    <object class="no-preview" v-html="field.image">
                    </object>
                    <div class="ml-2">{{ field.path }}</div>
                </template>
            </slot>
        </div>
    </div>
</template>

<script>
import ImageDetail from '../../../modules/Image';

export default {
    components: {
        ImageDetail: ImageDetail,
    },
    props: {
        field: {
            type: Object,
            required: true,
        },
        fieldName: {
            type: String,
            default: '',
        },
    },
    data: () => ({
        loaded: true,
    }),
    computed: {
        label() {
            return this.fieldName || this.field.name;
        },
    },
};
</script>
