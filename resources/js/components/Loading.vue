<template>
  <div class="d-inline">
    <i class="fas fa-spinner fa-spin" v-show="loadShow"></i>
  </div>
</template>

<script>
import store from '../store'

export default {
  computed: {
    loadShow() {
      return store.state.load
    }
  },
  methods: {
    
  }
}
</script>