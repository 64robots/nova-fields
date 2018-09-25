export default {
  data() {
    return {
      values: []
    };
  },

  created() {
    if (!this.field.value) return;

    let values = this.field.value || [];

    if (typeof this.field.value === 'string') {
      values = JSON.parse(this.field.value);
    }

    values.forEach(value => this.addItemToRow(value));
  },

  methods: {
    addItemToRow(obj) {
      const row_id = Math.random()
        .toString(36)
        .substr(2, 9);
      Object.assign(obj, { row_id });

      setTimeout(() => {
        Object.keys(obj).forEach(key => {
          if (key !== 'row_id') {
            const ref = this.$refs[`${row_id}${key}`];
            if (!ref) return;

            const element = ref[0];
            this.$watch(
              () => {
                return element.value;
              },
              val => {
                obj[key] = val;
              }
            );
          }
        });
      }, 100);

      this.$set(this.values, this.values.length, obj);
    }
  }
};
