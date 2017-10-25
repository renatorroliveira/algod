<template>
  <v-layout column align-center justify-center>
    <v-flex class="text-xs-center" v-if="accessLevel == 100">
      <h1>Algorithm God 2</h1>
      <h5>Uma nova experiência no ensino da programação de computadores</h5>
      <p class="body-2">
        Bem vindo ao Algod. Nesse site você vai ter acesso a suas grades curriculares,
        acompanhar os exemplos das suas aulas, além de entregar trabalhos, atividades e
        realizar suas provas. Tudo por aqui, simples e prático.
      </p>
    </v-flex>
    <v-flex xs12 v-else>
      <v-card>
        <v-card-media
          src="/static/doc-images/cards/sunshine.jpg"
          height="200px"
        >
        </v-card-media>
        <v-card-title primary-title>
          <div>
            <div class="headline">Top western road trips</div>
            <span class="grey--text">1,000 miles of wonder</span>
          </div>
        </v-card-title>
        <v-card-actions>
          <v-btn flat>Share</v-btn>
          <v-btn flat color="purple">Explore</v-btn>
          <v-spacer></v-spacer>
          <v-btn icon v-on:click="show = !show">
            <v-icon>{{ show ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
          </v-btn>
        </v-card-actions>
        <v-slide-y-transition>
          <v-card-text v-show="show">
            I'm a thing. But, like most politicians,
            he promised more than he could deliver.
            You won't have time for sleeping, soldier,
            not with all the bed making you'll be doing.
            Then we'll go with that data file!
            Hey, you add a one and two zeros to that or we walk!
            You're going to do his laundry? I've got to find a way to escape.
          </v-card-text>
        </v-slide-y-transition>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
import UserSession from '@/store/UserSession';

export default {
  name: 'Welcome',
  data() {
    return {
      show: false,
      accessLevel: UserSession.get('user').accessLevel,
    };
  },
};
</script>
