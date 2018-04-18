<template>
  <v-container grid-list-xl>
    <v-layout row wrap>
      <v-flex xs12 text-xs-center>
        <v-card color="blue-grey darken-1 white--text">
          <v-card-text>
            <div class="display-1">
              <v-btn flat icon :title="`Voltar para ${discipline.name}`" :to="`/discipline/${discipline.id}`">
                <v-icon dark>chevron_left</v-icon>
              </v-btn>
              {{topicItem.label}}
            </div>
          </v-card-text>
        </v-card>
      </v-flex>

      <v-flex xs12>
        <v-card style="min-height:200px;">
          <v-card-text>
            <p style="font-size: 18px">{{topicItem.description}}</p>

            <v-flex v-if="accessLevel >= 30" xs6 offset-xs3>
              <v-card>
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
                    <v-list-tile-content class="align-end">{{ handleDate(topicItem.dateAvailableTo) }}</v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile>
                    <v-list-tile-content>Tempo restante:</v-list-tile-content>
                    <v-list-tile-content class="align-end">{{ handleDate(topicItem.dateAvailableTo, 'now-date') }}</v-list-tile-content>
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
                    <v-list-tile-content class="align-right" v-if="!!send">{{handleDate(send.sendDate)}}</v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile>
                    <v-list-tile-content>Entregue:</v-list-tile-content>
                    <v-list-tile-content class="align-right" v-if="!!send">{{ handleDate(topicItem.dateAvailableTo, 'sent')}} adiantado</v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile>
                    <v-list-tile-content>Envio de arquivos:</v-list-tile-content>
                    <v-list-tile-content class="align-right" v-if="!!send"><v-btn v-on:click="downloadSend($event)" color="secondary"><v-icon dark>file_download</v-icon>&nbsp;download</v-btn></v-list-tile-content>
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
                    <td class="text-xs-right">{{ handleDate(props.item.sendDate) }}</td>
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
      discipline: [],
      subscription: [],
      months: [
        'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out',
        'Nov', 'Dez',
      ],
      weekDays: [
        'Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb',
      ],
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
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_GET_SUBSCRIPTION,
        data: this.$router.currentRoute.params.id,
      });
      DisciplineStore.on('getSubscription', (subscription) => {
        this.subscription = subscription;
        this.discipline = subscription.discipline;
        console.log(subscription);
      }, this);
      TopicStore.on('sends', (sends) => {
        this.sendList = sends;
      }, this);
      TopicStore.on('getSend', (send) => {
        this.send = send;
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
        TopicStore.dispatch({
          action: TopicStore.ACTION_GET_SEND,
          data: this.$router.currentRoute.params.topicItemId,
        });
        TopicStore.dispatch({
          action: TopicStore.ACTION_SENDS,
          data: this.$router.currentRoute.params.topicItemId,
        });
      }, this);
      TopicStore.on('successDownload', (file, xhr) => {
        this.handleDownloads(file, xhr, 'file');
      }, this);
      TopicStore.on('successDownloadZip', (zipFile, xhr) => {
        this.handleDownloads(zipFile, xhr, 'zip');
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
          for (let i = 0; i < fileUpload.files.length; i += 1) {
            formData.append('file', fileUpload.files[i]);
          }
          TopicStore.dispatch({
            action: TopicStore.ACTION_UPLOAD_MULTIPLE,
            data: {
              formData,
              topicItem: this.topicItem,
            },
          });
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
      handleDate(date, type) {
        const newDate = new Date(date);

        const wd = newDate.getDay();
        const d = newDate.getDate();
        const m = newDate.getMonth();
        const y = newDate.getFullYear();

        if (type === 'now-date') {
          const diff = new Date(Date.now() - newDate.getTime());

          const diffMonth = parseInt(diff / 1000 / 60 / 60 / 24 / 30, 10) * -1;
          const diffDays = parseInt(diff / 1000 / 60 / 60 / 24, 10) * -1;
          const diffHours = parseInt(diff / 1000 / 60 / 60, 10) * -1;
          const diffMin = parseInt(diff / 1000 / 60, 10) * -1;
          const diffSec = parseInt(diff / 1000, 10) * -1;

          const ttlDays = ((diffMonth * 30) - diffDays) * -1;
          const ttlHours = ((diffDays * 24) - diffHours) * -1;
          const ttlMin = ((diffHours * 60) - diffMin) * -1;
          const ttlSec = ((diffMin * 60) - diffSec) * -1;
          let remainingTime;

          remainingTime = `${ttlDays} dias, ${ttlHours}h${ttlMin}m e ${ttlSec}s`;
          if (ttlDays <= 0) {
            remainingTime = `${ttlHours}h${ttlMin}m e ${ttlSec}s`;
          }
          if (ttlDays <= 0 && ttlHours <= 0) {
            remainingTime = `${ttlMin}min e ${ttlSec}seg`;
          }
          return remainingTime;
        }

        if (type === 'sent') {
          const diff = newDate.getTime() - new Date(this.send.sendDate).getTime();
          const diffMonth = parseInt(diff / 1000 / 60 / 60 / 24 / 30, 10) * -1;
          const diffDays = parseInt(diff / 1000 / 60 / 60 / 24, 10) * -1;
          const diffHours = parseInt(diff / 1000 / 60 / 60, 10) * -1;
          const diffMin = parseInt(diff / 1000 / 60, 10) * -1;
          const diffSec = parseInt(diff / 1000, 10) * -1;

          const ttlDays = ((diffMonth * 30) - diffDays);
          const ttlHours = ((diffDays * 24) - diffHours);
          const ttlMin = ((diffHours * 60) - diffMin);
          const ttlSec = ((diffMin * 60) - diffSec);

          let earlyTime;

          earlyTime = `${ttlDays} dias, ${ttlHours}h${ttlMin}m e ${ttlSec}s`;
          if (ttlDays <= 0) {
            earlyTime = `${ttlHours}h${ttlMin}m e ${ttlSec}s`;
          }
          if (ttlDays <= 0 && ttlHours <= 0) {
            earlyTime = `${ttlMin}min e ${ttlSec}seg`;
          }
          return earlyTime;
        }

        const fullDate = `${this.weekDays[wd]}, ${d} de ${this.months[m]} de ${y}`;
        return fullDate;
      },
      handleDownloads(response, xhr, type) {
        if (type === 'zip') {
          const contentType = xhr.getResponseHeader('content-type');
          const filename = xhr.getResponseHeader('filename');
          const blob = new Blob([response], { type: contentType }, filename);
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = filename;
          link.click();
        }
        if (type === 'file') {
          const contentType = xhr.getResponseHeader('content-type');
          const filename = xhr.getResponseHeader('filename');
          const blob = new Blob([response], { type: contentType }, filename);
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = filename;
          link.click();
        }
      },
    },
    beforeDestroy() {
      TopicStore.off(null, null, this);
      UserSession.off(null, null, this);
      DisciplineStore.off(null, null, this);
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
