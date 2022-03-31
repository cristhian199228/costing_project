<template>
  <div class="text-center">
    <v-pagination
      v-model="currentPage"
      :length="lastPage"
      :total-visible="6"
      color="indigo darken-4"
      circle
    ></v-pagination>
  </div>
</template>

<script>
export default {
  props: ['store', 'collection'],
  computed: {
    currentPage: {
      get() {
        return this.$store.state[this.store].data.current_page;
      },
      set (val) {
        this.$store.commit(this.store + '/SET_CURRENT_PAGE', val);
      }
    },
    lastPage: {
      get() {
        return this.$store.state[this.store].data.last_page;
      }
    }
  },
  watch: {
    currentPage(page) {
      this.$store.dispatch(`${this.collection}`, page);
    }
  }
}
</script>