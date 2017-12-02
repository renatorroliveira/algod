<template>
  <v-layout align-center justify-center>
    <v-flex class="text-xs-center" v-if="exists">
      <v-card v-if="!!subscription">
        <v-card-text>
          <h4>{{discipline.name}}</h4>
          <div v-for="(topic, i) in topics" :key="topic.id" class="">
            <section>
              <h5>Tópico {{i + 1}}</h5>
              <v-btn v-on:click="newTopic = !newTopic; topicId = topic.id">Novo item</v-btn>
              <hr>
            </section>
          </div>
          <v-btn v-on:click="">Adicionar tópico</v-btn><br>
        </v-card-text>
          <p><v-btn v-on:click="doUnsubscribe($event)">Unsubscribe</v-btn><br></p>
      </v-card>
      <v-card v-else-if="!!discipline">
        <v-card-text>
          <h4>{{discipline.name}}</h4>
          <p class="body-2">
            <form v-on:submit="doSubscribe($event)">
              <v-text-field
                label="Senha"
                v-model="accessKey"
                autofocus>
              </v-text-field>
              <v-btn type="submit">Inscrever-se</v-btn>
            </form>
          </p>
        </v-card-text>
      </v-card>
    </v-flex>
    <v-flex v-else>
      <v-card>
        <v-card-text class="red lighten-2">
          <h5>Disciplina não econtrada</h5>
        </v-card-text>
      </v-card>
    </v-flex>

    <v-dialog class="text-xs-center" v-model="newTopic" max-width="600px">
      <v-card>
        <v-card-title>
          <form class="center" v-on:submit="doNewTopicItem($event)">
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
              <v-btn type="submit" color="secondary">Create Topic</v-btn>
            </div>
          </form>
        </v-card-title>
        <v-card-actions>
          <v-btn color="primary" flat v-on:click="newTopic = !newTopic">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-layout>
</template>

<script>
  import DisciplineStore from '@/store/Discipline';
  import Toastr from 'toastr';

  export default {
    data() {
      return {
        subscription: null,
        discipline: null,
        accessKey: '',
        exists: false,
        topics: [],
        newTopic: false,
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
          Toastr.error(err.responseJSON.message);
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
          this.discipline = data.discipline;
          DisciplineStore.dispatch({
            action: DisciplineStore.ACTION_LIST_TOPICS,
            data: this.$router.currentRoute.params.id,
          });
        }
      }, this);

      DisciplineStore.on('getDiscipline', (data) => {
        this.discipline = data;
      }, this);
      DisciplineStore.on('listTopics', (data) => {
        console.log(data);
        this.topics = data.data;
      }, this);
    },
    beforeDestroy() {
      DisciplineStore.off(null, null, this);
    },
    methods: {
      doSubscribe(event) {
        event.preventDefault();
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_SUBSCRIBE,
          data: {
            id: this.$router.currentRoute.params.id,
            accessKey: this.accessKey,
          },
        });
      },
      doUnsubscribe(event) {
        event.preventDefault();
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_UNSUBSCRIBE,
          data: this.discipline,
        });
      },
      doNewTopic(event) {
        event.preventDefault();
        console.log(this.discipline);
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_ADD_TOPIC,
          data: this.discipline,
        });
      },
      doNewTopicItem(event) {
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
      },
    },
  };
</script>
