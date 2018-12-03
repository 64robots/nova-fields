<template>
  <div :class="field.indexClasses">
    <span :class="[dotClasses, statusClass]"/>
  </div>
</template>

<script>
import Classes from './Classes'

export default {
  mixins: [Classes],

  props: ['resourceName', 'field']
}
</script>
