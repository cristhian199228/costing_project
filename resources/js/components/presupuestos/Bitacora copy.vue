<template>
<div>
  <v-data-table
    :headers="headers"
    :items="desserts"
    sort-by="calories"
    class="elevation-1"
  >

    <template v-slot:no-data>
      <v-btn
        color="primary"
        @click="initialize"
      >
        Reset
      </v-btn>
    </template>
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title>Prespuesto de Prueba</v-toolbar-title>

        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-text-field
          type="number"
          step="any"
          min="1"
          ref="input"
          :rules="[numberRule]"
          v-model.number="meses"
        ></v-text-field>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      {{meses*item.cantidad*(item.fat + item.calories + item.protein + item.carbs + item.iron + item.iron2 + item.iron3)}}
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
  <v-container>
      <v-row>
        <v-col
          cols="12"
          sm="6"
        >
          <v-text-field
            v-model="first"
            label="First Name"
            filled
          ></v-text-field>
        </v-col>

        <v-col
          cols="12"
          sm="6"
        >
          <v-text-field
            v-model="last"
            label="Last Name"
            filled
          ></v-text-field>
        </v-col>
      </v-row>
    </v-container>
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
      { text: "Basico", value: "calories" },
      { text: "AFP Pensiones", value: "fat" },
      { text: "AFP Seguros", value: "carbs" },
      { text: "Essalud", value: "protein" },
      { text: "EPS", value: "iron" },
      { text: "SCTR_SALUD", value: "iron" },
      { text: "SCTR_PENSIONES", value: "iron2" },
      { text: "AFP_PENSIONES", value: "iron3" },
      { text: "Utilidad", value: "utilidad" },
      { text: "Cantidad", value: "cantidad" },
          { text: 'Total', value: 'actions', sortable: false }
    ],
    desserts: [],
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
          calories: 2000.0,
          fat:  200.0,
          carbs:  100.0,
          protein:  150.0,
          iron:  70.0,
          iron2:  80.0,
          iron3: 220.0,
          cantidad: 1,
          utilidad: 0.35,
        },
        {
          name: "Medico Asistencial",
          calories:300.0,
          fat: 400.0,
          carbs:  110.0,
          protein:  150.0,
          iron: 70.0,
          iron2: 80.0,
          iron3: 220.0,
          cantidad: 1,
          utilidad: 0.35,
        },
        {
          name: "Enfermera Asistencial",
          calories:200.0,
          fat: 200.0,
          carbs:  100.0,
          protein: 150.0,
          iron: 90.0,
          iron2:  100.0,
          iron3: 110.0,
          cantidad: 1,
          utilidad: 0.35,
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