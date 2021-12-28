<template>
  <r64-default-field
      :hide-field="hideField"
      :field="field"
      :hide-label="hideLabelInForms"
      :field-classes="fieldClasses"
      :wrapper-classes="wrapperClasses"
      :label-classes="labelClasses"
  >
    <template slot="field">
        <input
            ref="theInput"
            v-bind="extraAttributes"

            :id="field.name"
            :dusk="field.attribute"
            :type="inputType"
            :min="inputMin"
            :max="inputMax"
            :step="inputStep"
            :pattern="inputPattern"
            :disabled="readOnly"
            v-model="value"
            :class="[errorClasses, inputClasses]"
            :placeholder="placeholder"

        />

        <button
            class="
            btn btn-link
            rounded
            px-1
            py-1
            inline-flex
            text-sm text-primary
            ml-1
            mt-2
          "
            v-if="field.showCustomizeButton"
            type="button"
            @click="toggleCustomizeClick"
        >
          {{ __('Customize') }}
        </button>
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import R64Field from '../../mixins/R64Field'
import slugify from '../../util/slugify'

export default {
  mixins: [HandlesValidationErrors, FormField,R64Field],

  data: () => ({
    isListeningToChanges: false,
  }),

  mounted() {
    const listenToCreateModalClosed = () => {
      if (this.isListeningToChanges === true) {
        this.registerChangeListener()
      }
    }

    Nova.$on('create-relation-modal-opened', this.removeChangeListener)
    Nova.$on('create-relation-modal-closed', listenToCreateModalClosed)

    if (this.shouldRegisterInitialListener) {
      this.registerChangeListener()
    }

    this.$once('hook:beforeDestroy', () => {
      Nova.$off('create-relation-modal-opened', this.removeChangeListener)
      Nova.$off('create-relation-modal-closed', listenToCreateModalClosed)
      this.removeChangeListener()
    })
  },

  methods: {
    changeListener(value) {
      return value => {
        this.value = slugify(value, this.field.separator)
      }
    },

    registerChangeListener() {
      Nova.$on(this.eventName, this.handleChange)

      this.isListeningToChanges = true
    },

    removeChangeListener() {
      if (this.isListeningToChanges === true) {
        Nova.$off(this.eventName)
      }
    },

    handleChange(value) {
      //this.value = slugify(value, this.field.separator);
      this.value = this.slugify(value);
    },
    slugify:function(value){
      return value.toLowerCase().replace(/&#8482;/g, '').trim().replace(/[^\w ]+/g, '').replace(/ +/g, '-').replace(/\_/g, '');
    },

    toggleCustomizeClick() {
      if (this.field.readonly) {
        this.removeChangeListener()
        this.isListeningToChanges = false
        this.field.readonly = false
        this.field.extraAttributes.readonly = false
        this.field.showCustomizeButton = false
        this.registerChangeListener()
        this.$refs.theInput.focus()
        return
      }

      this.registerChangeListener()
      this.field.readonly = true
      this.field.extraAttributes.readonly = true
    },
  },

  computed: {
    shouldRegisterInitialListener() {
      return !this.field.updating
    },

    eventName() {
      return `${this.field.from}-change`
    },

    extraAttributes() {
      return this.field.extraAttributes || {}
    },
    /**
     * Get the input type.
     */
    inputType() {
      return this.field.type || 'text'
    },

    /**
     * Get the input step amount.
     */
    inputStep() {
      return this.field.step
    },

    /**
     * Get the input minimum amount.
     */
    inputMin() {
      return this.field.min
    },

    /**
     * Get the input maximum amount.
     */
    inputMax() {
      return this.field.max
    },

    /**
     * Get the pattern that should be used for the field
     */
    inputPattern() {
      return this.field.pattern
    }
  },
}
</script>
