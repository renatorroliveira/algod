<template>
 <v-container fluid>
  <v-card>
    <v-card-text>
      <v-expansion-panel expand class="grey lighten-2">
        <v-expansion-panel-content v-for="i in 3" :key="i">
          <div slot="header">{{i}}º ano</div>
            <v-card>
              <v-card-text class="grey lighten-3" v-for="j in 4" :key="j">
                Matéria {{j}}
              </v-card-text>
            </v-card>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-card-text>
  </v-card>
 </v-container>
</template>

<script>

export default {
  name: 'Cursos',
  data() {
    return {
      //
    };
  },
};
</script>
