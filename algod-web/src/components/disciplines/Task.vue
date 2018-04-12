<template>
  <v-container grid-list-xl>
    <v-layout row wrap>
      <v-flex xs12 text-xs-center>
        <v-card color="blue-grey darken-1 white--text">
          <v-card-text>
            <div class="display-1">{{topicItem.label}}</div>
          </v-card-text>
        </v-card>
      </v-flex>

      <v-flex xs12>
        <v-card style="min-height:200px;">
          <v-card-text>
            <p>{{topicItem.description}}</p>

            <v-flex xs6 offset-xs3>
              <v-card v-if="accessLevel >= 30">
                <v-card-text>
                  <v-layout row wrap v-for="(item, i) in avaliationSummary" :key="i">
                    <v-flex xs6>
                      <p>Participantes</p>
                      <p>Envios</p>
                      <p>Precisa de avaliaçao</p>
                      <p>Data de entrega</p>
                      <p>Tempo restante</p>
                    </v-flex>
                    <v-flex xs6>
                      <p>{{item.participantes}}</p>
                      <p>{{item.envios}}</p>
                      <p>{{item.needAvaliation}}</p>
                      <p>{{item.dateAvailableTo}}</p>
                      <p>((now) - (entrega))</p>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-flex>

            <v-btn v-on:click="downloadSend($event)">download example</v-btn>

            <v-spacer class="mb-3"></v-spacer>

            <div v-if="topicItem.contentType === 1">
              <form id="formulario" v-on:submit="uploadSend($event)" enctype="multipart/form-data">
                <input id="fileupload" type="file" name="file" multiple>
                <input type="submit" name="submit" value="Enviar">
              </form>
            </div>

            <div v-else>
              <v-text-field
                label="Conteudo"
                v-model="content"
                persistent-hint
                multi-line
              ></v-text-field>
            </div>
          </v-card-text>

          <div>
            <v-btn v-if="accessLevel >= 30" color="primary" dark @click.stop="sends = true">Ver Envios</v-btn>
          </div>
        </v-card>

        <v-dialog v-model="sends" max-width="1000px">
          <v-card>
            <v-card-title><h3>Envios</h3></v-card-title>
            <v-card-text>
              <template>
                <v-data-table
                  :headers="headers"
                  :items="sendList"
                  hide-actions
                  class="elevation-1"
                  >
                  <template slot="items" slot-scope="props">
                    <td>{{ props.item.user.name }}</td>
                    <td class="text-xs-right">{{ props.item.sendDate }}</td>
                    <td class="text-xs-right">{{ props.item.name }}</td>
                  </template>
                </v-data-table>
              </template>
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import Toastr from 'toastr';
  import $ from 'jquery';
  import UserSession from '@/store/UserSession';
  import DisciplineStore from '@/store/Discipline';
  import TopicStore from '@/store/Topic';

  export default {
    data: () => ({
      accessLevel: UserSession.get('accessLevel'),
      topicItem: [],
      sendList: [],
      content: '',
      file: null,
      subscribedUsers: [],
      sends: false,
      avaliationSummary: [],
      headers2: [
        { text: 'Participantes', value: 'Participantes' },
        { text: 'Enviado', value: 'Enviado' },
        { text: 'Precisa ser avaliado', value: 'Needs avaliation' },
        { text: 'Data de entrega', value: 'Data' },
        { text: 'Status', value: 'Status' },
      ],
      headers: [
        { text: 'Nome', align: 'left', sortable: true, value: 'user.name' },
        { text: 'Data', value: 'sendDate' },
        { text: 'Arquivo', value: 'name' },
      ],
    }),
    created() {
      TopicStore.dispatch({
        action: TopicStore.ACTION_GET_TOPIC_ITEM,
        data: this.$router.currentRoute.params.topicItemId,
      });
      TopicStore.dispatch({
        action: TopicStore.ACTION_SENDS,
        data: this.$router.currentRoute.params.topicItemId,
      });
      TopicStore.on('sends', (sends) => {
        this.sendList = sends;
        console.log(this.sendList);
      }, this);
      TopicStore.on('getTopicItemById', (topicItem) => {
        this.topicItem = topicItem.data;
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_GET_SUBSCRIBED_USERS,
          data: this.topicItem.topic.discipline.id,
        });
      }, this);
      DisciplineStore.on(DisciplineStore.ACTION_GET_SUBSCRIBED_USERS, (subscribedUsers) => {
        this.subscribedUsers = subscribedUsers.data;
        this.avaliationSummary.push({
          participantes: subscribedUsers.data.length,
          envios: this.sendList.length,
          needAvaliation: this.sendList.length,
          dateAvailableTo: this.topicItem.dateAvailableTo,
        });
      }, this);
      TopicStore.on('successUpload', () => {
        console.log('Upload success');
      }, this);
      TopicStore.on('successDownload', () => {
        console.log('Download success');
      }, this);
      TopicStore.on('failDownload', (msg) => {
        Toastr.error(msg);
      }, this);
    },
    methods: {
      fileSelected(e) {
        console.log(e);
      },

      uploadSend(event) {
        event.preventDefault();
        const formData = new FormData();
        const fileUpload = $('#fileupload')[0];

        if (fileUpload.files.length === 1) {
          formData.append('file', fileUpload.files[0]);
          TopicStore.dispatch({
            action: TopicStore.ACTION_UPLOAD,
            data: {
              formData,
              topicItem: this.topicItem,
            },
          });
        } else if (fileUpload.files.length > 1) {
          // TODO: upload multiple files
        }
      },
      downloadSend(event) {
        event.preventDefault();
        TopicStore.dispatch({
          action: TopicStore.ACTION_DOWNLOAD,
          data: {
            topicItem: this.topicItem,
          },
        });
      },
    },

    updated() {
      // console.log(this.form);
    },
    beforeDestroy() {
      TopicStore.off(null, null, this);
    },
  };
</script>
