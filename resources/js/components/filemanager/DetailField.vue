<template>
    <div>
        <template v-if="field.value">
            <r64-panel-item  v-if="display === 'normal'"
                         :field="field"

                         :hide-label="hideLabelInDetail"
                         :label-classes="panelLabelClasses"
                         :field-classes="panelFieldClasses"
                         :wrapper-classes="panelWrapperClasses"
            />
            <ImagePanel v-else :field="field"

                        :hide-label="hideLabelInDetail"
                        :label-classes="panelLabelClasses"
                        :field-classes="panelFieldClasses"
                        :wrapper-classes="panelWrapperClasses" />
        </template>

        <template v-else>
            <panel-item  :field="field" />
        </template>
    </div>
</template>

<script>
import ImagePanel from './custom/ImagePanel';
import R64Field from '../../mixins/R64Field'
export default {
    mixins : [R64Field],
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
