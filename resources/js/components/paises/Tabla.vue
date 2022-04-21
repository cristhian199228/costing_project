<template>
  <div>
    <p class="display-1 mt-5 text-center">PAISES</p>
    <v-divider></v-divider>
    <v-data-table
      :items="getPaises"
      :headers="headers"
      :items-per-page="15"
      hide-default-footer
    >
      <template v-slot:item.acciones="{ item }">
        <v-icon small class="mr-2" @click="abrirEditarPais(item)">
          mdi-pencil
        </v-icon>
        <v-icon small @click="EliminarPais(item)"> mdi-delete </v-icon>
      </template>
    </v-data-table>
    <paginate :store="store" :collection="collection" />

    <dialog-pais />
  </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
import DialogPais from "./DialogPais";
import paginate from "../shared/paginate";

export default {
  components: {
    DialogPais,
    paginate,
  },
  data() {
    return {
      items: [
        {
          text: "Inicio",
          disabled: false,
          to: "/paciente",
        },
      ],
      headers: [
        { text: "Descripcion", value: "descripcion", sortable: false },
        { text: "Moneda", value: "moneda", sortable: false },
        { text: "Acciones", value: "acciones", sortable: false },
      ],
      action: null,
      store: "atenciones",
      collection: "getPaises",
    };
  },
  methods: {
    ...mapActions("paises", ["initPaises"]),
    ...mapMutations("paises", ["SHOW_PAIS_DIALOG", "SET_ID_PAIS_EDITADO"]),
    abrirEditarPais(tracing) {
      this.SHOW_PAIS_DIALOG(true);
      this.SET_ID_PAIS_EDITADO(tracing.id);
    },
    EliminarPais(tracing) {
      alert("esta dseguro de eliminar este pais?");
    },
  },
  computed: {
    ...mapGetters("paises", ["getPaises"]),
  },
  created() {
    this.initPaises();
  },
};
</script>