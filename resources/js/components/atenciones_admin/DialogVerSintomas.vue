<template>
  <div>
    <v-dialog v-model="getDialogSintomas" persistent max-width="500px" scrollable>
      <v-card>
        <p class="display-1 mt-5 text-center">SINTOMAS</p>
        <v-card-text>
          <v-chip class="mb-1 mr-1" label dark color="red darken-2" v-for="(sintoma, i) in sintomas" :key="i">{{ sintoma }}</v-chip>
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
    symptoms: Object,
  },
  computed: {
    ...mapGetters('atenciones',['getDialogSintomas']),
    sintomas() {
      const dc = [];
      for( const property in this.symptoms){
        if (this.symptoms[property]) {
          if (property === "cough") {
            dc.push("Tos");
          } else if (property === "throat_pain") {
            dc.push("Dolor de garganta");
          } else if (property === "nasal_congestion") {
            dc.push("Congestión nasal");
          } else if (property === "difficulty_breathing") {
            dc.push("Dificultad para respirar");
          } else if (property === "fever") {
            dc.push("Fiebre");
          } else if (property === "general_discomfort") {
            dc.push("Malestar general");
          } else if (property === "diarrhea") {
            dc.push("Diarrea");
          } else if (property === "nausea_vomiting") {
            dc.push("Nauseas/Vomitos");
          } else if (property === "headache") {
            dc.push("Cefálea");
          } else if (property === "irritability") {
            dc.push("Irritabilidad/Confusion");
          } else if (property === "short_breath") {
            dc.push("Falta de aliento");
          } else if (property === "anosmia_ausegia") {
            dc.push("Anosmia/Ausegia");
          } else if  (property === "others") {
            dc.push(`Otros: ${this.symptoms[property]}`);
          } else if (property === "medication_treat") {
            dc.push(`medicamento: ${this.symptoms[property]}`);
          } else if (property === "immune_condition") {
            dc.push(`Condicion que debilita el sistema inmune: ${this.symptoms[property]}`);
          }
        }
      }
      return dc;
    }
  },
  methods: {
    ...mapMutations('atenciones',['SHOW_VER_SINTOMAS_DIALOG']),
    close(){
      this.SHOW_VER_SINTOMAS_DIALOG(false)
    },
  },
}
</script>