<template>
  <div>
    <v-dialog v-model="getPaisesDialog" persistent max-width="600px" scrollable>
      <v-card>
        <v-card-title>EDITAR PAIS</v-card-title>
        <v-divider></v-divider>
        <v-card-text style="max-height: 550px">
          <v-text-field label="PAIS" clearable></v-text-field>
          <v-text-field label="MONEDA" clearable></v-text-field>
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
import { mapGetters, mapMutations } from "vuex";
export default {
  computed: {
    ...mapGetters("paises", ["getPaisesDialog"]),
  },
  methods: {
    ...mapActions("paises", ["initPaisEditado"]),
    ...mapMutations("paises", ["SHOW_PAIS_DIALOG"]),
    close() {
      this.SHOW_PAIS_DIALOG(false);
    },

    guardar() {
      this.SHOW_PAIS_DIALOG(false);
    },
  },
 created() {
    this.initPaisEditado();
  },
};
</script>