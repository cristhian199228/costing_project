<template>
  <div>
    <v-card outlined>
      <v-breadcrumbs :items="items"></v-breadcrumbs>
      <p class="display-1 mt-5 text-center">PRESUPUESTOS</p>
      <v-divider></v-divider>
      <v-card-text>
        <v-data-table
          :items="tracings"
          :headers="headers"
          :items-per-page="15"
          hide-default-footer
        >
          <template v-slot:header.estado="{ header }">
            <v-select
              dense
              v-model="filters.estado"
              label="Estado"
              :items="estados"
              item-value="value"
              item-text="text"
              clearable
              @change="changeEstado"
            ></v-select>
          </template>
          <template v-slot:header.date="{ header }">
            <v-menu
              ref="menu"
              v-model="menu"
              :close-on-content-click="false"
              :return-value.sync="dates"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="dateStr"
                  label="Fechas"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker v-model="dates" range no-title scrollable>
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                <v-btn
                  text
                  color="primary"
                  @click="guardarFechas"
                  :disabled="dates.length < 2"
                  >OK</v-btn
                >
              </v-date-picker>
            </v-menu>
          </template>
          <template v-slot:item.estado="{ item }">
            <v-chip
              :color="item.estado ? 'green darken-2' : 'orange darken-2'"
              label
              small
              dark
            >
              {{ item.estado ? "FINALIZADO" : "EN PROCESO" }}</v-chip
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
        {
          text: "Fecha Creación",
          value: "date",
          align: "start",
          sortable: false,
        },
        { text: "Descripcion", value: "descripcion", sortable: false },

        { text: "Estado", value: "estado", sortable: false },
        { text: "Duracion(meses)", value: "duracion", sortable: false },
        { text: "Acciones", value: "acciones", sortable: false },
      ],
      store: "seguimientos",
      collection: "getTracings",
      action: null,
      estados: [
        { value: 0, text: "EN PROCESO" },
        { value: 1, text: "FINALIZADO" },
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
    ...mapActions(["getTracings"]),
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
      this.getTracings();
    },
    guardarFechas() {
      this.$refs.menu.save(this.dates);
      this.$store.commit("seguimientos/SET_FECHAS_FILTER", this.dates);
      this.getTracings();
    },
    deleteItem(item){
       this.$store.dispatch(`seguimientos/destroy`, item);
    },
    clearBuscarField() {
      this.$store.commit("seguimientos/SET_BUSCAR_FILTER", null);
      this.getTracings();
    },
  },
  computed: {
    ...mapGetters("seguimientos", ["tracings"]),
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
    this.getTracings();
  },
};
</script>