<template>
    <div>
        <template v-if="display == 'normal'">
            <span >{{ field.value }}</span>
        </template>
        <template v-else>
            <template v-if="field.image">
                <img v-if="field.type == 'image'" :src="field.image" style="object-fit: cover;" class="w-8 h-8" :class="{ 'rounded-full': field.rounded, rounded: !field.rounded }" />
                <div v-else v-html="field.image" class="svg-icon">

                </div>
            </template>
            <template v-else>
                <span class="pl-2">&mdash;</span>
            </template>
			
    		
        </template>
    </div>
 
</template>

<script>
export default {
    props: ['resourceName', 'field'],
    data: () => ({
        display: 'normal',
    }),
    mounted() {
        this.display = this.field.display || 'normal';
    },
};
</script>

<style>
.svg-icon > svg {
    width: 2rem;
    height: 2rem;
    fill: var(--80);
}
</style>
