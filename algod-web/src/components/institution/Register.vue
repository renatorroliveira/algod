<template>
  <main>
  <v-container>
    <v-layout row wrap>
      <v-flex xs8 offset-xs2>
        <v-card>
          <v-card-text>
            <h5 class="headline text-xs-center">Adicionar instituição</h5>
            <form v-on:submit="register($event)">
              <v-text-field
                label="Nome"
                v-model="name"
                persistent-hint
                autofocus
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
              <v-text-field
                label="Descrição"
                v-model="desc"
                multi-line
                persistent-hint
              ></v-text-field>
              <div class="text-xs-right">
                <v-btn type="submit" primary>Registrar</v-btn>
              </div>
            </form>
          </v-card-text>
          <v-btn dark class="btn mx-3" to="/list">Lista de instituições</v-btn>
          <v-card-actions>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</main>
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
  mounted() {
    const me = this;
    InstStore.on(InstStore.ACTION_REGISTER, () => {
      Toastr.success('Instituição adicionada');
      me.$router.push('/list');
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
