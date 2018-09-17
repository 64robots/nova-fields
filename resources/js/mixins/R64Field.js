export default {
  props: {
    baseClasses: {
      type: Object,
      default: () => ({
        hideLabelInForms: false,
        hideLabelInDetail: false,
        fieldClasses: 'w-1/2 px-8 py-6',
        panelFieldClasses: 'w-3/4 py-4',
        wrapperClasses: 'flex border-b border-40',
        labelClasses: 'w-1/5 px-8 py-6',
        panelLabelClasses: 'w-1/4 py-4',
        excerptClasses:
          'cursor-pointer dim inline-block text-primary font-bold',
        readOnly: false,
        showContentLabel: 'Show Content',
        hideContentLabel: 'Hide Content'
      })
    }
  },

  computed: {
    hideLabelInForms() {
      return this.field.hideLabelInForms || this.baseClasses.hideLabelInForms;
    },

    hideLabelInDetail() {
      return this.field.hideLabelInDetail || this.baseClasses.hideLabelInDetail;
    },

    fieldClasses() {
      return this.field.fieldClasses || this.baseClasses.fieldClasses;
    },

    panelFieldClasses() {
      return this.field.panelFieldClasses || this.baseClasses.panelFieldClasses;
    },

    wrapperClasses() {
      return this.field.wrapperClasses || this.baseClasses.wrapperClasses;
    },

    labelClasses() {
      return this.field.labelClasses || this.baseClasses.labelClasses;
    },

    panelLabelClasses() {
      return this.field.panelLabelClasses || this.baseClasses.panelLabelClasses;
    },

    excerptClasses() {
      return this.field.excerptClasses || this.baseClasses.excerptClasses;
    },

    readOnly() {
      return this.field.readOnly || this.baseClasses.readOnly;
    },

    showContentLabel() {
      return this.field.showContentLabel || this.baseClasses.showContentLabel;
    },

    hideContentLabel() {
      return this.field.hideContentLabel || this.baseClasses.hideContentLabel;
    }
  }
};
