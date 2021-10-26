<template>
    <button
        :class="[ css, stepsComplete? 'confirmation__button--complete' : '' ]"
        :disabled='stepsComplete'
        v-on:click='incrementStep()'>
        {{ currentMessage }}
    </button>
</template>

<script>
export default {
    name: 'confirmation-button',
    props: {
        messages: Array,
        css: {
            type: String,
            default: 'confirmation__button',
        },
    },
    data() {
        return {
            defaultSteps: ['Click to confirm', 'Are you sure?', '✔'],
            currentStep: 0,
        };
    },
    computed: {
        messageList() {
            return this.messages ? this.messages : this.defaultSteps;
        },
        currentMessage() {
            return this.messageList[this.currentStep];
        },
        lastMessageIndex() {
            return this.messageList.length - 1;
        },
        stepsComplete() {
            return this.currentStep === this.lastMessageIndex;
        },
    },
    methods: {
        incrementStep() {
            this.currentStep++;
            if (this.stepsComplete) {
                this.$emit('confirmation-success');
            } else {
                this.$emit('confirmation-incremented');
            }

            setTimeout(
                function() {
                    this.currentStep = 0;
                }.bind(this),
                3000
            );
        },
        reset() {
            this.currentStep = 0;
            this.$emit('confirmation-reset');
        },
    },
};
</script>
<style>
</style>
