<template>
  <v-container fluid>
    <v-layout row justify-space-around>
      <v-flex xs12 sm10 md8 lg6>
        <v-card class="elevation-2">
          <v-card-title>
            <h5>{{ nomedocurso }}</h5>
          </v-card-title>
          <div style="border: 2px solid black; margin: 10px;">
            <v-card-text><h5>Novidades</h5></v-card-text>
          </div><br>
        </v-card>
        <v-card>
          <v-card-title><h5>Atividades</h5></v-card-title>
          <div id="activity">
            <v-layout row wrap>
              <v-flex xs6><br>
                <v-card>
                  <v-card-text v-for="i in 5" :key="i">
                    Atividade {{i}}
                    <v-checkbox
                      v-model="marker"
                      color="green"
                      v-bind:value="i"
                      hide-details>
                    </v-checkbox>
                  </v-card-text>
                </v-card>
              </v-flex>
            </v-layout>
          </div>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  name: 'Classroom',
  data() {
    return {
      atividade: '',
      nomedocurso: 'DW II',
      marker: ['green'],
    };
  },
};
</script>
