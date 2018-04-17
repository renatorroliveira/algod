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
            <p style="font-size: 18px">{{topicItem.description}}</p>

            <v-flex v-if="accessLevel >= 30" xs6 offset-xs3>
              <v-card v-if="!!send">
                <v-card-title><h5>Envios da tarefa</h5></v-card-title>
                <v-divider></v-divider>
                <v-list>
                  <v-list-tile>
                    <v-list-tile-content>Participantes:</v-list-tile-content>
                    <v-list-tile-content class="align-end">{{subscribedUsers.length}}</v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile>
                    <v-list-tile-content>Envios:</v-list-tile-content>
                    <v-list-tile-content class="align-end">{{sendList.length}}</v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile>
                    <v-list-tile-content>Precisa de avaliaçao:</v-list-tile-content>
                    <v-list-tile-content class="align-end">{{sendList.length}}</v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile>
                    <v-list-tile-content>Prazo de entrega:</v-list-tile-content>
                    <v-list-tile-content class="align-end">{{topicItem.dateAvailableTo}}</v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile>
                    <v-list-tile-content>Tempo restante:</v-list-tile-content>
                    <v-list-tile-content class="align-end">now - date dateAvailableTo</v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile>
                    <v-list-tile-content>Envios:</v-list-tile-content>
                    <v-list-tile-content class="align-end"><v-btn v-if="accessLevel >= 30" color="secondary" @click.stop="sends = true">Ver Envios</v-btn></v-list-tile-content>
                  </v-list-tile>
                </v-list>
              </v-card>
            </v-flex>

            <v-spacer class="mb-3"></v-spacer>

            <v-flex xs6 offset-xs3>
              <v-card v-if="!!send">
                <v-card-title><h5>Seus envios</h5></v-card-title>
                <v-divider></v-divider>
                <v-list>
                  <v-list-tile>
                    <v-list-tile-content>Status de envio:</v-list-tile-content>
                    <v-list-tile-content align-right>{{ typeof send === 'undefined' ? 'Aguardando envio' : 'Enviado'}}</v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile>
                    <v-list-tile-content>Status da avaliação:</v-list-tile-content>
                    <v-list-tile-content class="align-right">Sem nota</v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile>
                    <v-list-tile-content>Data da entrega:</v-list-tile-content>
                    <v-list-tile-content class="align-right">{{send.sendDate}}</v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile>
                    <v-list-tile-content>Tempo restante:</v-list-tile-content>
                    <v-list-tile-content class="align-right">Tarefa entregue x horas/dias andiantada</v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile>
                    <v-list-tile-content>Envio de arquivos:</v-list-tile-content>
                    <v-list-tile-content class="align-right"><v-btn v-on:click="downloadSend($event)" color="secondary"><v-icon dark>file_download</v-icon>&nbsp;download</v-btn></v-list-tile-content>
                  </v-list-tile>
                </v-list>
              </v-card>
            </v-flex>

            <v-spacer class="mb-3"></v-spacer>

            <v-flex v-if="!send">
              <div v-if="topicItem.contentType === 1">
                <form id="formulario" v-on:submit="uploadSend($event)" enctype="multipart/form-data">
                  <input id="fileupload" type="file" name="file" multiple>
                  <v-btn type="submit">Entregar</v-btn>
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
            </v-flex>
          </v-card-text>
        </v-card>

        <v-dialog v-model="sends" max-width="1000px">
          <v-card>
            <v-card-title>
              <v-flex xs8>
                <v-btn flat icon v-on:click="sends = false">
                  <v-icon>close</v-icon>
                </v-btn>
                <span style="font-size: 20px;"><strong>Envios de {{topicItem.label}}</strong></span>
              </v-flex>
              <v-flex xs4>
                <v-btn v-if="sendList.length > 0" flat v-on:click="downloadAllSends()">
                  <v-icon>file_download</v-icon>
                  Fazer download de todos os envios
                </v-btn>
              </v-flex>
            </v-card-title>
            <v-card-text>
              <template>
                <v-data-table
                  disable-initial-sort="true"
                  no-data-text="A tarefa não possui nenhum envio"
                  :headers="headers"
                  :rows-per-page-items="rowsPerPageItems"
                  rows-per-page-text="Linhas por página"
                  :items="sendList"
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
  import UploadButton from './UploadButton';

  export default {
    data: () => ({
      accessLevel: UserSession.get('accessLevel'),
      topicItem: [],
      sendList: [],
      content: '',
      file: null,
      subscribedUsers: [],
      sends: false,
      send: [],
      rowsPerPageItems: [
        5, 10, 25,
        {
          text: 'Todas',
          value: -1,
        },
      ],
      headers: [
        {
          text: 'Nome',
          value: 'name',
          align: 'left',
        },
        {
          text: 'Data de entrega',
          value: 'date',
        },
        {
          text: 'Nome do arquivo',
          value: 'filename',
        },
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
      TopicStore.dispatch({
        action: TopicStore.ACTION_GET_SEND,
        data: this.$router.currentRoute.params.topicItemId,
      });
      TopicStore.on('successSendsDownloads', (zipFile, xhr) => {
        const contentType = xhr.getResponseHeader('content-type');
        const filename = xhr.getResponseHeader('filename');
        const blob = new Blob([zipFile], { type: contentType }, filename);
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = filename;
        link.click();
      }, this);
      TopicStore.on('sends', (sends) => {
        this.sendList = sends;
      }, this);
      TopicStore.on('getSend', (send) => {
        this.send = send;
        console.log(this.send);
      }, this);
      TopicStore.on('getTopicItemById', (topicItem) => {
        this.topicItem = topicItem.data;
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_GET_SUBSCRIBED_USERS,
          data: this.topicItem.topic.discipline.id,
        });
      }, this);
      DisciplineStore.on('subscribedUsers', (subscribedUsers) => {
        this.subscribedUsers = subscribedUsers.data;
      }, this);
      TopicStore.on('successUpload', () => {
        console.log('Upload success');
        TopicStore.dispatch({
          action: TopicStore.ACTION_GET_SEND,
          data: this.$router.currentRoute.params.topicItemId,
        });
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
      downloadAllSends() {
        TopicStore.dispatch({
          action: TopicStore.ACTION_SENDS_DOWNLOAD,
          data: this.$router.currentRoute.params.topicItemId,
        });
      },
    },
    beforeDestroy() {
      TopicStore.off(null, null, this);
    },
    components: {
      UploadButton,
    },
  };
</script>

<style scoped>
.jbtn-file {
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.jbtn-file input[type=file] {
  position: absolute;
  top: 0;
  right: 0;
  min-width: 100%;
  min-height: 100%;
  text-align: right;
  filter: alpha(opacity=0);
  opacity: 0;
  outline: none;
  cursor: inherit;
  display: block;
}
</style>
