<template>
  <v-container grid-list-xl text-xs-center v-if="!!subscription">
    <v-layout row wrap>
      <v-flex xs12 offset-xs>
        <v-card dark color="blue-grey darken-1">
          <v-card-text>
            <div class="display-1">{{discipline.name}}</div>
            <div class="subheading">{{discipline.shortName}}</div>
          </v-card-text>
        </v-card>
      </v-flex>

      <v-flex xs3>
        <v-card dark color="blue-grey darken-1">
          <v-card-text class="px-0">
            <v-btn v-on:click="doUnsubscribe($event)">Unsubscribe</v-btn>
            <v-btn v-if="accessLevel === 100">Ativar edição</v-btn>
          </v-card-text>
        </v-card>
      </v-flex>

      <v-flex xs9>
        <v-card class="elevation-10" dark color="grey lighten-1" v-for="(topic, i) in topics" :key="topic.id">
          <v-card-text class="px-0 black--text">
            <v-layout row wrap>
              <v-flex xs3><h5>{{topic.title}}</h5></v-flex>
              <v-flex xs6></v-flex>
              <v-flex xs3>
                <div>
                  <v-icon>add</v-icon><a class="black--text" style="cursor: pointer;" v-if="accessLevel === 100" v-on:click="newTopicItem = !newTopicItem; topicId = topic.id">Novo item</a><br>
                  <p class="body-1 black--text" style="cursor: pointer;" v-on:click="newTopic = !newTopic;">Adicionar tópico</p>
                </div>
              </v-flex>
            </v-layout>

            <v-layout row wrap v-for="item in topicItems" :key="item.id" v-if="topic.id === item.topic.id">
              <v-flex xs3>
                <div v-if="item.type === 'Link'">
                  {{i +1}}. <a target="_blank" class="black--text" :href="item.content">{{item.label}}</a>
                </div>
                <div v-else>
                  {{i}}. {{item.label}}
                </div>
              </v-flex>
            </v-layout>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>

      <div>
        <v-dialog class="text-xs-center" v-model="newTopicItem" max-width="600px">
          <v-card>
            <v-card-text>
              <v-text-field
                label="Label"
                v-model="label"
                persistent-hint
                autofocus
              ></v-text-field>
              <v-select
                v-bind:items="types"
                v-model="selecTypes"
                label="Type"
                single-line
                bottom
              ></v-select>
              <v-text-field
                label="Description"
                v-model="description"
                persistent-hint
              ></v-text-field>
              <v-text-field
                label="Content"
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
                <v-btn type="submit" v-on:click="doNewTopicItem($event)" color="secondary">Create Topic</v-btn>
              </div>
            </v-card-text>
            <v-card-actions>
              <v-btn color="primary" flat v-on:click="newTopicItem = !newTopicItem">Close</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-dialog class="text-xs-center" v-model="newTopic" max-width="600px">
          <v-card>
            <v-card-text>
              <v-text-field
                label="Títutlo do tópico"
                v-model="newTopicTitle"
              ></v-text-field>
              <v-btn v-on:click="saveNewTopic($event)">Salvar</v-btn>
              <v-btn v-on:click="newTopicTitle = ''; newTopic = !newTopic">Cancelar</v-btn>
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
        accessKey: '',
        exists: false,
        topics: [],
        newTopic: false,
        newTopicItem: false,
        newTopicTitle: 'Tópico ',
        types: [
          'Link',
          'Image',
          'Task',
        ],
        selecTypes: null,
        label: '',
        description: '',
        content: '',
        dateAvailableTo: '',
        dateVisibleTo: '',
        topicId: null,
      };
    },
    created() {
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_GET_SUBSCRIPTION,
        data: this.$router.currentRoute.params.id,
      });
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
        console.log('1');
        if (typeof data === 'undefined') {
          console.log('2');
          this.subscription = null;
          this.accessKey = '';
          DisciplineStore.dispatch({
            action: DisciplineStore.ACTION_GET_DISCIPLINE,
            data: this.$router.currentRoute.params.id,
          });
        } else {
          console.log('must');
          this.subscription = data;
          this.discipline = data.discipline;
          console.log(this.subscription);
          this.getTopics();
        }
      }, this);
      DisciplineStore.on('getDiscipline', (data) => {
        this.discipline = data;
      }, this);
      DisciplineStore.on('listTopics', (data) => {
        console.log(data);
        console.log('list topics');
        this.topics = data.data;
      }, this);
      DisciplineStore.on('listTopicItems', (data) => {
        console.log(data);
        this.topicItems = data.data;
      }, this);
      DisciplineStore.on('addTopic', (topic) => {
        this.topics.push(topic);
        this.newTopic = !this.newTopic;
        this.newTopicTitle = 'Novo tópico';
      }, this);
      DisciplineStore.on('addTopicItem', (topicItem) => {
        this.topicItems.push(topicItem);
        this.newTopicItem = !this.newTopicItem;
        this.newTopicTitle = 'Novo tópico';
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
        console.log(this.discipline);
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_ADD_TOPIC,
          data: {
            discipline: this.discipline,
            title: this.newTopicTitle,
          },
        });
        this.getTopics();
      },
      doNewTopicItem(event) {
        event.preventDefault();
        console.log('entrou do new topic item');
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
        console.log('2222');
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_LIST_TOPICS,
          data: this.$router.currentRoute.params.id,
        });
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_LIST_TOPIC_ITEMS,
          data: this.$router.currentRoute.params.id,
        });
      },
    },
  };
</script>
