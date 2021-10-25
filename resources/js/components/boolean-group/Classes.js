export default {
  computed: {
    dotClasses() {
      return this.field.dotClasses || 'inline-block rounded-full w-2 h-2 mr-1';
    },

    statusClass() {
      if (this.field.value) {
        return this.field.successClass || 'bg-success';
      }
      return this.field.dangerClass || 'bg-danger';
    }
  }
};
