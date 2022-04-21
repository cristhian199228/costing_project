<template>
  <div>
    <v-card outlined>
      <v-breadcrumbs :items="items"></v-breadcrumbs>
      <p class="display-1 mt-5 text-center">OTROS</p>
      <v-divider></v-divider>
      <v-card-text>
       <v-data-table
    :headers="headers"
    :items="desserts"
    :items-per-page="5"
    class="elevation-1"
  ></v-data-table>
      </v-card-text>
      <v-btn @click="storeTracing" color="pink" fixed bottom right fab dark
        ><v-icon>mdi-plus</v-icon></v-btn
      >
      <paginate :store="store" :collection="collection" />
      <dialog-save-tracing :action="action" />
    </v-card>
  </div>
</template>
<script>
  export default {
    data () {
      return {
        headers: [
          {
            text: 'Descripcion',
            align: 'start',
            sortable: false,
            value: 'name',
          },
          { text: 'Monto', value: 'calories' },
        ],
        desserts: [
        ],
      }
    },
  }
</script>
