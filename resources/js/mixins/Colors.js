export default {
  computed: {
    applyColor() {
      if (!this.field.colors) return '';
      const value = this.field.value.replace(/[^\d.-]/g, ''); // remove non numeric chars
      return value < 0 ? 'text-danger' : 'text-success';
    }
  }
};
