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
      return this.baseClasses.hideLabelInForms || this.field.hideLabelInForms;
    },

    hideLabelInDetail() {
      return this.baseClasses.hideLabelInDetail || this.field.hideLabelInDetail;
    },

    fieldClasses() {
      return this.baseClasses.fieldClasses || this.field.fieldClasses;
    },

    panelFieldClasses() {
      return this.baseClasses.panelFieldClasses || this.field.panelFieldClasses;
    },

    wrapperClasses() {
      return this.baseClasses.wrapperClasses || this.field.wrapperClasses;
    },

    labelClasses() {
      return this.baseClasses.labelClasses || this.field.labelClasses;
    },

    panelLabelClasses() {
      return this.baseClasses.panelLabelClasses || this.field.panelLabelClasses;
    },

    excerptClasses() {
      return this.baseClasses.excerptClasses || this.field.excerptClasses;
    },

    readOnly() {
      return this.baseClasses.readOnly || this.field.readOnly;
    },

    showContentLabel() {
      return this.baseClasses.showContentLabel || this.field.showContentLabel;
    },

    hideContentLabel() {
      return this.baseClasses.hideContentLabel || this.field.hideContentLabel;
    }
  }
};
