<template>
    <button
        type="button"
        @keydown.enter.prevent="$emit('click')"
        @click.prevent="$emit('click')"
        tabindex="0"
        class="cursor-pointer dim btn btn-link text-primary inline-flex items-center"
    >
        <icon type="delete" view-box="0 0 20 20" width="16" height="16" />
        <slot />
    </button>
</template>

<script>
export default {}
</script>
