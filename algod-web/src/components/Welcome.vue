<template>
  <v-layout column align-center justify-center>
    <v-flex class="text-xs-center" v-if="accessLevel == 100">
      <h1>Algorithm God 2</h1>
      <h5>Uma nova experiência no ensino da programação de computadores</h5>
      <p class="body-2">
        Área de admin
        Bem vindo ao Algod. Nesse site você vai ter acesso a suas grades curriculares,
        acompanhar os exemplos das suas aulas, além de entregar trabalhos, atividades e
        realizar suas provas. Tudo por aqui, simples e prático.
      </p>
    </v-flex>
    <v-flex xs12 v-else>
      <v-card v-for="(discipline, i) in disciplines" :key="i">
        <v-card-media
          v-if="discipline.img != null"
          :src="discipline.img"
          height="200px"
        >
        </v-card-media>
        <v-card-title primary-title>
          <div>
            <div class="headline" v-html="discipline.name"></div>
            <span class="grey--text" v-html="discipline.shortName"></span>
          </div>
        </v-card-title>
        <v-card-actions>
          <v-btn flat color="purple" :to="`/discipline/${discipline.id}`">Explore</v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>
    <v-flex>
      <v-card>

      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
import UserSession from '@/store/UserSession';
import DisciplineStore from '@/store/Discipline';

export default {
  name: 'Welcome',
  data() {
    return {
      show: false,
      accessLevel: UserSession.get('accessLevel'),
      disciplines: [],
    };
  },
  mounted() {
    const me = this;
    DisciplineStore.dispatch({
      action: DisciplineStore.ACTION_LIST,
    });
    DisciplineStore.on('list', (data) => {
      me.disciplines = data.data;
    }, this);
  },
  beforeDestroy() {
    DisciplineStore.off(null, null, this);
  },
};
</script>
