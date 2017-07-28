<template>
  <div>
    <v-parallax src="/static/base-ifc-wallpaper.png" class="mb-5">
      <v-layout column align-center justify-center>
        <h1 class="white--text">ALGORITHM GOD II</h1>
        <h5 class="white--text">Uma nova experiência no ensino da programação de computadores</h5>
        <v-btn primary large class="elevation-5">Comece agora</v-btn>
      </v-layout>
    </v-parallax>

    <v-container class="my-5">
      <v-layout row justify-space-around>
        <v-flex xs12 sm10 md8 lg6 xl4>
          <v-card class="grey lighten-2">
            <v-card-title>
              <p class="body-2 text-xs-center">
                Bem vindo ao Algod. Nesse site você vai ter acesso a suas grades curriculares,
                acompanhar os exemplos das suas aulas, além de entregar trabalhos, atividades e
                realizar suas provas. Tudo por aqui, simples e prático.
              </p>
            </v-card-title>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </div>

</template>



<script>
export default {
  name: 'hello',
  data() {
    return {
      msg: 'Welcome to Your Vue.js App',
    };
  },
};
</script>
