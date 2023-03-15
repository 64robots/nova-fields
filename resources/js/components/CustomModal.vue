<template>
  <Teleport to="#modals">
    <Modal
        data-testid="delete-resource-modal"
        :show="true"
        role="alertdialog"
        size="lg"
        :closes-via-backdrop="false"
        @keyup.esc="handleClose"
        @modal-close="handleClose"
    >
      <form
          class="mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden process-div"
      >
        <slot>
          <ModalHeader>
            <div class="flex justify-between items-center">
              <h2 class="text-90 font-normal text-xl">{{ confirmationTitle }}</h2>
              <button dusk="cancel-action-button" type="button" class="btn btn-link dim cursor-pointer text-80" @click.prevent="handleClose">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </ModalHeader>
          <ModalContent>
            <p class="text-80 leading-normal" v-html="confirmationMessage"></p>
          </ModalContent>
        </slot>
        <ModalFooter>
          <div class="ml-auto">
            <LinkButton
                type="button"
                data-testid="cancel-button"
                dusk="cancel-delete-button"
                class="mr-3"
                @click="handleClose"
            >
              {{ __('Cancel') }}
            </LinkButton>

            <LoadingButton
                ref="confirmButton"
                dusk="confirm-action-button"
                component="DefaultButton"
                type="submit"
                @click="handleConfirm"
            >
              Confirm
            </LoadingButton>
          </div>
        </ModalFooter>
      </form>
    </Modal>
  </Teleport>
</template>

<script>
export default {
  name: "CustomModal",
  props:['confirmationMessage','confirmationTitle'],
  emits: ['confirm', 'close'],
  methods:{
    handleClose(){
      this.$emit('close');
    },
    handleConfirm(){
      this.$emit('confirm');
    }
  },
}
</script>

<style scoped>
</style>
