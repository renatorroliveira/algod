<template>
  <v-container grid-list-xl text-xs-center v-if="!!subscription || disciplineRole >= 30">
    <v-flex xs12>
      <v-card color="blue-grey darken-1 white--text">
        <v-card-text>
          <div class="display-1">{{discipline.name}}</div>
          <div class="subheading">{{discipline.shortName}}</div>
        </v-card-text>
      </v-card>
    </v-flex>

    <v-layout row wrap>
      <v-flex xs12></v-flex>
      <v-flex xs3>
        <v-list>
          <v-list-group no-action>
            <v-list-tile slot="item">
              <v-list-tile-content>
                <v-list-tile-title><strong>Disciplinas Inscritas</strong></v-list-tile-title>
              </v-list-tile-content>
              <v-list-tile-action>
                <v-icon>keyboard_arrow_down</v-icon>
              </v-list-tile-action>
            </v-list-tile>
            <v-list-tile v-for="(item, i) in items" :key="item.id" :to="item.href">
              <v-list-tile-content>
                <v-list-tile-title>{{item.title}}</v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
          </v-list-group>
        </v-list>

        <v-divider class="mb-3"></v-divider>

        <v-list v-if="disciplineRole >= 30">
          <v-list-tile>
            <v-list-tile-content>
              <v-list-tile-title><strong>Administração</strong></v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
          <v-list-group no-action>
            <v-list-tile slot="item">
              <v-list-tile-content>
                <v-list-tile-title>{{ discipline.name }}</v-list-tile-title>
              </v-list-tile-content>
              <v-list-tile-action>
                <v-icon>keyboard_arrow_down</v-icon>
              </v-list-tile-action>
            </v-list-tile>
            <v-list-tile v-for="(descDisc, i) in descDiscis" :key="i" :to="descDisc.href">
              <v-list-tile-content>
                <v-list-tile-title>{{descDisc.title}}</v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-list-tile @click="newTopic = !newTopic">
              <v-list-tile-content>
                <v-list-tile-title>Novo tópico</v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
          </v-list-group>
        </v-list>
      </v-flex>

      <v-flex xs9 v-if="topics.length > 0">
        <v-container fluid style="min-height: 0;" grid-list-lg>
          <v-layout row wrap>
            <v-flex xs12 v-for="(topic, i) in topics" :key="topic.id">
              <v-card color="white" style="min-height: 200px;">
                <v-card-title primary-title>
                  <v-flex xs10>
                    <h5 class="text-sm-left">{{topic.title}}</h5>
                  </v-flex>
                  <v-flex xs1>
                    <v-btn v-if="disciplineRole >= 30" flat icon title="Adicionar item" v-on:click="newTopicItem = !newTopicItem; topicId = topic.id">
                      <v-icon>add</v-icon>
                    </v-btn>
                  </v-flex>
                  <v-flex xs1>
                    <v-btn v-if="disciplineRole >= 30" flat icon :title="`Remover ${topic.title}`">
                      <v-icon>close</v-icon>
                    </v-btn>
                  </v-flex>
                </v-card-title>
                <v-card-text>
                  <v-layout row wrap v-for="item in topicItems" :key="item.id" v-if="topic.id === item.topic.id">
                    <v-flex xs10 class="text-sm-left">
                      <div v-if="item.type === 'Link'">
                        <v-icon>public</v-icon> <a target="_blank" id="externalUrl" :href="item.content">{{item.label}}</a>
                      </div>
                      <div v-else-if="item.type === 'Task'">
                        <v-icon>class</v-icon> <router-link id="internalUrl" :to="`/discipline/${discipline.id}/task/${item.id}`">{{item.label}}</router-link>
                      </div>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-flex>

      <v-flex xs9 v-else>
        <v-card style="min-height:200px;">
          <v-card-text>
            <h5>A disciplina não possui nenhum tópico criado</h5>
            <v-btn v-on:click="newTopic = !newTopic">Criar um tópico</v-btn>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>

    <div>
      <v-dialog class="text-xs-center" v-model="newTopicItem" max-width="600px">
        <v-card>
          <v-card-text>
            <v-text-field
              label="Título"
              v-model="label"
              persistent-hint
              autofocus
            ></v-text-field>
            <v-select
              v-bind:items="types"
              v-model="selecTypes"
              label="Tipo"
              single-line
              bottom
            ></v-select>
            <v-text-field
              label="Descrição"
              v-model="description"
              persistent-hint
            ></v-text-field>
            <v-text-field
              label="Conteúdo"
              v-model="content"
              persistent-hint
            ></v-text-field>
            <v-text-field
              label="Date available To"
              v-model="dateAvailableTo"
              type="date"
              persistent-hint
            ></v-text-field>
            <v-text-field
              label="Date visible To"
              v-model="dateVisibleTo"
              type="date"
              persistent-hint
            ></v-text-field>
            <div class="input-field">
              <v-btn v-on:click="saveNewTopicItem($event)" color="secondary">Criar novo item</v-btn>
            </div>
          </v-card-text>
        </v-card>
      </v-dialog>

      <v-dialog class="text-xs-center" v-model="newTopic" max-width="600px">
        <v-card>
          <v-card-text>
            <v-text-field
              label="Título do tópico"
              v-model="newTopicTitle"
            ></v-text-field>
            <v-btn v-on:click="saveNewTopic($event)">Criar tópico</v-btn>
            <v-btn v-on:click="newTopicTitle = ''; newTopic = !newTopic">Cancelar</v-btn>
          </v-card-text>
        </v-card>
      </v-dialog>

      <v-dialog class="text-xs-center" v-model="editUser" max-width="600px">
        <v-card>
          <v-card-title>kk</v-card-title>
          <v-card-text>
            <v-select
              v-bind:items="permissions"
              v-model="e1"
              label="Permissões"
              single-line
              bottom
            ></v-select>
          </v-card-text>
        </v-card>
      </v-dialog>
    </div>
  </v-container>
  <v-container grid-list-xl text-xs-center v-else-if="!!discipline">
    <v-layout row wrap>
      <v-flex xs6 offset-xs3>
        <v-card>
          <v-card-text>
            <h4>{{discipline.name}}</h4>
            <v-text-field
              v-if="discipline.accessKey !== null || discipline.accessKey !== ''"
              label="Código de acesso"
              v-model="accessKey"
              autofocus>
            </v-text-field>
            <v-btn v-on:click="doSubscribe($event)">Inscrever-se</v-btn>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import UserSession from '@/store/UserSession';
  import DisciplineStore from '@/store/Discipline';
  import Toastr from 'toastr';

  export default {
    data() {
      return {
        accessLevel: UserSession.get('accessLevel'),
        topicItems: [],
        subscription: null,
        discipline: null,
        e1: null,
        permissions: [],
        accessKey: '',
        exists: false,
        topics: [],
        newTopic: false,
        newTopicItem: false,
        editUser: false,
        newTopicTitle: '',
        types: [
          'Link',
          'Image',
          'Task',
        ],
        items: [],
        selecTypes: null,
        label: '',
        description: '',
        content: '',
        dateAvailableTo: '',
        dateVisibleTo: '',
        topicId: null,
        disciplineRole: 0,
        descDiscis: [],
      };
    },
    created() {
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_GET_SUBSCRIPTION,
        data: this.$router.currentRoute.params.id,
      });
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_LIST,
      });
      DisciplineStore.on('list', (data) => {
        for (let i = 0; i < data.data.length; i += 1) {
          this.items.push({
            title: data.data[i].discipline.name,
            href: `/discipline/${data.data[i].discipline.id}`,
          });
        }
      }, this);
      const permissionList = [
        {
          value: 5,
          text: 'Aluno',
        }, {
          value: 30,
          text: 'Professor',
        }, {
          value: 50,
          text: 'Moderador',
        }, {
          value: 100,
          text: 'Administrador',
        },
      ];
      for (let i = 0; i < permissionList.length; i += 1) {
        this.permissions.push({
          text: permissionList[i].text,
          roleValue: permissionList[i].value,
        });
      }
      DisciplineStore.on('fail', (err) => {
        if (err.status === 404) {
          this.exists = false;
        } else if (err.responseJSON.message === 'Senha inválida') {
          Toastr.warning(err.responseJSON.message);
        }
      }, this);
      DisciplineStore.on('doSubscribe', () => {
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_GET_SUBSCRIPTION,
          data: this.$router.currentRoute.params.id,
        });
      }, this);
      DisciplineStore.on('doUnsubscribe', () => {
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_GET_SUBSCRIPTION,
          data: this.$router.currentRoute.params.id,
        });
      }, this);
      DisciplineStore.on('getSubscription', (data) => {
        this.exists = true;
        if (typeof data === 'undefined') {
          this.subscription = null;
          this.accessKey = '';
          DisciplineStore.dispatch({
            action: DisciplineStore.ACTION_GET_DISCIPLINE,
            data: this.$router.currentRoute.params.id,
          });
        } else {
          this.subscription = data;
          console.log(data);
          this.discipline = data.discipline;
          const usersPath = `/discipline/${data.discipline.id}/users`;
          this.descDiscis.push({
            title: 'Usuários inscritos',
            href: usersPath,
          });
          this.disciplineRole =
          this.accessLevel >= 30 ? this.accessLevel : this.subscription.role;
          console.log(this.subscription);
          this.getTopics();
        }
      }, this);
      DisciplineStore.on('getDiscipline', (data) => {
        this.discipline = data;
      }, this);
      DisciplineStore.on('listTopics', (data) => {
        console.log(data);
        this.topics = data.data;
      }, this);
      DisciplineStore.on('listTopicItems', (data) => {
        console.log(data);
        this.topicItems = data.data;
      }, this);
      DisciplineStore.on('addTopic', (topic) => {
        this.topics.push(topic);
        this.newTopicTitle = 'Novo tópico';
      }, this);
      DisciplineStore.on('addTopicItem', (topicItem) => {
        this.topicItems.push(topicItem);
        this.newTopicTitle = 'Novo item';
      }, this);
    },
    beforeDestroy() {
      DisciplineStore.off(null, null, this);
    },
    methods: {
      doSubscribe(event) {
        event.preventDefault();
        if (this.accessKey === '') {
          Toastr.warning('Você deve digitar a senha');
        } else {
          DisciplineStore.dispatch({
            action: DisciplineStore.ACTION_SUBSCRIBE,
            data: {
              id: this.$router.currentRoute.params.id,
              accessKey: this.accessKey,
            },
          });
        }
      },
      doUnsubscribe(event) {
        event.preventDefault();
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_UNSUBSCRIBE,
          data: this.discipline,
        });
      },
      saveNewTopic(event) {
        event.preventDefault();
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_ADD_TOPIC,
          data: {
            discipline: this.discipline,
            title: this.newTopicTitle,
          },
        });
        this.newTopic = !this.newTopic;
        this.getTopics();
      },
      saveNewTopicItem(event) {
        event.preventDefault();
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_ADD_TOPIC_ITEM,
          data: {
            id: this.topicId,
            topicItem: {
              label: this.label,
              type: this.selecTypes,
              description: this.description,
              content: this.content,
              dateVisibleTo: this.dateVisibleTo,
              dateAvailableTo: this.dateAvailableTo,
            },
          },
        });
        this.newTopicItem = !this.newTopicItem;
        this.getTopics();
      },
      getTopics() {
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_LIST_TOPICS,
          data: this.discipline.id,
        });
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_LIST_TOPIC_ITEMS,
          data: this.discipline.id,
        });
      },
    },
  };
</script>

<style scoped>
  #externalUrl {
    color: #455A64;
    text-decoration: none;
  }
  #internalUrl {
    color: #455A64;
    text-decoration: none;
  }
</style>
