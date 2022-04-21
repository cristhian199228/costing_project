<template>
  <div>
    <v-card outlined>
      <v-breadcrumbs :items="items"></v-breadcrumbs>
      <p class="display-1 mt-5 text-center">REGISTRO DE ATENCIONES DIARIAS</p>
      <v-divider></v-divider>
      <v-data-table :items="getAtenciones" :headers="headers" :items-per-page="15" hide-default-footer>
        <template v-slot:header.nombres="{header}">
          <v-text-field dense hide-details v-model="buscar" label="Paciente" append-icon="mdi-magnify" @click:append="getAtencionesAdministrador"
                        @keyup.enter="getAtencionesAdministrador" clearable @click:clear="clearBuscarField" single-line></v-text-field>
        </template>
        <template v-slot:header.date="{header}">
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
                dense
                hide-details
                class="font-weight-medium"
              ></v-text-field>
            </template>
            <v-date-picker v-model="dates" :max="new Date().toISOString().substr(0, 10)" range no-title scrollable>
              <v-spacer></v-spacer>
              <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
              <v-btn text color="primary" @click="guardarFechas" :disabled="dates.length < 2">OK</v-btn>
            </v-date-picker>
          </v-menu>
        </template>
        <template v-slot:header.sintomas="{header}">
          <v-select class="font-weight-medium" dense hide-details v-model="getFilters.sintomas" label="Sintomas" :items="sintomas"
                    item-value="value" item-text="text" clearable @change="changeSintomas"></v-select>
        </template>
        <template v-slot:header.eh="{header}">
          <v-select class="font-weight-medium" dense hide-details v-model="getFilters.antecedentes" label="Antecedentes Ep." :items="antecedentes"
                    item-value="value" item-text="text" clearable @change="changeAntecedentes"></v-select>
        </template>
        <template v-slot:header.dc="{header}">
          <v-select class="font-weight-medium" dense hide-details v-model="getFilters.dc" label="Contactos Directos" :items="contactos"
                    item-value="value" item-text="text" clearable @change="changeDC"></v-select>
        </template>
        <template v-slot:header.tam="{header}">
          <v-select class="font-weight-medium" dense hide-details v-model="getFilters.et" label="Temp. AM" :items="temperaturas"
                    item-value="value" item-text="text" clearable @change="changeET "></v-select>
        </template>
        <template v-slot:header.tpm="{header}">
          <v-select class="font-weight-medium small" dense hide-details v-model="getFilters.lt" label="Temp. PM" :items="temperaturas"
                    item-value="value" item-text="text" clearable @change="changeLT "></v-select>
        </template>
        <template v-slot:item.date="{item}">
          <p class="font-weight-medium small">{{ item.date }}</p>
        </template>
        <template v-slot:item.nombres="{item}">
          <p class="font-weight-medium small" v-if="item.patient">{{ item.patient.full_name }}</p>
        </template>
        <template v-slot:item.sintomas="{item}">
          <v-btn v-if="item.symptoms" @click="verSintomas(item.symptoms)" icon color="red darken-2"><v-icon>mdi-emoticon-sick-outline</v-icon></v-btn>
          <v-btn v-else color="green darken-2" icon><v-icon>mdi-emoticon-happy-outline</v-icon></v-btn>
        </template>
        <template v-slot:item.eh="{item}">
          <v-btn v-if="item.epidemiological_histories" @click="verAntecedentes(item.epidemiological_histories)" icon color="red darken-2"><v-icon>mdi-emoticon-sick-outline</v-icon></v-btn>
          <v-btn v-else color="green darken-2" icon><v-icon>mdi-emoticon-happy-outline</v-icon></v-btn>
        </template>
        <template v-slot:item.dc="{item}">
          <v-btn @click="verContactosDirectos(item.direct_contacts)" icon>
            <v-badge v-if="item.direct_contacts" :content="String(item.direct_contacts.length)" :color="item.direct_contacts.length > 0 ? 'red darken-2' : 'green darken-2'">
            <v-icon>mdi-human-male</v-icon></v-badge></v-btn>
        </template>
        <template v-slot:item.tam="{item}">
          <v-chip v-if="item.early_temp" small dark :color="item.early_temp.value >= 37.8  ? 'red darken-2' : 'green darken-2'">
            {{ item.early_temp.value }}
          </v-chip>
        </template>
        <template v-slot:item.tpm="{item}">
          <v-chip v-if="item.late_temp" small dark :color="item.late_temp.value >= 37.8  ? 'red darken-2' : 'green darken-2'">
            {{ item.late_temp.value }}
          </v-chip>
        </template>
      </v-data-table>
      <paginate :store="store" :collection="collection" />
      <dialog-ver-sintomas :symptoms="symptoms" />
      <dialog-ver-antecedentes :eh="eh" />
      <dialog-ver-contactos-directos :contactos="contactosDirectos" />
      <dialog-save-tracing />
    </v-card>
  </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations } from 'vuex'
import paginate from "../shared/paginate";
import DialogVerSintomas from "./DialogVerSintomas";
import DialogVerAntecedentes from "./DialogVerAntecedentes";
import DialogVerContactosDirectos from "./DialogVerContactosDirectos";

export default {
  components: {
    paginate,
    DialogVerSintomas,
    DialogVerAntecedentes,
    DialogVerContactosDirectos,
  },
  data() {
    return {
      items: [
        {
          text: 'Atenciones',
          disabled: false,
          to: '/atenciones',
        },
      ],
      headers: [
        {text: 'Fecha', value: 'date', align: 'start', sortable: false},
        {text: 'Paciente', value: 'nombres', sortable: false},
        {text: 'Síntomas', value: 'sintomas', sortable: false},
        {text: 'Antecedentes Ep.', value: 'eh', sortable: false},
        {text: 'Contactos Directos', value: 'dc', sortable: false},
        {text: 'Temp. AM', value: 'tam', sortable: false},
        {text: 'Temp. PM', value: 'tpm', sortable: false},
      ],
      store: 'atenciones',
      collection: 'getAtencionesAdministrador',
      menu: false,
      dates: [new Date().toISOString().substr(0, 10), new Date().toISOString().substr(0, 10)],
      sintomas: [
        {value: 0, text: 'ASINTOMATICOS'},
        {value: 1, text: 'SINTOMATICOS'},
      ],
      antecedentes: [
        {value: 0, text: 'SIN ANTECEDENTES'},
        {value: 1, text: 'CON ANTECEDENTES'},
      ],
      temperaturas: [
        {value: 0, text: 'SIN FIEBRE'},
        {value: 1, text: 'CON FIEBRE'},
      ],
      contactos: [
        {value: 0, text: 'SIN CONTACTOS'},
        {value: 1, text: 'CON CONTACTOS'},
      ],
      symptoms: {},
      eh: {},
      contactosDirectos: [],
    }
  },
  computed: {
    ...mapGetters('atenciones',['getAtenciones','getFilters']),
    dateStr() {
      return this.dates.join(' ~ ')
    },
    buscar: {
      get() {
        return this.$store.getters['atenciones/getFilters'].buscar
      },
      set(val) {
        this.$store.commit('atenciones/SET_BUSCAR_FILTER', val)
      }
    },
  },
  methods: {
    ...mapActions(['getAtencionesAdministrador']),
    guardarFechas(){
      this.$refs.menu.save(this.dates)
      this.$store.commit('atenciones/SET_FECHAS_FILTER', this.dates)
      this.getAtencionesAdministrador()
    },
    clearBuscarField() {
      this.$store.commit('atenciones/SET_BUSCAR_FILTER', null)
      this.getAtencionesAdministrador()
    },
    changeSintomas(val) {
      this.$store.commit('atenciones/SET_SINTOMAS_FILTER', val)
      this.getAtencionesAdministrador()
    },
    changeAntecedentes(val) {
      this.$store.commit('atenciones/SET_ANTECEDENTES_FILTER', val)
      this.getAtencionesAdministrador()
    },
    changeET(val) {
      this.$store.commit('atenciones/SET_ET_FILTER', val)
      this.getAtencionesAdministrador()
    },
    changeLT(val) {
      this.$store.commit('atenciones/SET_LT_FILTER', val)
      this.getAtencionesAdministrador()
    },
    changeDC(val) {
      this.$store.commit('atenciones/SET_DIRECT_CONTACTS_FILTER', val)
      this.getAtencionesAdministrador()
    },
    verSintomas(symptoms) {
      this.symptoms = Object.assign({}, symptoms)
      this.$store.commit('atenciones/SHOW_VER_SINTOMAS_DIALOG', true)
    },
    verAntecedentes(antecedentes) {
      this.eh = Object.assign({}, antecedentes)
      this.$store.commit('atenciones/SHOW_VER_ANTECEDENTES_DIALOG', true)
    },
    verContactosDirectos(contactos) {
      if (contactos.length > 0) {
        this.contactosDirectos = contactos
        this.$store.commit('atenciones/SHOW_VER_CONTACTOS_DIRECTOS_DIALOG', true)
      }
    }
  },
  created() {
    this.getAtencionesAdministrador()
  }
}
</script>