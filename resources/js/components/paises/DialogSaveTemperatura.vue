<template>
  <div>
    <v-dialog v-model="getDialog" persistent max-width="600px">
      <v-card>
        <v-card-title>GUARDAR TEMPERATURA</v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-row no-gutters justify="center">
            <v-col class="text-center mb-2" cols="3">
              <v-btn outlined color="blue darken-4" @click="ADD_ENTERO" :disabled="getEntero >= 42"><v-icon>mdi-plus</v-icon></v-btn>
            </v-col>
            <v-col class="text-center" cols="1"></v-col>
            <v-col class="text-center" cols="3">
              <v-btn outlined color="blue darken-4" @click="ADD_DECIMAL" :disabled="getDecimal >= 9"><v-icon>mdi-plus</v-icon></v-btn>
            </v-col>
          </v-row>
          <v-row no-gutters justify="center">
            <v-col class="text-center" cols="3">
              <h1 class="display-1">{{ getEntero }}</h1>
            </v-col>
            <v-col class="text-center" cols="1">
              <h1 class="display-1">.</h1>
            </v-col>
            <v-col class="text-center" cols="3">
              <h1 class="display-1">{{ getDecimal }}</h1>
            </v-col>
          </v-row>
          <v-row no-gutters justify="center">
            <v-col class="text-center" cols="3">
              <v-btn outlined color="blue darken-4" @click="SUBTRACT_ENTERO" :disabled="getEntero <= 30"><v-icon>mdi-minus</v-icon></v-btn>
            </v-col>
            <v-col class="text-center" cols="1"></v-col>
            <v-col class="text-center" cols="3">
              <v-btn outlined color="blue darken-4" @click="SUBTRACT_DECIMAL" :disabled="getDecimal <= 0"><v-icon>mdi-minus</v-icon></v-btn>
            </v-col>
          </v-row>
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
    action: String,
  },
  computed: {
    ...mapGetters('temperatura',['getDialog','getDecimal','getEntero']),
  },
  methods: {
    ...mapMutations('temperatura',['SHOW_TEMPERATURE_DIALOG','ADD_DECIMAL','ADD_ENTERO','SUBTRACT_DECIMAL','SUBTRACT_ENTERO']),
    close(){
      this.SHOW_TEMPERATURE_DIALOG(false)
    },
    guardar() {
      this.$store.dispatch(`temperatura/${this.action}`)
    },
  }
}
</script>