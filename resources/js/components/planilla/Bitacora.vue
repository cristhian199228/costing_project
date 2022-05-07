<template>
  <div>
    <v-col cols="12" offset-lg="2" offset-md="1" lg="8" md="10" sm="12" xs="12">
      <v-card outlined>
        <validation-observer ref="observer" v-slot="{validate}">
          <v-breadcrumbs :items="items"></v-breadcrumbs>
          <p class="display-1 mt-5 text-center">BITÁCORA DE SEGUIMIENTO</p>
          <p class="subtitle-1 text-center">PACIENTE: <span class="font-weight-light">{{ paciente.full_name }}</span></p>
          <v-divider></v-divider>
    

          <v-card-text>
            <v-row>
              <v-col cols="12" lg="10" md="10" sm="10" xs="12">
                <validation-provider v-slot="{errors}" name="comentario" rules="required">
                  <v-textarea v-model="form.comment" label="Nuevo Comentario" counter rows="3" outlined clearable
                              background-color="indigo lighten-5" :error-messages="errors"></v-textarea>
                </validation-provider>
              </v-col>
              <v-col cols="12" lg="2" md="2" sm="2" xs="12">
                <v-btn color="primary" @click="guardar">AGREGAR</v-btn>
              </v-col>
            </v-row>
            <v-divider></v-divider>
            <v-expansion-panels>
              <v-expansion-panel v-for="binnacle in getBinnacles" :key="binnacle.id">
                <v-expansion-panel-header>
                  {{ binnacle.datetime }}
                </v-expansion-panel-header>
                <v-expansion-panel-content>{{ binnacle.comment }}</v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-card-text>
        </validation-observer>
      </v-card>
    </v-col>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  data() {
    return {
      items: [
        {
          text: 'Seguimientos',
          link: true,
          exact: true,
          disabled: false,
          to: { name: 'Seguimientos'}
        },
        {
          text: 'Bitácora',
          disabled: false,
          to: '/seguimientos/bitacora/' + this.$route.params.tracing_id
        }
      ],
      form: {
        comment: null,
        tracing_id: this.$route.params.tracing_id
      }
    }
  },
  computed: {
    ...mapGetters('seguimientos',['getBinnacles','getTracingById']),
    paciente() {
      return this.getTracingById(this.$route.params.tracing_id).patient
    }
  },
  methods: {
    ...mapActions(['getTracing']),
    ...mapActions('binnacle',['store']),
    guardar() {
      this.$refs.observer.validate().then(success => {
        if (!success) {
          return
        }
        this.store(this.form)
        this.form.comment = null
        this.$refs.observer.reset()
      })
    }
  },
  created() {
    this.getTracing(this.$route.params.tracing_id)
  }
}
</script>