<template>
   <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 sm6 offset-sm3 md4 offset-md4>
        <img src="../../assets/logoAGdarken.png" style="width: 200px; margin: 20px auto; display: block;" />
        <h5 class="headline text-xs-center">Faça seu login</h5>
        <v-card>
          <v-card-text>
            <form v-on:submit="login($event)">
              <v-text-field
                label="Email"
                v-model="email"
                autofocus>
              </v-text-field>
              <v-text-field
                label="Digite sua senha"
                v-model="password"
                :append-icon="btnToggle ? 'visibility' : 'visibility_off'"
                :append-icon-cb="() => (btnToggle = !btnToggle)"
                :type="btnToggle ? 'text' : 'password'">
              </v-text-field>
              <div class="text-xs-right">
                <v-btn type="submit" primary>Entrar</v-btn>
              </div>
            </form>
          </v-card-text>
          <v-card-actions>
            <router-link class="btn mx-3" to="/register">Cadastre-se</router-link>
            <router-link class="btn mx-3" to="/forgot-password">Esqueceu sua senha?</router-link>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  name: 'login',
  data() {
    return {
      email: '',
      password: '',
      btnToggle: false,
    };
  },
  methods: {
    login(event) {
      event.preventDefault();
      // Faria o login com AJAX
      console.log(this);
    },
  },
};
</script>
