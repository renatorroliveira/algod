<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 md6 offset-md3>
        <v-card>
          <v-card-text>
            <v-text-field
                label="Nome"
                v-model="fullname"
                persistent-hint
              ></v-text-field>
              <v-text-field
                label="Apelido"
                v-model="nickname"
                persistent-hint
              ></v-text-field>
              <v-text-field
                label="Matrícula"
                v-model="matricula"
                persistent-hint
              ></v-text-field>
              <v-text-field
                label="Email"
                v-model="email"
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
            <div>
              <v-btn class="blue-grey" dark>Login</v-btn>
            </div>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  name: 'registration',
  data() {
    return {
      email: '',
      password: '',
      matricula: '',
      passwordconfirm: '',
      fullname: '',
      nickname: '',
      btnToggle: false,
      btnToggle2: false,
      select: [],
      options: [
        {
          value: 1,
          text: '1F',
        },
        {
          value: 2,
          text: '2F',
        },
        {
          value: 3,
          text: '3F',
        },
      ],
    };
  },
  methods: {
    register(event) {
      event.preventDefault();
      // Faria o cadastro com AJAX
      console.log(this.email);
      console.log(this.password);
    },
  },
};
</script>
