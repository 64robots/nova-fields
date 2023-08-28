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
          class="mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden"
      >
        <slot>
          <ModalHeader v-html="confirmationTitle" />
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
