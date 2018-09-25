export default {
  props: ['field'],

  data() {
    return {
      fields: []
    };
  },

  created() {
    this.fields = this.field.fields;
  }
};
