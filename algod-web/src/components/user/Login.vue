<template>
   <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 md6 offset-md3>
        <v-card>
          <v-card-text>
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
      console.log(this.email);
      console.log(this.password);
    },
  },
};
</script>
