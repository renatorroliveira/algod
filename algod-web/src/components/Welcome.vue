<template>
  <div>
    <v-parallax src="/static/base-ifc-wallpaper.png" class="mb-5">
      <v-layout column align-center justify-center>
        <h1 class="white--text">ALGORITHM GOD II</h1>
        <h5 class="white--text">Uma nova experiência no ensino da programação de computadores</h5>
        <p class="body-2 text-xs-center">
          Bem vindo ao Algod. Nesse site você vai ter acesso a suas grades curriculares,
          acompanhar os exemplos das suas aulas, além de entregar trabalhos, atividades e
          realizar suas provas. Tudo por aqui, simples e prático.
        </p>
        <v-btn primary large class="elevation-5" to="/login">Entrar</v-btn>
        <v-btn primary large class="elevation-5" to="/register">Registre-se agora</v-btn>
      </v-layout>
    </v-parallax>
  </div>
</template>

<script>
export default {
  name: 'Welcome',
  data() {
    return {
      //
    };
  },
};
</script>
