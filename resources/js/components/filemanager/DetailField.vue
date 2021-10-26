<template>
    <div>
        <template v-if="field.value">
            <panel-item  v-if="display == 'normal'" :field="field" />
            <ImagePanel v-else :field="field" />
        </template>

        <template v-else>
            <panel-item  :field="field" />
        </template>
    </div>
</template>

<script>
import ImagePanel from './custom/ImagePanel';

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    components: {
        ImagePanel: ImagePanel,
    },
    data: () => ({
        display: 'normal',
    }),
    mounted() {
        this.display = this.field.display || 'normal';
    },
};
</script>
