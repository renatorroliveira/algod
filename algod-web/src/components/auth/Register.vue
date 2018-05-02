<template>
  <main>
    <img src="../../assets/logoAGdarken.png" style="width: 200px; margin: 20px auto; display: block;" />
    <h5 class="headline text-xs-center">Crie sua conta</h5>
    <v-card>
      <v-card-text>
        <form v-on:submit="register($event)">
          <v-text-field
            label="Nome"
            v-model="name"
            persistent-hint
            autofocus
          ></v-text-field>
          <v-text-field
            label="Apelido"
            v-model="nickname"
            persistent-hint
          ></v-text-field>
          <v-text-field
            label="Email"
            v-model="email"
            persistent-hint
          ></v-text-field>
          <v-text-field
            label="Telefone"
            v-model="phone"
            mask="(##) # ####-####"
            return-masked-value
            persistent-hint
          ></v-text-field>
          <v-text-field
            label="Digite sua senha"
            v-model="password"
            :append-icon="btnToggle ? 'visibility' : 'visibility_off'"
            :append-icon-cb="() => (btnToggle = !btnToggle)"
            :type="btnToggle ? 'text' : 'password'">
          </v-text-field>
          <v-text-field
            label="Confirme sua senha"
            v-model="passwordconfirm"
            :append-icon="btnToggle2 ? 'visibility' : 'visibility_off'"
            :append-icon-cb="() => (btnToggle2 = !btnToggle2)"
            :type="btnToggle2 ? 'text' : 'password'">
          </v-text-field>
          <div class="text-xs-right">
            <v-btn type="submit" color="primary">Registrar</v-btn>
          </div>
        </form>
      </v-card-text>
      <v-card-actions>
        <router-link class="btn mx-3 black--text" to="/auth/login">Faça login</router-link>
        <router-link class="btn mx-3 black--text" to="/auth/forgot-password">Esqueceu sua senha?</router-link>
      </v-card-actions>
    </v-card>
  </main>
</template>

<script>
import Toastr from 'toastr';
import UserStore from '@/store/User';

export default {
  name: 'registration',
  data() {
    return {
      email: '',
      password: '',
      passwordconfirm: '',
      name: '',
      nickname: '',
      phone: '',
      btnToggle: false,
      btnToggle2: false,
      select: [],
      submitting: false,
    };
  },
  created() {
    UserStore.on('fail', (res) => {
      console.log(res);
      Toastr.error(res);
    }, this);
    UserStore.on('register', () => {
      this.$router.push('/auth/login');
    }, this);
  },
  beforeDestroy() {
    UserStore.off(null, null, this);
  },
  methods: {
    register(event) {
      event.preventDefault();
      if (this.password !== this.passwordconfirm) {
        Toastr.warning('As senhas digitadas não são iguais.');
      } else {
        UserStore.dispatch({
          action: UserStore.ACTION_REGISTER,
          data: {
            name: this.name,
            phone: this.phone,
            email: this.email,
            password: this.password,
            nickname: this.nickname,
          },
        });
      }
    },
  },
};
</script>
