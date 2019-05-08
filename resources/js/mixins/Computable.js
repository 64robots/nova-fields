export default {
  props: {
    rowValues: {
      type: Array,
      default: () => []
    }
  },

  watch: {
    rowValues: {
      immediate: true,
      deep: true,
      handler() {
        this.fetchComputedValue()
      }
    }
  },

  methods: {
    fetchComputedValue() {
      Nova.request()
        .post(
          `/nova-r64-api/${this.resourceName}/computed/${this.field.attribute}`,
          {
            values: this.rowValues
          }
        )
        .then(({ data }) => {
          this.computedValueReceived(data)
        })
    },

    computedValueReceived(data) {
      console.warn('You should implement "computedValueReceived" method in your Computable component', data)
    }
  }
}
