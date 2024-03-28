<template>
    <Modal
      :show="show"
      data-testid="confirm-upload-removal-modal"
      role="alertdialog"
      size="md"
    >
      <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden"
        style="width: 460px"
      >
        <ModalHeader v-text="__('Delete File')" />
        <ModalContent>
          <p class="leading-tight">
            {{ __('Are you sure you want to delete this file?') }}
          </p>
        </ModalContent>
        <ModalFooter>
          <div class="ml-auto">
            <LinkButton
              dusk="cancel-upload-delete-button"
              type="button"
              @click.prevent="$emit('close')"
              class="mr-3"
            >
              {{ __('Cancel') }}
            </LinkButton>
  
            <LoadingButton
              @click.prevent="handleConfirm"
              ref="confirmButton"
              dusk="confirm-upload-delete-button"
              :disabled="clicked"
              :processing="clicked"
              component="DangerButton"
            >
              {{ __('Delete') }}
            </LoadingButton>
          </div>
        </ModalFooter>
      </div>
    </Modal>
  </template>
  
  <script>
  export default {
    emits: ['confirm', 'close'],
  
    props: {
      show: { type: Boolean, default: false },
    },
  
    /**
     * Mount the component.
     */
    mounted() {
      // this.$refs.confirmButton.focus()
    },
  
    data: () => ({ clicked: false }),
  
    methods: {
      handleConfirm() {
        this.clicked = true
        this.$emit('confirm')
      },
    },
  }
  </script>
  