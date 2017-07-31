<template>
	<v-container fluid>
    <v-layout row justify-space-around>
      <v-flex xs12 sm10 md8 lg6>
        <v-card class="elevation-2">
          <v-card-title><h5>Atividade</h5></v-card-title>
          <v-card-text>
            <v-subheader>Descrição da atividade</v-subheader>
              <v-container fluid>
                <v-layout row>
                  <v-flex xs12>
                    <v-text-field
                      v-model="resposta"
                      name="input-1"
                      label="Resposta"
                      multi-line
                    ></v-text-field>
                  </v-flex>
                </v-layout>
              </v-container>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  name: 'atividade',
  data() {
    return {
      resposta: '',
    };
  },
};
</script>
