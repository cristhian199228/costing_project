<template>
  <div>
    <v-dialog v-model="getDialogContactosDirectos" persistent max-width="700px" scrollable>
      <v-card>
        <v-card-title><p class="display-1 mt-5 text-center">CONTACTOS DIRECTOS</p></v-card-title>
        <v-data-table :headers="headers" :items="contactos">
        </v-data-table>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="close">CERRAR</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex'

export default {
  props: {
    contactos: Array,
  },
  data() {
    return {
      headers: [
        { text: 'Nombre Completo', value: 'full_name', align: 'start', sortable: false},
        { text: 'Celular', value: 'phone', sortable: false},
        { text: 'Puesto', value: 'position', sortable: false},
        { text: 'Detalle', value: 'detail', sortable: false},
      ],
    }
  },
  computed: {
    ...mapGetters('atenciones',['getDialogContactosDirectos']),
  },
  methods: {
    ...mapMutations('atenciones',['SHOW_VER_CONTACTOS_DIRECTOS_DIALOG']),
    close(){
      this.SHOW_VER_CONTACTOS_DIRECTOS_DIALOG(false)
    },
  },
}
</script>