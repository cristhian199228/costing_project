<template>
  <div>
    <v-data-table
      :headers="headersDefecto"
      :items="dessertsDefecto"
      sort-by="calories"
      class="elevation-1"
      hide-default-footer
    >
      <template v-slot:no-data>
        <v-btn color="primary" @click="initialize"> Reset </v-btn>
      </template>
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Presupuesto de Prueba</v-toolbar-title>

          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          Duración del proyecto
          <v-text-field
            type="number"
            step="any"
            min="0"
            ref="input"
            :rules="[numberRule]"
            v-model.number="meses"
            class="mx-6"
          ></v-text-field>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        {{
          meses *
          item.cantidad *
          (item.fat +
            item.calories +
            item.protein +
            item.carbs +
            item.iron +
            item.iron2 +
            item.iron3)
        }}
      </template>

      <template v-slot:item.cantidad="{ item }">
        <v-text-field
          type="number"
          step="any"
          min="1"
          ref="input"
          :rules="[numberRule]"
          v-model.number="item.cantidad"
        ></v-text-field>
      </template>
      <template v-slot:no-data>
        <v-btn color="primary" @click="initialize"> Reset </v-btn>
      </template>
    </v-data-table>
    <v-divider></v-divider>
    <v-data-table
      :headers="headers"
      :items="desserts"
      sort-by="calories"
      class="elevation-1"
    >
      <template v-slot:no-data>
        <v-btn color="primary" @click="initialize"> Reset </v-btn>
      </template>

      <template v-slot:item.actions="{ item }">
        {{
          meses *
          item.cantidad *
          (item.fat +
            item.calories +
            item.protein +
            item.carbs +
            item.iron +
            item.iron2 +
            item.iron3)
        }}
      </template>

      <template v-slot:item.cantidad="{ item }">
        <v-text-field
          type="number"
          step="any"
          min="1"
          ref="input"
          :rules="[numberRule]"
          v-model.number="item.cantidad"
        ></v-text-field>
      </template>
      <template v-slot:item.calories="{ item }">
        <v-text-field
          type="number"
          step="any"
          min="1"
          ref="input"
          :rules="[numberRule]"
          v-model.number="item.calories"
        ></v-text-field>
      </template>
      <template v-slot:no-data>
        <v-btn color="primary" @click="initialize"> Reset </v-btn>
      </template>
    </v-data-table>
  </div>
</template>
<script>
export default {
  data: () => ({
    meses: 12,
    dialog: false,
    dialogDelete: false,
    headers: [
      {
        text: "Puesto",
        align: "start",
        sortable: false,
        value: "name",
      },
      { text: "Cantidad", value: "cantidad" },
      { text: "Basico", value: "calories" },
      { text: "Vacaciones", value: "vacaciones" },
      { text: "Gratificacion", value: "gratificacion" },
      { text: "CTS", value: "cts" },
      { text: "Essalud", value: "essalud" },
      { text: "SCTR - Salud", value: "sctr_salud" },
      { text: "SCTR - Pensión", value: "sctr_pension"},
      { text: "Seguro Vida Ley", value: "seguro_vida_ley" },
      { text: "Contribucion Vacaciones", value: "cont_vacaciones" },
      { text: "Contribucion Gratificacion", value: "cont_gratificacion" },
      { text: "Otros", value: "otros" },
      { text: "Gastos Administrativos", value: "gastos_administrativos" },
      { text: "Utilidad", value: "utilidad" },

      { text: "Total", value: "actions", sortable: false },
    ],
    headersDefecto: [
      { text: "Vacaciones", value: "vacaciones", sortable: false },
      { text: "Gratificacion", value: "gratificacion", sortable: false },
      { text: "CTS", value: "cts", sortable: false },
      { text: "Essalud", value: "essalud", sortable: false },
      { text: "SCTR - Salud", value: "sctr_salud", sortable: false },
      { text: "SCTR - Pensión", value: "sctr_pension", sortable: false },
      { text: "Seguro Vida Ley", value: "seguro_vida_ley", sortable: false },
      {
        text: "Contribucion Vacaciones",
        value: "cont_vacaciones",
        sortable: false,
      },
      {
        text: "Contribucion Gratificacion",
        value: "cont_gratificacion",
        sortable: false,
      },
      { text: "Otros", value: "otros", sortable: false },
      {
        text: "Gastos Administrativos",
        value: "gastos_administrativos",
        sortable: false,
      },
      { text: "Utilidad", value: "utilidad", sortable: false },
    ],
    desserts: [],
    dessertsDefecto: [],
    editedIndex: -1,
    editedItem: {
      name: "",
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
    },
    defaultItem: {
      name: "",
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
    },
    vacaciones_d: 8.33,
    gratificacion_d: 16.66,
    cts_d: 8.33,
    essalud_d: 9.0,
    sctr_salud_d: 0.85,
    sctr_pension_d: 1,
    seguro_vida_ley_d: 1,
    cont_vacaciones_d: 3.54,
    cont_gratificacion_d: 2.89,
    otros_d: 1.4,
    gastos_administrativos_d: 10.0,
    utilidad_d: 35.0,
    basico_tecnico_it: 2080.0,
    basico_medico_asis: 8620.0,
    basico_enfermera_asistencial: 5376.0,
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    initialize() {
      this.desserts = [
        {
          name: "Tecnico IT",
          calories: this.basico_tecnico_it,
          vacaciones: this.basico_tecnico_it * (this.vacaciones_d / 100),
          gratificacion: this.basico_tecnico_it * (this.gratificacion_d / 100),
          cts: this.basico_tecnico_it * (this.cts_d / 100),
          essalud: this.basico_tecnico_it * (this.essalud_d / 100),
          sctr_salud: this.basico_tecnico_it * (this.sctr_salud_d / 100),
          sctr_pension: this.basico_tecnico_it * (this.sctr_pension_d / 100),
          seguro_vida_ley: this.basico_tecnico_it * (this.seguro_vida_ley_d/ 100),
          cont_vacaciones: this.basico_tecnico_it * (this.cont_vacaciones_d/ 100),
          cont_gratificacion: this.basico_tecnico_it * (this.cont_gratificacion_d/ 100),
          otros: this.basico_tecnico_it * (this.otros_d/ 100),
          gastos_administrativos: this.basico_tecnico_it * (this.gastos_administrativos_d/ 100),
          utilidad: this.basico_tecnico_it * (this.utilidad_d / 100),
           cantidad: 1,
        },
        {
          name: "Medico Asistencial",
          calories: this.basico_medico_asis,
          vacaciones: this.basico_medico_asis * (this.vacaciones_d / 100),
          gratificacion: this.basico_medico_asis * (this.gratificacion_d / 100),
          cts: this.basico_medico_asis * (this.cts_d / 100),
          essalud: this.basico_medico_asis * (this.essalud_d / 100),
          sctr_salud: this.basico_medico_asis * (this.sctr_salud_d / 100),
          sctr_pension: this.basico_medico_asis * (this.sctr_pension_d / 100),
          seguro_vida_ley: this.basico_medico_asis * (this.seguro_vida_ley_d/ 100),
          cont_vacaciones: this.basico_medico_asis * (this.cont_vacaciones_d/ 100),
          cont_gratificacion: this.basico_medico_asis * (this.cont_gratificacion_d/ 100),
          otros: this.basico_medico_asis * (this.otros_d/ 100),
          gastos_administrativos: this.basico_medico_asis * (this.gastos_administrativos_d/ 100),
          utilidad: this.basico_medico_asis * (this.utilidad_d / 100),
           cantidad: 1,
        },
        {
          name: "Enfermera Asistencial",
          calories: this.basico_enfermera_asistencial,
          vacaciones: this.basico_enfermera_asistencial * (this.vacaciones_d / 100),
          gratificacion: this.basico_enfermera_asistencial* (this.gratificacion_d / 100),
          cts: this.basico_enfermera_asistencial * (this.cts_d / 100),
          essalud: this.basico_enfermera_asistencial* (this.essalud_d / 100),
          sctr_salud: this.basico_enfermera_asistencial * (this.sctr_salud_d / 100),
          sctr_pension: this.basico_enfermera_asistencial * (this.sctr_pension_d / 100),
          seguro_vida_ley: this.basico_enfermera_asistencial * (this.seguro_vida_ley_d/ 100),
          cont_vacaciones: this.basico_enfermera_asistencial * (this.cont_vacaciones_d/ 100),
          cont_gratificacion: this.basico_enfermera_asistencial * (this.cont_gratificacion_d/ 100),
          otros: this.basico_enfermera_asistencial * (this.otros_d/ 100),
          gastos_administrativos: this.basico_enfermera_asistencial * (this.gastos_administrativos_d/ 100),
          utilidad: this.basico_enfermera_asistencial* (this.utilidad_d / 100),
           cantidad: 1,
        },
      ];
      this.dessertsDefecto = [
        {
          vacaciones: this.vacaciones_d + "%",
          gratificacion: this.gratificacion_d + "%",
          cts: this.cts_d + "%",
          essalud: this.essalud_d + "%",
          sctr_salud: this.sctr_salud_d + "%",
          sctr_pension: this.sctr_pension + "%",
          seguro_vida_ley: this.seguro_vida_ley_d + "%",
          cont_vacaciones: this.vacaciones_d + "%",
          cont_gratificacion: this.gratificacion_d + "%",
          otros: this.otros_d + "%",
          gastos_administrativos: this.gastos_administrativos_d + "%",
          utilidad: this.utilidad_d + "%",
        },
      ];
    },

    editItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      this.desserts.splice(this.editedIndex, 1);
      this.closeDelete();
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    save() {
      if (this.editedIndex > -1) {
        Object.assign(this.desserts[this.editedIndex], this.editedItem);
      } else {
        this.desserts.push(this.editedItem);
      }
      this.close();
    },
  },
};
</script>