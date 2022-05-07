<template>
  <div>
    <v-card outlined>
      <v-breadcrumbs :items="items"></v-breadcrumbs>
      <p class="display-1 mt-5 text-center">PLANILLA</p>
           
      <v-divider></v-divider>
      <v-card-text>
        <v-data-table
          :items="tracingsPlanilla"
          :headers="headers"
          :items-per-page="15"
          hide-default-footer
        >
          <template v-slot:header.puesto="{ header }">
            <v-text-field
              v-model="buscar"
              label="Descripcion"
              append-icon="mdi-magnify"
              @click:append="getTracingsPlanilla"
              @keyup.enter="getTracingsPlanilla"
              clearable
              @click:clear="clearBuscarField"
              single-line
            ></v-text-field>
          </template>
          <template v-slot:item.estado="{ item }">
            <v-chip
              :color="item.estado ? 'green darken-2' : 'orange darken-2'"
              label
              small
              dark
            >
              {{ item.estado ? "ACTIVO" : "INACTIVO" }}</v-chip
            >
          </template>
          <template v-slot:item.acciones="{ item }">
            <v-icon small class="mr-2" @click="abrirBitacora(item)">
              mdi-pencil
            </v-icon>
            <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
          </template>
        </v-data-table>
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
import { mapActions, mapGetters, mapMutations, mapState } from "vuex";
import paginate from "../shared/paginate";
import DialogSaveTracing from "./DialogSaveTracing";

export default {
  components: {
    paginate,
    DialogSaveTracing,
  },
  data() {
    return {
      items: [
        {
          text: "Seguimientos",
          disabled: false,
          to: "/seguimientos",
        },
      ],
      headers: [
        { text: "Descripcion", value: "puesto", sortable: false },
        { text: "Estado", value: "estado", sortable: false },
        { text: "Acciones", value: "acciones", sortable: false },
      ],
      store: "seguimientos",
      collection: "getTracingsPlanilla",
      action: null,
      estados: [
        { value: 1, text: "EN PROCESO" },
        { value: 2, text: "FINALIZADO" },
      ],
      menu: false,
      dates: [
        new Date().toISOString().substr(0, 10),
        new Date().toISOString().substr(0, 10),
      ],
    };
  },
  methods: {
    ...mapMutations("seguimientos", ["SHOW_SAVE_TRACING_DIALOG"]),
    ...mapActions(["getTracingsPlanilla"]),
    storeTracing() {
      this.SHOW_SAVE_TRACING_DIALOG(true);
    },
    abrirBitacora(tracing) {
      this.$store.commit("seguimientos/SET_TRACING_ID", tracing.id);
      this.$router.push({
        name: "Bitacora",
        params: {
          tracing_id: tracing.id,
        },
      });
    },
    changeEstado(estado) {
      this.$store.commit("seguimientos/SET_ESTADO_FILTER", estado);
      this.getTracingsPlanilla();
    },
    guardarFechas() {
      this.$refs.menu.save(this.dates);
      this.$store.commit("seguimientos/SET_FECHAS_FILTER", this.dates);
      this.getTracingsPlanilla();
    },
    clearBuscarField() {
      this.$store.commit("seguimientos/SET_BUSCAR_FILTER", null);
      this.getTracingsPlanilla();
    },
    deleteItem(item){
       this.$store.dispatch(`seguimientos/destroy`, item);
    }
  },
  computed: {
    ...mapGetters("seguimientos", ["tracingsPlanilla"]),
    ...mapState("seguimientos", ["filters"]),
    dateStr() {
      return this.dates.join(" ~ ");
    },
    buscar: {
      get() {
        return this.$store.getters["seguimientos/getFilters"].buscar;
      },
      set(val) {
        this.$store.commit("seguimientos/SET_BUSCAR_FILTER", val);
      },
    },
  },
  created() {
    this.getTracingsPlanilla();
  },
};
</script>