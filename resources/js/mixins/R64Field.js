export default {
  props: {
    resourceId: {
      type: [String, Number],
      default: null
    },

    parentAttribute: {
      type: String,
      default: ''
    },

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
    },

    wrapperClasses() {
      return this.field.wrapperClasses || this.baseClasses.wrapperClasses
    },

    inputClasses() {
      return this.field.inputClasses || this.baseClasses.inputClasses
    },

    fieldClasses() {
      return this.field.fieldClasses || this.baseClasses.fieldClasses
    },

    labelClasses() {
      return this.field.labelClasses || this.baseClasses.labelClasses
    },

    panelLabelClasses() {
      return  this.field.panelLabelClasses || this.baseClasses.panelLabelClasses
    },

    panelFieldClasses() {
      return this.baseClasses.panelFieldClasses || this.field.panelFieldClasses
    },

    panelWrapperClasses() {
      return (
        this.baseClasses.panelWrapperClasses || this.field.panelWrapperClasses
      )
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
    },

    showContentLabel() {
      return this.field.showContentLabel || this.baseClasses.showContentLabel
    },

    hideContentLabel() {
      return this.field.hideContentLabel || this.baseClasses.hideContentLabel
    },
  },

  methods: {
    shouldShowField(f) {
      const field = f ? f : this.field
      return true
    }
  }
}
