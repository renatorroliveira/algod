<template>
  <v-container fluid>
    <v-layout row justify-space-around>
      <v-flex xs12 sm10 md8 lg6>
        <v-card class="elevation-2">
          <v-card-title><h5>Sala de aula</h5></v-card-title>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  name: 'Sala de aula',
  data() {
    return {
      atividade: '',
    };
  },
};
</script>
