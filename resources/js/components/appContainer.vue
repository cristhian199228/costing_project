<template>
  <v-app id="inspire">
    <v-snackbar
      transition="scale-transition"
      top
      :color="snackbar.color"
      v-model="snackbar.show"
      timeout="3500"
    >
      {{ snackbar.message }}
    </v-snackbar>
    <v-navigation-drawer v-model="drawer" app>
      <template v-slot:prepend>
        <v-list-item two-line>
          <v-list-item-avatar>
            <v-icon large>mdi-account-circle</v-icon>
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title>{{ getName }}</v-list-item-title>
            <v-list-item-subtitle>{{ getRole }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </template>
      <v-divider></v-divider>
      <v-list v-if="getUser" dense>
        <v-list-item-group color="indigo darken-4">
          <v-list-item to="/">
            <v-list-item-icon>
              <v-icon>mdi-home</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Inicio</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <template>
            <v-list-item
              v-for="(modulo, i) in modulos"
              :key="i"
              :to="modulo.route"
            >
              <v-list-item-icon>
                <v-icon v-text="modulo.icon"></v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title v-text="modulo.title"></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </template>
          <v-list-item @click="logout">
            <v-list-item-icon>
              <v-icon>mdi-power</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Salir</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar color="indigo darken-4" dark app>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>COSTING PROJECT</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn v-if="getUser" @click="logout" icon
        ><v-icon>mdi-power</v-icon></v-btn
      >
    </v-app-bar>

    <v-main>
      <router-view class="ma-2"></router-view>
    </v-main>

    <v-footer color="indigo darken-4" dark app>
      <p class="subtitle-2 my-auto font-weight-medium">
        @INTERNATIONAL SOS {{ new Date().getFullYear() }}
      </p>
    </v-footer>
  </v-app>
</template>

<script>
import { mapState, mapGetters, mapActions } from "vuex";
export default {
  data() {
    return {
      drawer: null,
      modulos: [
        { title: "Paises", icon: "mdi-flag", route: "/paises" },
        { title: "Planilla Personal", icon: "mdi-account-supervisor", route: "/planilla" },
        { title: "Equipos", icon: "mdi-slot-machine", route: "/equipos" },
        { title: "Otros", icon: "mdi-arrange-bring-forward", route: "/otros" },
        { title: "Presupuestos", icon: "mdi-currency-usd", route: "/presupuestos" },
      ],
    };
  },
  methods: {
    ...mapActions("currentUser", ["logout"]),
  },
  created() {
    if (window.localStorage.hasOwnProperty("user")) {
      const user = JSON.parse(window.localStorage.getItem("user"));
      this.$store.commit("currentUser/SET_USER", user);
    }
  },
  computed: {
    ...mapState(["snackbar"]),
    ...mapGetters("currentUser", ["getRole", "getName", "getUser", "isAdmin"]),
  },
};
</script>