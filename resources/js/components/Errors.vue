<template>
  <div class="container" v-if="hasErrors">
    <div class="row">
      <div class="col-sm-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="clearErrors">
            <span aria-hidden="true">&times;</span>
          </button>
          <p v-for="(item, index) in errors" :key="index">* {{ item[0] }}</p>
        </div>
      </div> 
    </div>
  </div>
</template>

<script>
import store from '../store'

export default {
  data() {
    return {
    }
  },
  computed: {
    errors() {
      return store.state.errors
    },
    hasErrors() {
      return store.state.errors !== null
    }
  },
  methods: {
    //  Clear Errors
    clearErrors() {
      store.state.errors = null
    }
  }
}
</script>