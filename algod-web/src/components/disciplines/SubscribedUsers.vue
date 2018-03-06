<template>
  <v-container grid-list-xl text-xs-center>
    <v-flex sx12>
      <v-card color="blue-grey darken-1 white--text">
        <v-card-text>
          <div class="display-1">Usuários inscritos em {{discipline.name}}</div>
        </v-card-text>
        <v-spacer class="mb-3"></v-spacer>
      </v-card>
    </v-flex>
    <v-layout row wrap>
      <v-flex xs3>
        <v-card>
          <v-card-text>
            <v-list>
              <v-list-tile v-on:click="addUser = !addUser">
                <v-list-tile-title>
                  Inscrever usuários
                </v-list-tile-title>
              </v-list-tile>
            </v-list>
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex xs9 v-if="subscribedUsers.length > 0">
        <v-card v-for="user in subscribedUsers" :key="user.id">
          <v-card-text>
            {{user.name}}
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex xs9 v-else>
        <v-card>
          <v-card-text>
            <h5>A disciplina {{discipline.name}} não possui usuários cadastrados</h5>
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex xs3>
      </v-flex>
    </v-layout>

    <v-dialog
      v-model="addUser"
      fullscreen
      transition="dialog-bottom-transition"
      :overlay="false"
      scrollable>
      <v-card>
        <v-card-text>
          <v-flex xs6 offset-xs3>
            <v-card>
              <v-card-text>
                <form v-on:submit="searchUser($event)">
                  <v-text-field
                    label="Nome do aluno"
                    v-model="terms"
                    prepend-icon="search"
                    persistent-hint
                    autofocus
                  ></v-text-field>
                  <v-btn>Pesquisar</v-btn>
                </form>
              </v-card-text>
            </v-card>
          </v-flex><v-spacer class="mb-3"></v-spacer>
          <v-flex xs6 offset-xs3 v-for="user in users" :key="user.id">
            <v-card>
              <v-card-text>
                <h5>{{user.name}}</h5>
              </v-card-text>
              <v-card-actions>
                <v-spacer class="mb-3"></v-spacer>
                <v-btn v-on:click="">Inscrever na disciplina</v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-card-text>
      </v-card>
    </v-dialog>

  </v-container>
</template>

<script>
import DisciplineStore from '@/store/Discipline';
import UserSession from '@/store/UserSession';

export default {
  name: 'Subscribed-Users',
  data() {
    return {
      discipline: [],
      subscribedUsers: [],
      addUser: false,
      terms: '',
      users: [],
    };
  },
  created() {
    DisciplineStore.dispatch({
      action: DisciplineStore.ACTION_GET_DISCIPLINE,
      data: this.$route.params.id,
    });
    DisciplineStore.dispatch({
      action: DisciplineStore.ACTION_GET_SUBSCRIBED_USERS,
      data: this.$route.params.id,
    });
    DisciplineStore.on('getDiscipline', (data) => {
      this.discipline = data;
    }, this);
    DisciplineStore.on('subscribedUsers', (data) => {
      this.subscribedUsers = data;
    }, this);
    UserSession.on('paginatedList', (data) => {
      this.users = data.data;
    }, this);
  },
  beforeDestroy() {
    DisciplineStore.off(null, null, this);
    UserSession.off(null, null, this);
  },
  methods: {
    addNewUser(event) {
      event.preventDefault();
    },
    searchUser(event) {
      event.preventDefault();
      UserSession.dispatch({
        action: UserSession.ACTION_LIST_USERS_BY_NAME,
        data: this.terms,
      });
    },
  },
};
</script>
