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
        this.fetchComputedValues()
      }
    }
  },

  methods: {
    fetchComputedValues() {
      if (this.field.compute) {
        this.getComputedValue()
          .then(({ data }) => {
            this.computedValueReceived(data)
          })
      }

      if (this.field.computeOptions) {
        const computeOptions = true
        this.getComputedValue(computeOptions)
          .then(({ data }) => {
            this.computedOptionsReceived(data)
          })
      }

    },

    getComputedValue(computeOptions = false) {
      return Nova.request()
        .post(
          `/nova-r64-api/${this.resourceName}/computed/${this.field.attribute}`,
          {
            parentAttribute: this.parentAttribute,
            resourceId: this.resourceId,
            values: this.rowValues,
            computeOptions
          }
        )
    },

    computedValueReceived(data) {
      console.warn('You should implement "computedValueReceived" method in your Computable component', data)
    },

    computedOptionsReceived(data) {
      console.warn('You should implement "computedOptionsReceived" method in your Computable component', data)
    }
  }
}
