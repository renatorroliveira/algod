<template>
  <v-container grid-list-xl text-xs-center v-if="disciplineRole >= 30">
    <v-flex sx12>
      <v-card color="blue-grey darken-1 white--text">
        <v-card-text>
          <v-layout row wrap>
            <v-flex xs1>
              <v-btn flat icon :title="`Voltar para ${discipline.name}`" :to="`/discipline/${discipline.id}`">
                <v-icon dark>chevron_left</v-icon>
              </v-btn>
            </v-flex>
            <v-flex xs11 text-xs-center>
              <span class="display-1">Usuários inscritos em {{discipline.name}}</span>
            </v-flex>
          </v-layout>
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
        <v-card v-for="subscription in subscribedUsers" :key="subscription.user.id">
          <v-card-text>
            <v-layout>
              <v-flex xs1>
                <v-btn v-on:click="editUser = !editUser; selectedUser = subscription.user" small flat icon>
                  <v-icon>edit</v-icon>
                </v-btn>
              </v-flex>
              <v-flex xs10>
                <p class="title">{{subscription.user.name}}</p>
              </v-flex>
              <v-flex xs1>
                <v-btn v-on:click="unsubUser = !unsubUser; selectedUser = subscription.user" small flat icon>
                  <v-icon>close</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
          </v-card-text>
          <v-spacer class="mb-2"></v-spacer>
        </v-card>
      </v-flex>
      <v-flex xs9 v-else>
        <v-card>
          <v-card-text>
            <h5>A disciplina {{discipline.name}} não possui usuários cadastrados</h5>
            <router-link class="btn" :to="`/discipline/${discipline.id}`">Voltar</router-link>
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
          <v-btn flat icon v-on:click="addUser = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-flex xs6 offset-xs3>
            <v-card>
              <v-card-text>
                <form v-on:submit="searchUser($event)">
                  <v-text-field
                    label="Nome do aluno"
                    v-model="terms"
                    prepend-icon="search"
                    persistent-hint
                  ></v-text-field>
                  <v-btn type="submit">Pesquisar</v-btn>
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
                <v-btn v-on:click="subscribeUserInDiscipline($event, user)">Inscrever na disciplina</v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-dialog class="text-xs-center" v-model="editUser" max-width="600px">
          <v-card>

            <v-card-title><span class="subheading">Alterar as permissões de {{selectedUser.name}}</span></v-card-title>
            <v-layout row wrap>
              <v-flex xs6 offset-xs3>
            <v-card-text>
              <v-select
                required
                v-bind:items="permissions"
                v-model="e1"
                label="Permissões"
                single-line
                bottom
              ></v-select>
              <v-btn v-on:click="editUserRole($event)">Salvar</v-btn>
              <v-btn v-on:click="editUser = false">Cancelar</v-btn>
            </v-card-text>
          </v-flex>
        </v-layout>
          </v-card>

    </v-dialog>

    <v-dialog v-model="unsubUser" max-width="500px">
        <v-card>
          <v-card-title>
            Deseja desinscrever {{selectedUser.name}} de {{discipline.name}}?
          </v-card-title>
          <v-card-actions>
            <v-btn color="primary" flat v-on:click="unsubscribeUser($event)">Sim</v-btn>
            <v-btn color="primary" flat v-on:click="unsubUser = false">Cancelar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

  </v-container>
  <v-container v-else>
    <v-flex sx12>
      <v-card color="error white--text">
        <v-card-text class="text-xs-center">
          <h5>Você não tem permissão para ver os usuários inscritos em</h5><div class="display-1">{{discipline.name}}</div>
          <router-link class="btn" :to="`/discipline/${discipline.id}`">Voltar</router-link>
        </v-card-text>
      </v-card>
    </v-flex>
  </v-container>
</template>

<script>
  import Toastr from 'toastr';
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
        editUser: false,
        unsubUser: false,
        disciplineRole: 0,
        selectedUser: [],
        accessLevel: UserSession.get('accessLevel'),
        e1: '',
        permissions: [
          {
            value: 5,
            text: 'Aluno',
          }, {
            value: 30,
            text: 'Moderador',
          }, {
            value: 50,
            text: 'Professor',
          }, {
            value: 100,
            text: 'Administrador',
          },
        ],
      };
    },
    created() {
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_GET_SUBSCRIPTION,
        data: this.$router.currentRoute.params.id,
      });
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_GET_DISCIPLINE,
        data: this.$route.params.id,
      });
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_GET_SUBSCRIBED_USERS,
        data: this.$route.params.id,
      });
      DisciplineStore.on('updateUserRole', () => {
        this.selectedUser = [];
        this.editUser = false;
        this.e1 = '';
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_GET_SUBSCRIPTION,
          data: this.$router.currentRoute.params.id,
        });
      }, this);
      DisciplineStore.on('unsubscribeUser', () => {
        this.selectedUser = [];
        this.unsubUser = false;
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_GET_SUBSCRIBED_USERS,
          data: this.$route.params.id,
        });
      }, this);
      DisciplineStore.on('getSubscription', (data) => {
        if (typeof data === 'undefined') {
          this.subscription = null;
          this.accessKey = '';
          DisciplineStore.dispatch({
            action: DisciplineStore.ACTION_GET_DISCIPLINE,
            data: this.$router.currentRoute.params.id,
          });
          this.disciplineRole = this.accessLevel;
        } else {
          this.subscription = data;
          this.discipline = data.discipline;
          if (this.accessLevel > this.subscription.role) {
            this.disciplineRole = this.accessLevel;
          } else {
            this.disciplineRole = this.subscription.role;
          }
        }
      }, this);
      DisciplineStore.on('getDiscipline', (data) => {
        this.discipline = data;
      }, this);
      DisciplineStore.on('subscribedUsers', (data) => {
        this.subscribedUsers = data.data;
      }, this);
      UserSession.on('paginatedList', (data) => {
        this.users = data.data;
      }, this);
      DisciplineStore.on('subscribeUser', () => {
        this.addUser = false;
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_GET_SUBSCRIBED_USERS,
          data: this.$route.params.id,
        });
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
      subscribeUserInDiscipline(event, user) {
        event.preventDefault();
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_SUBSCRIBE_USER,
          data: {
            user,
            discipline: {
              id: this.$router.currentRoute.params.id,
            },
          },
        });
      },
      editUserRole(event) {
        event.preventDefault();
        this.e1 = parseInt(this.e1, 10);
        if (!this.e1) {
          Toastr.warning('Escolha um papel');
        } else {
          DisciplineStore.dispatch({
            action: DisciplineStore.ACTION_UPDATE_ROLE,
            data: {
              discipline: {
                id: this.$router.currentRoute.params.id,
              },
              user: this.selectedUser,
              newRole: this.e1,
            },
          });
        }
      },
      unsubscribeUser(event) {
        event.preventDefault();
        if (this.selectedUser) {
          DisciplineStore.dispatch({
            action: DisciplineStore.ACTION_UNSUBSCRIBE_USER,
            data: {
              discipline: {
                id: this.$router.currentRoute.params.id,
              },
              user: this.selectedUser,
            },
          });
        }
      },
    },
  };
</script>
