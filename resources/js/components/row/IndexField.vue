<template>
  <div class="p-4">
    <a v-if="show" href="#" @click="show = false">Hide {{ field.name }}</a>
    <a v-else href="#" @click="show = true">Show {{ field.name }}</a>
    <TableWrapper v-if="show" :field="field" />
  </div>
</template>

<script>
import TableWrapper from './TableWrapper.vue'

export default {
  props: ['resourceName', 'field'],

  components: { TableWrapper },

  data() {
    return {
      show: false
    }
  }
}
</script>
