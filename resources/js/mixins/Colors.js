export default {
  computed: {
    applyColor() {
      if (!this.field.colors) return ''
      let value = String(this.field.value)
      const isNegativeMoneyFormat = value.includes('(') && value.includes('(')
      value = value.replace(/[^\d.-]/g, '') // remove non numeric chars
      const isNegative = value < 0
      return isNegativeMoneyFormat || isNegative
        ? 'text-danger'
        : 'text-success'
    }
  }
}
