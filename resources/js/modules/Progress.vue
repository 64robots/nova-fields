<template>
    <div class="progress mt-2 w-5/6">
        <div class="progress-bar file" :style="{ width: progressByFile}" v-if="type == 'files'"></div>
        <div class="progress-bar percent" :style="{ width: progressByPercent}" v-else></div>
    </div>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: 'files',
            required: false,
        },

        file: {
            type: Object,
            default: function() {
                return {};
            },
            required: false,
        },

        percent: {
            type: Number,
            default: 0,
            required: false,
        },
    },

    data: () => ({
        loading: true,
    }),

    mounted() {},

    methods: {
        getCorrectPercent(percent) {
            if (!percent) {
                percent = 0;
            }

            if (percent != 0) {
                percent += '%';
            }

            return percent;
        },
    },

    computed: {
        progressByFile() {
            return this.getCorrectPercent(this.file.progress);
        },

        progressByPercent() {
            return this.getCorrectPercent(this.percent);
        },
    },
};
</script>

<style scoped  lang="scss">
/* PROGRESS */
.progress {
    background-color: #e5e9eb;
    height: 0.25em;
    position: relative;

    .progress-bar {
        animation-duration: 3s;
        animation-name: width;
        background-image: linear-gradient(
            to right,
            #4cd964,
            #5ac8fa,
            #007aff,
            #34aadc,
            #5856d6,
            #ff2d55
        );
        background-size: 24em 0.25em;
        height: 100%;
        position: relative;
    }
}
</style>
