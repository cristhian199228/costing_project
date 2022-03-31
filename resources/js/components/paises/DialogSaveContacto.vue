<template>
  <div>
    <v-dialog v-model="getDialog" persistent max-width="600px" scrollable>
      <v-card>
        <v-card-title>GUARDAR CONTACTO DIRECTO</v-card-title>
        <v-divider></v-divider>
        <v-card-text style="max-height: 550px;">
          <validation-observer ref="observer" v-slot="{validate}">
            <validation-provider v-slot="{errors}" name="nombre completo" rules="required|min:10">
              <v-text-field v-model="dc.full_name" label="NOMBRE COMPLETO" :error-messages="errors" clearable></v-text-field>
            </validation-provider>
            <validation-provider v-slot="{errors}" name="celular" rules="required|max:9|min:9|integer">
              <v-text-field v-model="dc.phone" label="CELULAR" :error-messages="errors" clearable></v-text-field>
            </validation-provider>
            <v-text-field v-model="dc.position" label="CARGO" clearable></v-text-field>
            <v-textarea v-model="dc.detail" label="DETALLE" clearable></v-textarea>
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
    dc: Object,
    action: String,
  },
  computed: {
    ...mapGetters('dc',['getDialog'])
  },
  methods: {
    ...mapMutations('dc',['SHOW_SAVE_DIRECT_CONTACT_DIALOG']),
    close(){
      this.SHOW_SAVE_DIRECT_CONTACT_DIALOG(false)
      this.reset()
    },
    guardar() {
      this.$refs.observer.validate().then(async isValid => {
        if (!isValid) {
          return
        }
        await this.$store.dispatch(`dc/${this.action}`, this.dc)
        this.reset()

      })
    },
    reset() {
      for (const prop in this.dc) {
        this.dc[prop] = null
      }
      this.$refs.observer.reset()
    }
  },
}
</script>