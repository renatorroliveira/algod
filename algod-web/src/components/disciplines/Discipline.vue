<template>
  <v-container grid-list-xl text-xs-center v-if="!!discipline && (!!subscription || disciplineRole >= 30)">
    <v-flex xs12>
      <v-card color="blue-grey darken-1 white--text">
        <v-card-text>
          <div class="display-1">{{discipline.name}}</div>
          <div class="subheading">{{discipline.shortName}}</div>
        </v-card-text>
      </v-card>
    </v-flex>

    <v-layout row wrap>
      <v-flex xs3>
        <v-list>
          <v-list-tile v-if="subscription === null" v-on:click="modalSubscribe = true">
            <v-list-tile-content>
              <v-list-tile-title>
                Inscrever-se
              </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
          <v-list-tile v-else v-on:click="doUnsubscribe($event)">
            <v-list-tile-content>
              <v-list-tile-title>
                Excluir inscrição
              </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>

        <v-spacer class="mb-3"></v-spacer>

        <v-list v-if="disciplineRole >= 30 || accessLevel >= 30">
          <v-list-tile>
            <v-list-tile-content>
              <v-list-tile-title><strong>Administração</strong></v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
          <v-list-tile @click="newTopic = !newTopic">
            <v-list-tile-content>
              <v-list-tile-title>Novo tópico</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
          <v-list-tile :to="`/discipline/${discipline.id}/users`">
            <v-list-tile-content>
              <v-list-tile-title>Usuários inscritos</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-flex>

      <v-flex xs9 v-if="topics.length > 0">
        <v-layout row wrap>
          <v-flex xs12 v-for="(topic, i) in topics" :key="topic.id">
            <v-card color="white" style="min-height: 200px;">
              <v-card-title primary-title>
                <span style="display: none;">{{update}}</span>
                <v-flex xs10>
                  <h5 class="text-sm-left">{{topic.title}}</h5>
                </v-flex>
                <v-flex xs1>
                  <v-btn v-if="disciplineRole >= 30 || accessLevel >= 30" flat icon title="Adicionar item" v-on:click="newTopicItem = !newTopicItem; topicId = topic.id">
                    <v-icon>add</v-icon>
                  </v-btn>
                </v-flex>
                <v-flex xs1>
                  <v-btn v-if="disciplineRole >= 30 || accessLevel >= 30" flat icon :title="`Remover ${topic.title}`" v-on:click="remTopic = !remTopic; topicId = topic.id; topicToRemove = topic.title">
                    <v-icon>close</v-icon>
                  </v-btn>
                </v-flex>
              </v-card-title>
              <v-card-text v-if="topicItems.length > 0">
                <v-layout row wrap v-for="item in topicItems" :key="item.id" v-if="topic.id === item.topic.id">
                  <v-flex xs10 class="text-sm-left">
                    <div v-if="item.type === 'Link'">
                       <a target="_blank" id="externalUrl" :href="item.content"><v-icon>public</v-icon>{{item.label}}</a>
                    </div>
                    <div v-else-if="item.type === 'Task'">
                      <router-link id="internalUrl" :to="`/discipline/${discipline.id}/task/${item.id}`"><v-icon>class</v-icon> {{item.label}}</router-link>
                    </div>
                  </v-flex>
                </v-layout>
              </v-card-text>
              <v-card-text v-else></v-card-text>
            </v-card>
          </v-flex>
        </v-layout>
      </v-flex>

      <v-flex xs9 v-else>
        <v-card style="min-height:200px;">
          <v-card-text>
            <h5>A disciplina não possui nenhum tópico criado</h5>
            <v-btn v-if="accessLevel >= 30 || disciplineRole >= 30" v-on:click="newTopic = !newTopic">Criar um tópico</v-btn>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>

    <div>
      <v-dialog class="text-xs-center" v-model="newTopicItem" max-width="600px">
        <v-card>
          <v-card-text>
            <v-form v-on:submit="saveNewTopicItem($event)">
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
                multi-line
              ></v-text-field>
              <v-text-field
                label="Conteúdo"
                v-model="content"
                v-if="selecTypes !== 'Task'"
                persistent-hint
              ></v-text-field>
              <v-select
                v-bind:items="contentTypes"
                v-model="contentType"
                label="Tipo do conteúdo"
                single-line
                v-else
              ></v-select>
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
                <v-btn type="submit" color="secondary">Criar novo item</v-btn>
              </div>
            </v-form>
          </v-card-text>
        </v-card>
      </v-dialog>

      <v-dialog v-model="modalSubscribe">
        <v-card>
          <v-card-text>
            <form v-on:submit="doSubscribe($event)">
              <v-text-field
              label="Senha de acesso"
              v-model="accessKey"></v-text-field>
              <v-btn type="submit">Inscrever-se</v-btn>
            </form>
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
            <v-btn v-on:click="newTopicTitle = ''; newTopic = false">Cancelar</v-btn>
          </v-card-text>
        </v-card>
      </v-dialog>

      <v-dialog v-model="remTopic" max-width="500px">
        <v-card>
          <v-card-title>
            Você deseja remover {{topicToRemove}}?
          </v-card-title>
          <v-card-actions>
            <v-btn color="primary" flat v-on:click="deleteTopic($event);">Sim</v-btn>
            <v-btn color="primary" flat v-on:click="topicToRemove = ''; remTopic = false">Cancelar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </v-container>

  <v-container grid-list-xl text-xs-center v-else-if="!subscription && !!discipline">
    <v-layout row wrap>
      <v-flex xs6 offset-xs3>
        <v-card>
          <v-card-text>
            <h4>{{discipline.name}}</h4>
            <v-text-field
              v-if="discipline.accessKey.length > 0"
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
  import TopicStore from '@/store/Topic';
  import Toastr from 'toastr';

  export default {
    data() {
      return {
        accessLevel: UserSession.get('accessLevel'),
        topicItems: [],
        subscription: null,
        discipline: null,
        e1: null,
        accessKey: '',
        modalSubscribe: false,
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
        contentTypes: [{
          text: 'Upload',
          id: 1,
        }, {
          text: 'Envio de texto',
          id: 2,
        }],
        items: [],
        contentType: [],
        selecTypes: null,
        label: '',
        description: '',
        content: '',
        dateAvailableTo: '',
        update: '',
        dateVisibleTo: '',
        topicId: null,
        disciplineRole: 0,
        topicToRemove: '',
        remTopic: null,
      };
    },
    created() {
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_GET_SUBSCRIPTION,
        data: this.$router.currentRoute.params.id,
      });

      DisciplineStore.on('doSubscribe', () => {
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_GET_SUBSCRIPTION,
          data: this.$router.currentRoute.params.id,
        });
        this.modalSubscribe = false;
      }, this);
      DisciplineStore.on('doUnsubscribe', () => {
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_GET_SUBSCRIPTION,
          data: this.$router.currentRoute.params.id,
        });
      }, this);

      DisciplineStore.on('getDiscipline', (data) => {
        this.discipline = data;
      }, this);

      TopicStore.on('addTopic', () => {
        this.newTopic = false;
        this.label = '';
        this.content = '';
        this.selecTypes = null;
        this.dateVisibleTo = '';
        this.dateAvailableTo = '';
        this.description = '';
        this.getTopics();
        this.update = ' ';
      }, this);
      TopicStore.on('deleteTopic', () => {
        this.remTopic = false;
        this.topicToRemove = '';
        this.getTopics();
        this.update = ' ';
      }, this);
      TopicStore.on('addTopicItem', () => {
        this.newTopicItem = false;
        this.getTopics();
        this.update = ' ';
      }, this);

      TopicStore.on('listTopics', (data) => {
        this.topics = [];
        if (data.data.length > 0) {
          for (let i = 0; i < data.data.length; i += 1) {
            if (!data.data[i].deleted) {
              this.topics.push(data.data[i]);
            }
          }
        }
      }, this);
      TopicStore.on('listTopicItems', (data) => {
        this.topicItems = [];
        if (data.data.length > 0) {
          for (let i = 0; i < data.data.length; i += 1) {
            if (!data.data[i].deleted) {
              this.topicItems.push(data.data[i]);
            }
          }
        }
      }, this);

      DisciplineStore.on('getSubscription', (data) => {
        console.log(data);
        this.exists = true;
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

        this.getTopics();
      }, this);

      DisciplineStore.on('fail', (err) => {
        if (err.status === 404) {
          this.exists = false;
        }
        Toastr.warning(err.responseJSON.message);
      }, this);
    },
    beforeDestroy() {
      DisciplineStore.off(null, null, this);
      TopicStore.off(null, null, this);
      UserSession.off(null, null, this);
    },
    methods: {
      doSubscribe(event) {
        event.preventDefault();
        if (this.discipline.accessKey.length > 0) {
          if (this.accessKey !== '') {
            DisciplineStore.dispatch({
              action: DisciplineStore.ACTION_SUBSCRIBE,
              data: {
                id: this.$router.currentRoute.params.id,
                accessKey: this.accessKey,
              },
            });
          } else {
            Toastr.warning('Você deve digitar a senha');
          }
        } else if (this.discipline.accessKey.length === 0) {
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
        TopicStore.dispatch({
          action: TopicStore.ACTION_ADD_TOPIC,
          data: {
            discipline: this.discipline,
            title: this.newTopicTitle,
          },
        });
      },
      deleteTopic(event) {
        event.preventDefault();
        TopicStore.dispatch({
          action: TopicStore.ACTION_DELETE_TOPIC,
          data: this.topicId,
        });
      },
      saveNewTopicItem(event) {
        event.preventDefault();
        if (this.topicId !== null && this.label !== ''
          && this.description !== ''
          && (this.content !== '' || !!this.contentType) && this.dateVisibleTo !== ''
          && this.dateAvailableTo !== '') {
          TopicStore.dispatch({
            action: TopicStore.ACTION_ADD_TOPIC_ITEM,
            data: {
              id: this.topicId,
              topicItem: {
                label: this.label,
                type: this.selecTypes,
                description: this.description,
                content: this.content,
                dateVisibleTo: this.dateVisibleTo,
                dateAvailableTo: this.dateAvailableTo,
                contentType: this.contentType.id,
              },
            },
          });
        } else {
          Toastr.warning('Você deve completar todos os campos');
        }
      },
      getTopics() {
        TopicStore.dispatch({
          action: TopicStore.ACTION_LIST_TOPICS,
          data: this.$router.currentRoute.params.id,
        });
        TopicStore.dispatch({
          action: TopicStore.ACTION_LIST_TOPIC_ITEMS,
          data: this.$router.currentRoute.params.id,
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
