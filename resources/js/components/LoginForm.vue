<template>
  <div>
    <v-row no-gutters>
      <v-col
        cols="12"
        offset-lg="3"
        lg="6"
        offset-md="2"
        md="8"
        offset-sm="1"
        sm="10"
        xs="12"
      >
        <validation-observer ref="observer">
          <v-card elevation="5">
            <p class="text-center display-1 pt-5">COSTING PROJECT</p>
            <p
              class="
                subtitle-1
                font-weight-light
                ma-5
                indigo--text
                text--darken-4 text-center
              "
            >
              Ingrese su Usuario y Contraseña
            </p>
            <v-divider></v-divider>
            <v-card-text>
              <v-row>
                <v-col
                  cols="12"
                  offset-lg="1"
                  lg="10"
                  offset-md="1"
                  md="10"
                  offset-sm="1"
                  sm="10"
                  xs="12"
                >
                  <v-text-field
                    v-model.trim="form.numero_documento"
                    prepend-inner-icon="mdi-account-box"
                    label="USUARIO"
                    outlined
                    dense
                  ></v-text-field>

                  <v-text-field
                    v-model="form.fecha_nacimiento"
                    label="CONTRASEÑÁ"
                    prepend-inner-icon="mdi-key"
                    outlined
                    type="password"
                    dense
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="primary" class="text--white" @click="ingresar"
                >INGRESAR</v-btn
              >
            </v-card-actions>
          </v-card>
        </validation-observer>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  data() {
    return {
      form: {
        numero_documento: null,
        fecha_nacimiento: null,
      },
      menu: false,
      activePicker: null,
    };
  },
  methods: {
    ...mapActions("currentUser", ["login"]),
    ingresar() {
      this.login(this.form);
    },
  },
  watch: {
    menu(val) {
      val && setTimeout(() => (this.activePicker = "YEAR"));
    },
  },
};
</script>