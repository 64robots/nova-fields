export default {
  props: {
    baseClasses: {
      type: Object,
      default: () => ({
        hideLabelInForms: false,
        hideLabelInDetail: false,
        fieldClasses: null,
        panelFieldClasses: null,
        wrapperClasses: null,
        labelClasses: null,
        panelLabelClasses: null,
        excerptClasses: null,
        readOnly: null,
        showContentLabel: null,
        hideContentLabel: null
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
