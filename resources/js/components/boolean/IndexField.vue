<template>
  <div class="text-center">
    <span
      :class="[dotClasses, statusClass]"
    />
  </div>
</template>

<script>
import Classes from './Classes';

export default {
  mixins: [Classes],

  props: ['resourceName', 'field']
};
</script>
