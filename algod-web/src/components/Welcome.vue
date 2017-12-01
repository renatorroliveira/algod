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
      <div v-if="disciplines.length <= 0">
        <h5>Você não está inscrito em nenhuma disciplina</h5>
        <form v-on:submit="doSearch($event)">
          <v-text-field prepend-icon="search" v-model="terms" hide-details single-line></v-text-field><p></p>
          <div class="text-xs-right">
            <v-btn type="submit" color="secondary">Pesquisar</v-btn>
          </div>
        </form>
        <p></p>
        <v-card v-if="resultSearch.length > 0" v-for="(discipline, i) in resultSearch" :key="i">
          <v-card-media
            v-if="discipline.img != null"
            :src="discipline.img"
            height="200px"
          >
          </v-card-media>
          <v-card-title primary-title>
            <div class="center">
              <div class="headline" v-html="discipline.name"></div>
              <span class="grey--text" v-html="discipline.shortName"></span>
            </div>
          </v-card-title>
          <v-card-title>
            <v-text-field
              label="Código de acesso"
              v-model="accessKey"
              autofocus>
            </v-text-field>
          </v-card-title>
          <v-card-actions>
            <v-btn flat color="black" v-on:click="doSubscribe($event, discipline.id)">Subscribe</v-btn>
            <v-btn flat color="purple" :to="`/discipline/${discipline.id}`">Explore</v-btn>
          </v-card-actions>
        </v-card>
        <v-card v-else-if="query">
          <v-card-text>
            <h5>Nenhum resultado encontrado</h5>
          </v-card-text>
        </v-card>
      </div>
      <div v-else>
        <v-card v-for="(discipline, i) in disciplines" :key="i">
          <v-card-media
            v-if="discipline.discipline.img != null"
            :src="discipline.discipline.img"
            height="200px"
          >
          </v-card-media>
          <v-card-title primary-title>
            <div>
              <div class="headline" v-html="discipline.discipline.name"></div>
              <span class="grey--text" v-html="discipline.discipline.shortName"></span>
            </div>
          </v-card-title>
          <v-card-actions>
            <v-btn flat color="purple" :to="`/discipline/${discipline.discipline.id}`">Explore</v-btn>
            <v-btn flat color="black" v-on:click="doUnsubscribe($event, discipline.discipline)">Unsubscribe</v-btn>
          </v-card-actions>
        </v-card>
      </div>
    </v-flex>
  </v-layout>
</template>

<script>
import Toastr from 'toastr';
import UserSession from '@/store/UserSession';
import DisciplineStore from '@/store/Discipline';

export default {
  name: 'Welcome',
  data: () => ({
    show: false,
    accessLevel: UserSession.get('accessLevel'),
    disciplines: [],
    terms: '',
    resultSearch: [],
    accessKey: '',
    query: false,
  }),
  mounted() {
    const me = this;
    DisciplineStore.dispatch({
      action: DisciplineStore.ACTION_LIST,
    });
    DisciplineStore.on('list', (data) => {
      console.log(data.data);
      me.disciplines = data.data;
    }, this);
    DisciplineStore.on('doUnsubscribe', () => {
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_LIST,
      });
    }, this);
    DisciplineStore.on('doSubscribe', () => {
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_LIST,
      });
    }, this);
    DisciplineStore.on('search', (data) => {
      console.log(data);
      me.query = true;
      me.resultSearch = data.data;
    }, this);
  },
  methods: {
    doSearch(event) {
      event.preventDefault();
      if (this.terms !== '') {
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_SEARCH,
          data: this.terms,
        });
      } else {
        Toastr.warning('Insira algum texto');
      }
    },
    doSubscribe(event, disciplineId) {
      event.preventDefault();
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_SUBSCRIBE,
        data: {
          id: disciplineId,
          accessKey: this.accessKey,
        },
      });
    },
    doUnsubscribe(event, discipline) {
      event.preventDefault();
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_UNSUBSCRIBE,
        data: discipline,
      });
    },
  },
  beforeDestroy() {
    DisciplineStore.off(null, null, this);
  },
};
</script>
