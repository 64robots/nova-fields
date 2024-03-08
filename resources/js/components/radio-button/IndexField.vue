<template>
    <div
        :title="this.field.value"
        :aria-label="this.field.value"
        :class="[field.indexClasses, applyColor]"
    >{{ value }}</div>
</template>

<script>
import HasOptions from '../../mixins/HasOptions';

    export default {
        mixins: [HasOptions],

        props: ['resourceName', 'field'],

        computed: {
            value() {
                if (this.field.skipTransformation) {
                    return this.field.value;
                }

                const displayValue = this.field.options[this.field.value];

                if (displayValue) {
                    return this.getOptionLabel(
                        displayValue
                    );
                }

                return this.field.default;
            }
        },
    }
</script>
