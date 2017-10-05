<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 sm6 offset-sm3 md4 offset-md4>
        <img src="../../assets/logoAGdarken.png" style="width: 200px; margin: 20px auto; display: block;" />
        <h5 class="headline text-xs-center">Adicionar instituição</h5>
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
                label="Descrição"
                v-model="desc"
                persistent-hint
              ></v-text-field>
              <v-text-field
                label="Site"
                v-model="site"
                persistent-hint
              ></v-text-field>
              <v-text-field
                label="Host"
                v-model="host"
                persistent-hint
              ></v-text-field>
              <v-text-field
                label="URL base"
                v-model="baseURL"
                persistent-hint
              ></v-text-field>
              <div class="text-xs-right">
                <v-btn type="submit" primary>Registrar</v-btn>
              </div>
            </form>
          </v-card-text>
          <v-card-actions>
            <router-link class="btn mx-3" to="/login">Faça login</router-link>
            <router-link class="btn mx-3" to="/forgot-password">Esqueceu sua senha?</router-link>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import Toastr from 'toastr';
import InstStore from '@/store/Institution';

export default {
  name: 'register-institution',
  data() {
    return {
      name: '',
      desc: '',
      site: '',
      host: '',
      baseURL: '',
    };
  },
  created() {
    const me = this;
    InstStore.on(InstStore.ACTION_REGISTER, (response) => {
      console.log(response);
      Toastr.success('Instituição adicionada');
    }, me);
  },
  beforeDestroy() {
    InstStore.off(null, null, this);
  },
  methods: {
    register(event) {
      event.preventDefault();
      InstStore.dispatch({
        action: InstStore.ACTION_REGISTER,
        data: {
          name: this.name,
          description: this.desc,
          site: this.site,
          host: this.host,
          baseUrl: this.baseURL,
        },
      });
    },
  },
};
</script>
