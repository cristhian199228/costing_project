<template>
  <div>
    <v-row no-gutters>
      <v-col cols="12" offset-lg="2" offset-md="1" lg="8" md="10" sm="12" xs="12">
        <v-card outlined>
          <router-view></router-view>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>

export default {
}
</script>