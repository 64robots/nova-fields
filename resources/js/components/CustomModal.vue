<template>
  <modal
      class="change-attribute-modal"
      data-testid="confirm-action-modal"
      tabindex="-1"
      role="dialog"
      :closes-via-backdrop="false"
      @modal-close="handleClose"
  >
    <div class="relative mx-auto flex justify-center z-20 py-view">
      <div class="bg-white rounded-lg shadow-lg overflow-hidden " style="width: 460px;">
        <div class="p-8">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-90 font-normal text-xl">{{ confirmationTitle }}</h2>
            <button type="button" class="btn btn-link dim cursor-pointer text-80" @click.prevent="handleClose">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <p class="text-80 leading-normal" v-html="confirmationMessage"></p>
        </div>
        <div class="bg-30 px-6 py-3 flex">
          <div class="ml-auto">
            <button type="button" class="btn btn-default btn-primary" @click="handleConfirm">Confirm</button>
            <button type="button" class="btn btn-default btn-primary"
                    @click="handleClose">Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </modal>
</template>

<script>
export default {
  name: "CustomModal",
  props:['confirmationMessage','confirmationTitle'],
  methods:{
    handleClose(){
      this.$emit('close');
    },
    handleConfirm(){
      this.$emit('confirm');
    }
  }
}
</script>

<style scoped>
</style>