export default {
  props: {
    baseClasses: {
      type: Object,
      default: () => ({
        hideLabelInForms: false,
        hideLabelInDetail: false,
        readOnly: false,
        showContentLabel: 'Show Content',
        hideContentLabel: 'Hide Content'
      })
    }
  },

  computed: {
    hideField() {
      if (this.onCreate) {
        return this.field.hideWhenCreating
      }
      if (this.onUpdate) {
        return this.field.hideWhenUpdating
      }
      if (this.onDetail) {
        return this.field.hideFromDetail
      }
    },

    wrapperClasses() {
      return this.baseClasses.wrapperClasses || this.field.wrapperClasses
    },

    inputClasses() {
      return this.baseClasses.inputClasses || this.field.inputClasses
    },

    fieldClasses() {
      return this.baseClasses.fieldClasses || this.field.fieldClasses
    },

    panelFieldClasses() {
      return this.baseClasses.panelFieldClasses || this.field.panelFieldClasses
    },

    labelClasses() {
      return this.baseClasses.labelClasses || this.field.labelClasses
    },

    panelLabelClasses() {
      return this.baseClasses.panelLabelClasses || this.field.panelLabelClasses
    },

    excerptClasses() {
      return this.baseClasses.excerptClasses || this.field.excerptClasses
    },

    placeholder() {
      return this.field.placeholder === undefined
        ? this.field.name
        : this.field.placeholder
    },

    hideLabelInForms() {
      return this.field.hideLabelInForms || this.baseClasses.hideLabelInForms
    },

    hideLabelInDetail() {
      return this.field.hideLabelInDetail || this.baseClasses.hideLabelInDetail
    },

    readOnly() {
      const readOnly = this.field.readOnly || this.baseClasses.readOnly

      if (readOnly) {
        return true
      }
      if (this.onUpdate) {
        return this.field.readOnlyOnUpdate || this.baseClasses.readOnlyOnUpdate
      }
      if (this.onCreate) {
        return this.field.readOnlyOnCreate || this.baseClasses.readOnlyOnCreate
      }
    },

    showContentLabel() {
      return this.field.showContentLabel || this.baseClasses.showContentLabel
    },

    hideContentLabel() {
      return this.field.hideContentLabel || this.baseClasses.hideContentLabel
    },

    onCreate() {
      return this.$route.name === 'create'
    },

    onUpdate() {
      return this.$route.name === 'edit'
    },

    onDetail() {
      return this.$route.name === 'detail'
    }
  }
}
