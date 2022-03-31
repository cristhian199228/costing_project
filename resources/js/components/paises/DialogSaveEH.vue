<template>
  <div>
    <v-dialog v-model="getDialog" persistent max-width="600px" scrollable>
      <v-card>
        <v-card-title>GUARDAR ANTECEDENTES EP.</v-card-title>
        <v-divider></v-divider>
        <v-card-text style="max-height: 550px;">
          <validation-observer ref="observer" v-slot="{validate}">
            <validation-provider v-slot="{errors}" vid="traveled_14days_ago" name="traveled_14days_ago">
              <v-checkbox v-model="eh.traveled_14days_ago" @change="traveled_14days_ago" hide-details
                          label="Durante los últimos 14 días ha realizado algun viaje?"></v-checkbox>
            </validation-provider>
            <template v-if="eh.traveled_14days_ago">
              <validation-provider v-slot="{errors}" rules="required_if:traveled_14days_ago,true" name="lugares visitados">
                <v-text-field v-model="eh.places_visited" label="Lugares de procedencia" :error-messages="errors"></v-text-field>
              </validation-provider>
              <validation-provider v-slot="{errors}" rules="required_if:traveled_14days_ago,true" name="medio de transporte">
                <v-text-field v-model="eh.conveyance" label="Cual fué el medio de transporte?" :error-messages="errors"></v-text-field>
              </validation-provider>
              <v-menu
                v-model="menu"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="auto"
              >
                <template v-slot:activator="{ on, attrs }">
                  <validation-provider v-slot="{errors}" rules="required_if:traveled_14days_ago,true" name="fecha de llegada">
                    <v-text-field
                      v-model="eh.arrival_date"
                      label="Fecha de llegada de viaje"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      :error-messages="errors"
                    ></v-text-field>
                  </validation-provider>
                </template>
                <v-date-picker
                  v-model="eh.arrival_date"
                  @input="menu = false"
                  :max="new Date().toISOString().substr(0, 10)"
                ></v-date-picker>
              </v-menu>
            </template>
            <validation-provider v-slot="{errors}" vid="close_contact" name="close_contact">
            <v-checkbox v-model="eh.close_contact" label="¿Tuvo contacto cercano (compartiendo alojamiento o
                    proporcionando cuidado)" hide-details @change="close_contact"></v-checkbox>
            </validation-provider>
            <template v-if="eh.close_contact">
              <v-menu
                v-model="menu1"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="auto"
              >
                <template v-slot:activator="{ on, attrs }">
                  <validation-provider v-slot="{errors}" rules="required_if:close_contact,true" name="fecha de ultimo contacto">
                    <v-text-field
                      v-model="eh.last_contact_date"
                      label="Fecha de último contacto"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      :error-messages="errors"
                    ></v-text-field>
                  </validation-provider>
                </template>
                <v-date-picker
                  v-model="eh.last_contact_date"
                  @input="menu1 = false"
                  :max="new Date().toISOString().substr(0, 10)"
                ></v-date-picker>eh.
              </v-menu>
            </template>
            <v-checkbox v-model="eh.covid_conversation" label="¿Pasó tiempo en la distancia de conversación con una persona
                        que tiene o está bajo investigación por COVID-19?" hide-details></v-checkbox>
          </validation-observer>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="normal" text @click="close">CANCELAR</v-btn>
          <v-btn color="primary" @click="guardar">GUARDAR</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex'
export default {
  props: {
    eh: Object,
    action: String,
  },
  data() {
    return {
      menu: false,
      menu1: false,
    }
  },
  computed: {
    ...mapGetters('antecedentes',['getDialog'])
  },
  methods: {
    ...mapMutations('antecedentes',['SHOW_EH_DIALOG']),
    close(){
      this.SHOW_EH_DIALOG(false)
      this.reset()
    },
    guardar() {
      this.$refs.observer.validate().then(async success => {
        if (!success) {
          return
        }
        await this.$store.dispatch(`antecedentes/${this.action}`, this.eh)
        this.reset()
      })
    },
    traveled_14days_ago(val) {
      if (!val) {
        this.eh.places_visited = null
        this.eh.conveyance = null
        this.eh.arrival_date = null
      }
    },
    close_contact(val) {
      if(!val) this.eh.last_contact_date = null
    },
    reset() {
      for (const prop in this.eh) {
        if (prop !== 'id') {
          if (prop === 'traveled_14days_ago' || prop === 'close_contact' || prop === 'covid_conversation') this.eh[prop] = false
          else this.eh[prop] = null
        }
      }
      this.$refs.observer.reset()
    }
  },
}
</script>