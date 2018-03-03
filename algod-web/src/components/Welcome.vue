<template>
  <v-container grid-list-md text-xs-center>
    <v-layout row wrap>
      <v-flex xs12 v-if="accessLevel >= 30" class="text-xs-center">
        <v-btn color="primary" dark @click.stop="dialog = true">Pesquisar disciplinas</v-btn> <v-spacer class="mb-4"></v-spacer>
        <h1>Algorithm God 2</h1>
        <h5>Uma nova experiência no ensino da programação de computadores</h5>
        <p class="body-2">
          Área de admin!!
          Bem vindo ao Algod. Nesse site você vai ter acesso a suas grades curriculares,
          acompanhar os exemplos das suas aulas, além de entregar trabalhos, atividades e
          realizar suas provas. Tudo por aqui, simples e prático.
        </p>
      </v-flex>
      <v-flex xs4 offset-xs4>
        <v-btn v-if="accessLevel < 30" color="primary" dark @click.stop="dialog = true">Pesquisar disciplinas</v-btn> <v-spacer class="mb-4"></v-spacer>
          <v-card v-for="(discipline, i) in disciplines" :key="discipline.id" class="text-xs-center">
            <!-- <v-card-media
              v-if="discipline.discipline.img != null"
              :src="discipline.discipline.img"
              height="200px"
            >
            </v-card-media> -->
            <v-card-title>
              <v-flex xs12>
                <h5>{{discipline.discipline.name}}</h5>
                <span class="grey--text">{{discipline.discipline.shortName}}</span>
              </v-flex>
            </v-card-title>
            <v-card-actions>
              <v-flex xs12>
                <v-btn flat color="purple" :to="`/discipline/${discipline.discipline.id}`">Visitar</v-btn>
                <v-btn flat color="black" v-on:click="doUnsubscribe($event, discipline.discipline)">Desinscrever</v-btn>
              </v-flex>
            </v-card-actions>
            <v-spacer class="mb-3"></v-spacer>
          </v-card>
      </v-flex>
    </v-layout>

    <v-dialog v-model="dialog" fullscreen transition="dialog-bottom-transition" :overlay="false" scrollable>
      <v-card tile>
        <v-toolbar card dark color="blue-grey darken-2">
          <v-btn icon @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Pesquisar disciplinas</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-card xs5>
            <v-card-text>
              <v-flex xs6 offset-xs3>
                <v-text-field
                  prepend-icon="search"
                  label="Buscar disciplinas"
                  solo-inverted
                  v-model="terms"
                  class="mx-3"
                  flat>
                </v-text-field>
                <v-btn v-on:click="doSearch($event)">Buscar</v-btn>
              </v-flex>
            </v-card-text>
          </v-card>
          <v-spacer class="mb-3"></v-spacer>
          <v-flex offset-xs3 xs6 v-if="resultSearch.length > 0">
            <v-card xs6 offset-xs3 v-for="(discipline, i) in resultSearch" :key="discipline.id">
              <!-- <v-card-media
                v-if="discipline.img != null"
                :src="discipline.img"
                height="200px"
              >
              </v-card-media> -->
              <v-card-title class="text-xs-center">
                <v-flex xs12>
                  <h5>{{discipline.name}}</h5>
                  <span class="grey--text">{{discipline.shortName}}</span>
                </v-flex>
              </v-card-title>
              <v-card-title>
                <v-text-field
                  label="Código de acesso"
                  v-model="accessKey"
                  autofocus>
                </v-text-field>
              </v-card-title>
              <v-card-actions>
                <v-flex xs12>
                  <v-btn flat color="black" v-on:click="doSubscribe($event, discipline.id)">Inscrever-se</v-btn>
                  <v-btn flat color="purple" :to="`/discipline/${discipline.id}`">Visitar</v-btn>
                </v-flex>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-card-text>
        <div style="flex: 1 1 auto;"/>
      </v-card>
    </v-dialog>
  </v-container>

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
    dialog: false,
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
      this.accessKey = '';
      this.dialog = false;
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
