<template>
  <div>
    <v-dialog v-model="getDialogAntecedentes" persistent max-width="500px" scrollable>
      <v-card>
        <p class="display-1 mt-5 text-center">ANTECEDENTES</p>
        <v-card-text>
          <v-chip class="mb-1 mr-1" label dark color="red darken-2" v-for="(antecedente, i) in antecedentes" :key="i">{{ antecedente }}</v-chip>
        </v-card-text>
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
    eh: Object,
  },
  computed: {
    ...mapGetters('atenciones',['getDialogAntecedentes']),
    antecedentes(){
      const ae = [];
      for( const property in this.eh){
        if (this.eh[property]) {
          if (property === "close_contact") {
            ae.push("Contacto cercano con investigado covid");
          } else if (property === "covid_conversation") {
            ae.push("Conversación con investigado covid");
          } else if (property === "traveled_14days_ago") {
            ae.push("Viajó en los últimos 14 dias");
          } else if (property === "arrival_date") {
            ae.push(`Fecha de llegada viaje: ${this.eh[property]}`);
          } else if (property === "places_visited") {
            ae.push(`Lugares visitados: ${this.eh[property]}`);
          } else if (property === "conveyance") {
            ae.push(`Medio de transporte: ${this.eh[property]}`);
          } else if(property === "last_contact_date" ) {
            ae.push(`Fecha de ultimo contacto con investigado covid: ${this.eh[property]}`);
          }
        }
      }
      return ae;
    },
  },
  methods: {
    ...mapMutations('atenciones',['SHOW_VER_ANTECEDENTES_DIALOG']),
    close(){
      this.SHOW_VER_ANTECEDENTES_DIALOG(false)
    },
  },
}
</script>