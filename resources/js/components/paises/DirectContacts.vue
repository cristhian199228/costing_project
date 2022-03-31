<template>
  <div>
    <v-breadcrumbs :items="items"></v-breadcrumbs>
    <p class="display-1 mt-5 text-center">LISTA DE CONTACTOS DIRECTOS</p>
    <v-divider></v-divider>
    <v-card-text>
      <v-alert
        border="left"
        colored-border
        type="info"
        elevation="2"
      >
        Un contacto directo es una persona que experimentó las siguientes condiciones durante los último 14 días previos a la fecha en la que se toma la prueba serológica.
        <ul>
          <li>Contacto frente a frente sin utilizar EPP adecuado o sin mascarilla comunitaria</li>
          <li>Contacto físico directo (abrazos, tocar los codos, tocas los puños, estrechar las manos), así sea por sólo unos cuantos segundos.</li>
          <li>Contacto frente a frente a menos de 1 metro de distancia y por más de 15 minutos a pesar de estar utilizando mascarilla comunitaria.</li>
          <li>Haber compartido un espacio cerrado por 2 horas o más, con poca o nula ventilación en este mismo lapso, aunque hayan estado separados a más de 2 metros y hayan estado usando mascarilla comunitaria.</li>
          <li>Haber compartido dormitorio o llegar de descanso en camas contiguas horizontal y vertical</li>
          <li>Haber compartido comedores a menos de 1.5 metros de distancia sin una separación física de más de 60 centímetros desde el tablero.</li>
          <li>Haber compartido equipos herramientas u otros sin haber realizado una previa limpieza de estos antes de su uso</li>
        </ul>
      </v-alert>
      <v-data-table :headers="headers" :items="getDc">
        <template v-slot:item.acciones="{item}">
          <v-btn @click="updateDC(item)" small icon><v-icon small>mdi-pencil</v-icon></v-btn>
          <v-btn @click="deleteDC(item.id)" small icon><v-icon small>mdi-delete</v-icon></v-btn>
        </template>
      </v-data-table>
      <v-btn @click="storeDC" color="pink" fixed bottom right fab dark><v-icon>mdi-plus</v-icon></v-btn>
      <dialog-save-contacto :action="action" :dc="dc" />
    </v-card-text>
  </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations } from 'vuex'
import DialogSaveContacto from "./DialogSaveContacto";

export default {
  components: {
    DialogSaveContacto,
  },
  data() {
    return {
      items: [
        {
          text: 'Inicio',
          link: true,
          exact: true,
          disabled: false,
          to: { name: 'Pac'}
        },
        {
          text: 'Contactos directos',
          disabled: false,
          to: '/paciente/contactosDirectos/' + this.$route.params.attention_id
        }
      ],
      headers: [
        { text: 'Nombre Completo', value: 'full_name', align: 'start', sortable: false},
        { text: 'Celular', value: 'phone', sortable: false},
        { text: 'Puesto', value: 'position', sortable: false},
        { text: 'Detalle', value: 'detail', sortable: false},
        { text: 'Acciones', value: 'acciones', sortable: false},
      ],
      dc: {
        full_name: null,
        phone: null,
        position: null,
        detail: null
      },
      action: null,
    }
  },
  methods: {
    ...mapActions('dc',['getDirectContacts','delete']),
    ...mapMutations('dc',['SHOW_SAVE_DIRECT_CONTACT_DIALOG']),
    storeDC() {
      this.action = 'store'
      this.SHOW_SAVE_DIRECT_CONTACT_DIALOG(true)
    },
    updateDC(dc) {
      this.action = 'update'
      this.dc = Object.assign({}, dc)
      this.SHOW_SAVE_DIRECT_CONTACT_DIALOG(true)
    },
    deleteDC(id) {
      const res = confirm('Está seguro de eliminar el contacto directo?')
      if (res) this.delete(id)
    }
  },
  computed: {
    ...mapGetters('dc',['getDc']),
  },
  created() {
    this.getDirectContacts()
  }
}
</script>