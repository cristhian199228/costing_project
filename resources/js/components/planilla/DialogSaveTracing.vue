<template>
  <div>
    <v-dialog v-model="getDialog" max-width="600px">
      <v-card>
        <v-card-title>NUEVA PLANILLA/v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-text-field
            :search-input.sync="search"
            :items="getPacientes"
            label=" DESCRIPCION"
            hide-no-data
            hide-selected
            clearable
            item-text="full_name"
            item-value="idpacientes"
            v-model="form.patient_id"
          >
          
          </v-text-field>
          <v-text-field
            :search-input.sync="search"
            :items="getPacientes"
            label=" SUELDO BASE"
            hide-no-data
            hide-selected
            clearable
            item-text="full_name"
            item-value="idpacientes"
            v-model="form.patient_id"
          >
          
          </v-text-field>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="normal" text @click="close">CANCELAR</v-btn>
          <v-btn color="primary" @click="guardar">GUARDAR</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters, mapMutations, mapActions } from 'vuex'

export default {
  props: {
    action: Object
  },
  data() {
    return {
      search: null,
      form: {
        patient_id: null,
        days: 7,
      },
      criterios: ['full_name', 'numero_documento']
    }
  },
  computed: {
    ...mapGetters('seguimientos',['getDialog']),
    ...mapGetters(['getPacientes']),
  },
  methods: {
    ...mapMutations('seguimientos',['SHOW_SAVE_TRACING_DIALOG']),
    ...mapActions(['searchPacientes']),
    close(){
      this.SHOW_SAVE_TRACING_DIALOG(false)
    },
    guardar() {
      this.$store.dispatch(`seguimientos/store`, this.form)
    },
  },
  watch: {
    search(buscar) {
      this.searchPacientes(buscar)
    }
  }
}
</script>