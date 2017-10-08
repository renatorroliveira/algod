<template>
  <main>
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
        <router-link class="btn mx-3" to="/auth/register">Cadastre-se</router-link>
        <router-link class="btn mx-3" to="/auth/forgot-password">Esqueceu sua senha?</router-link>
      </v-card-actions>
    </v-card>
  </main>
</template>

<script>
import Toastr from 'toastr';
import UserSession from '@/store/UserSession';

export default {
  name: 'login',
  data() {
    return {
      email: '',
      password: '',
      btnToggle: false,
    };
  },
  mounted() {
    const me = this;
    UserSession.on('fail', (res) => {
      Toastr.error(res);
    }, me);
    UserSession.on('login', () => {
      Toastr.success('Usuário logado');
      me.$router.push('/');
    }, me);
  },
  beforeDestroy() {
    UserSession.off(null, null, this);
  },
  methods: {
    login(event) {
      event.preventDefault();
      if (this.email === '') {
        Toastr.error('E-mail não pode ser vazio');
      }
      if (this.password === '') {
        Toastr.error('Senha não pode ser vazia');
      } else {
        UserSession.dispatch({
          action: UserSession.ACTION_LOGIN,
          data: {
            email: this.email,
            password: this.password,
          },
        });
      }
    },
  },
};
</script>
