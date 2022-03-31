<template>
  <div>
    <v-breadcrumbs :items="items"></v-breadcrumbs>
    <p class="display-1 mt-5 text-center">PAISES</p>
    <v-divider></v-divider>
    <v-data-table :items="getAtenciones" :headers="headers" :items-per-page="15" hide-default-footer>
      <template v-slot:item.sintomas="{item}">
        <template v-if="item.symptoms">
          <v-btn color="red darken-2" @click="updateSintomas(item.symptoms)" icon :disabled="!item.current">
            <v-icon>mdi-emoticon-sick-outline</v-icon></v-btn>
          <v-btn small icon @click="deleteSintomas(item.symptoms.id)" v-if="item.current"><v-icon small>mdi-delete</v-icon></v-btn>
        </template>
        <template v-else>
          <v-btn color="green darken-2" icon @click="storeSintomas(item)" :disabled="!item.current"><v-icon>mdi-emoticon-happy-outline</v-icon></v-btn>
        </template>
      </template>
      <template v-slot:item.dc="{item}">
        <v-btn :disabled="!item.current" @click="abrirDc(item)" icon><v-badge :disabled="!item.current" :content="String(item.direct_contacts_count)" :color="item.direct_contacts_count > 0 ?
        'red darken-2' : 'green darken-2'">
          <v-icon>mdi-human-male</v-icon></v-badge></v-btn>
      </template>
      <template v-slot:item.eh="{item}">
        <template v-if="item.epidemiological_histories">
          <v-btn color="red darken-2" @click="updateEH(item.epidemiological_histories)" icon :disabled="!item.current">
            <v-icon>mdi-emoticon-sick-outline</v-icon></v-btn>
          <v-btn small icon @click="deleteEH(item.epidemiological_histories.id)" v-if="item.current"><v-icon small>mdi-delete</v-icon></v-btn>
        </template>
        <template v-else>
          <v-btn color="green darken-2" icon @click="storeEH(item)" :disabled="!item.current"><v-icon>mdi-emoticon-happy-outline</v-icon></v-btn>
        </template>
      </template>
      <template v-slot:item.tam="{item}">
        <template v-if="item.early_temp">
          <v-btn :color="item.early_temp.value >= 37.8 ? 'red darken-2' : 'green darken-2'" icon :disabled="!item.current"
                 @click="updateTemp(item.early_temp, 'et')"><v-icon>mdi-thermometer</v-icon></v-btn>
          <v-btn small icon @click="deleteTemp(item.early_temp, 'et')" v-if="item.current"><v-icon small>mdi-delete</v-icon></v-btn>
        </template>
        <template v-else>
          <v-btn icon @click="storeTemp(item, 'et')" :disabled="!item.current"><v-icon>mdi-thermometer</v-icon></v-btn>
        </template>
      </template>
      <template v-slot:item.tpm="{item}">
        <template v-if="item.late_temp">
          <v-btn :color="item.late_temp.value >= 37.8 ? 'red darken-2' : 'green darken-2'" icon :disabled="!item.current"
                 @click="updateTemp(item.late_temp, 'lt')"><v-icon>mdi-thermometer</v-icon></v-btn>
          <v-btn small icon @click="deleteTemp(item.late_temp, 'lt')" v-if="item.current"><v-icon small>mdi-delete</v-icon></v-btn>
        </template>
        <template v-else>
          <v-btn icon @click="storeTemp(item, 'lt')" :disabled="!item.current || new Date().getHours() < 12"><v-icon>mdi-thermometer</v-icon></v-btn>
        </template>
      </template>
    </v-data-table>
    <paginate :store="store" :collection="collection" />
    <v-btn @click="storeAttention" color="pink" fixed bottom right fab dark><v-icon>mdi-plus</v-icon></v-btn>
    <dialog-store-sintomas :symptoms="symptoms" :action="action" />
    <dialog-save-e-h :eh="eh" :action="action" />
    <dialog-save-temperatura :action="action" />
  </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations } from 'vuex'
import DialogStoreSintomas from "./DialogStoreSintomas";
import DialogSaveTemperatura from "./DialogSaveTemperatura";
import paginate from "../shared/paginate";
import DialogSaveEH from "./DialogSaveEH";

export default {
  components: {
    DialogStoreSintomas,
    DialogSaveTemperatura,
    paginate,
    DialogSaveEH,
  },
  data() {
    return {
      items: [
        {
          text: 'Inicio',
          disabled: false,
          to: '/paciente',
        },
      ],
      headers: [
       
        { text: 'Descripcion', value: 'descripcion', sortable: false},  
        { text: 'Moneda', value: 'moneda', sortable: false},
       
      ],
      action: null,
      symptoms: {
        cough: false,
        throat_pain: false,
        nasal_congestion: false,
        difficulty_breathing: false,
        fever: false,
        general_discomfort: false,
        diarrhea: false,
        nausea_vomiting: false,
        headache: false,
        irritability: false,
        short_breath: false,
        anosmia_ausegia: false,
        medication_treat: null,
        immune_condition: null,
        others: null,
      },
      eh: {
        traveled_14days_ago: false,
        places_visited: null,
        conveyance: null,
        arrival_date: null,
        close_contact: false,
        covid_conversation: false,
        last_contact_date: null,
      },
      store: 'atenciones',
      collection: 'getAtencionesPaciente',
    }
  },
  methods: {
    ...mapActions('atenciones',['storeAtencion']),
    ...mapMutations('atenciones',['SET_ATTENTION_ID']),
    ...mapMutations('sintomas',['SHOW_SYMPTOMS_DIALOG']),
    ...mapMutations('temperatura',['SHOW_TEMPERATURE_DIALOG','SET_TEMPERATURE','SET_TEMP_TYPE']),
    ...mapMutations('antecedentes',['SHOW_EH_DIALOG']),
    storeAttention(){
      const r = confirm('Esta seguro de crear una nueva atención')
      if (r) this.storeAtencion()
    },
    storeSintomas(attention){
      this.action = 'store'
      this.SET_ATTENTION_ID(attention.id)
      this.SHOW_SYMPTOMS_DIALOG(true)
    },
    updateSintomas(symptoms) {
      this.action = 'update'
      this.symptoms = Object.assign({}, symptoms)
      this.SHOW_SYMPTOMS_DIALOG(true)
    },
    deleteSintomas(id) {
      const res = confirm('Está seguro de eliminar los síntomas?')
      if (res) this.$store.dispatch('sintomas/delete', id)
    },
    storeEH(attention) {
      this.action = 'store'
      this.SET_ATTENTION_ID(attention.id)
      this.SHOW_EH_DIALOG(true)
    },
    updateEH(eh) {
      this.action = 'update'
      this.eh = Object.assign({}, eh)
      this.SHOW_EH_DIALOG(true)
    },
    deleteEH(id) {
      const res = confirm('Está seguro de eliminar los antecedentes?')
      if (res) this.$store.dispatch('antecedentes/delete', id)
    },
    storeTemp(attention, type){
      this.action = 'store'
      this.SET_ATTENTION_ID(attention.id)
      this.SET_TEMP_TYPE(type)
      this.SHOW_TEMPERATURE_DIALOG(true)
    },
    updateTemp(temperature, type) {
      this.action = 'update'
      this.SET_TEMPERATURE(temperature)
      this.SET_TEMP_TYPE(type)
      this.SHOW_TEMPERATURE_DIALOG(true)
    },
    deleteTemp(temperature, type) {
      this.SET_TEMP_TYPE(type)
      this.SET_TEMPERATURE(temperature)
      const res = confirm('Está seguro de eliminar la temperatura?')
      if (res) this.$store.dispatch('temperatura/delete')
    },
    abrirDc(attention) {
      this.SET_ATTENTION_ID(attention.id)
      this.$router.push({
        name: 'DC',
        params: {
          attention_id: attention.id
        }
      })
    }
  },
  computed: {
    ...mapGetters('atenciones',['getAtenciones']),
  },
  created() {
    this.$store.dispatch('getAtencionesPaciente')
  }
}
</script>