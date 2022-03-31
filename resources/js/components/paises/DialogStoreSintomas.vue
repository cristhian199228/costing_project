<template>
  <div>
    <v-dialog v-model="getDialog" persistent max-width="600px" scrollable>
      <v-card>
        <v-card-title>GUARDAR SINTOMAS</v-card-title>
        <v-divider></v-divider>
        <v-card-text style="max-height: 550px;">
          <v-checkbox v-model="symptoms.cough" label="Tos" hide-details></v-checkbox>
          <v-checkbox v-model="symptoms.throat_pain" label="Dolor de garganta" hide-details></v-checkbox>
          <v-checkbox v-model="symptoms.nasal_congestion" label="Congestion nasal" hide-details></v-checkbox>
          <v-checkbox v-model="symptoms.difficulty_breathing" label="Dificultad para respirar" hide-details></v-checkbox>
          <v-checkbox v-model="symptoms.fever" label="Fiebre" hide-details></v-checkbox>
          <v-checkbox v-model="symptoms.general_discomfort" label="Malestar general" hide-details></v-checkbox>
          <v-checkbox v-model="symptoms.diarrhea" label="Diarrea" hide-details></v-checkbox>
          <v-checkbox v-model="symptoms.nausea_vomiting" label="Náuseas y vómitos" hide-details></v-checkbox>
          <v-checkbox v-model="symptoms.headache" label="Cefálea" hide-details></v-checkbox>
          <v-checkbox v-model="symptoms.irritability" label="Irritabilidad y confusión" hide-details></v-checkbox>
          <v-checkbox v-model="symptoms.short_breath" label="Falta de aliento" hide-details></v-checkbox>
          <v-checkbox v-model="symptoms.anosmia_ausegia" label="Anosmia o Ausegia" hide-details></v-checkbox>
          <v-text-field v-model="symptoms.medication_treat" label="Toma algun medicamento para tratar algun sintoma mencionado"></v-text-field>
          <v-text-field v-model="symptoms.immune_condition" label="Tiene alguna condición que debilite su sistema inmune"></v-text-field>
          <v-text-field v-model="symptoms.others" label="Otros síntomas"></v-text-field>
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
import { mapGetters, mapMutations } from 'vuex'
export default {
  props: {
    symptoms: Object,
    action: String,
  },
  computed: {
    ...mapGetters('sintomas',['getDialog'])
  },
  methods: {
    ...mapMutations('sintomas',['SHOW_SYMPTOMS_DIALOG']),
    close(){
      this.SHOW_SYMPTOMS_DIALOG(false)
    },
    guardar() {
      this.$store.dispatch(`sintomas/${this.action}`, this.symptoms)
    },
  }
}
</script>