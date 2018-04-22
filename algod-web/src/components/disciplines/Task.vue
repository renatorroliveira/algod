<template>
  <v-container grid-list-xl>
    <v-layout row wrap>
      <v-flex xs12 text-xs-center>
        <v-card color="blue-grey darken-1 white--text">
          <v-card-text>
            <v-layout row wrap>
              <v-flex xs1>
                <v-btn flat icon :title="`Voltar para ${discipline.name}`" :to="`/discipline/${discipline.id}`">
                  <v-icon dark>chevron_left</v-icon>
                </v-btn>
              </v-flex>
              <v-flex xs11 text-xs-center>
                <span class="display-1">{{topicItem.label}}</span>
              </v-flex>
            </v-layout>
          </v-card-text>
        </v-card>
      </v-flex>

      <v-flex xs12>
        <v-card style="min-height:200px;">
          <v-card-text>
            <p style="font-size: 18px">{{topicItem.description}}</p>

            <v-flex v-if="disciplineRole >= 30" xs6 offset-xs3>
              <v-card>
                <v-card-title><h5>Envios da tarefa</h5></v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <span style="font-size: 18px;">Participantes:</span>
                    </v-flex>
                    <v-flex xs6>
                      <span style="font-size: 18px;">{{subscribedUsers.length}}</span>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <span style="font-size: 18px;">Envios:</span>
                    </v-flex>
                    <v-flex xs6>
                      <span style="font-size: 18px;">{{sendList.length}}</span>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <span style="font-size: 18px;">Precisa de avaliaçao:</span>
                    </v-flex>
                    <v-flex xs6>
                      <span style="font-size: 18px;" v-if="!!send">{{sendList.length}}</span>
                      <span style="font-size: 18px;" v-else>0</span>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <span style="font-size: 18px;">Prazo de entrega:</span>
                    </v-flex>
                    <v-flex xs6>
                      <span style="font-size: 18px;">{{ handleDate(topicItem.dateAvailableTo) }}</span>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <span style="font-size: 18px;">Tempo restante:</span>
                    </v-flex>
                    <v-flex xs6>
                      <span style="font-size: 18px;">{{ handleDate(topicItem.dateAvailableTo, 'now-date') }}</span>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <span style="font-size: 18px;">Envios:</span>
                    </v-flex>
                    <v-flex xs6>
                      <span style="font-size: 18px;" v-if="!!send"><v-btn color="secondary" v-on:click="sends = true">Ver Envios</v-btn></span>
                      <span style="font-size: 18px;" v-else>0</span>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-flex>

            <v-spacer class="mb-3"></v-spacer>

            <v-flex xs6 offset-xs3>
              <v-card>
                <v-card-title><h5>Seus envios</h5></v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <span style="font-size: 18px;">Status de envio:</span>
                    </v-flex>
                    <v-flex xs6>
                      <span style="font-size: 18px;">{{ typeof send === 'undefined' || typeof send === 'null' ? 'Aguardando envio' : 'Enviado'}}</span>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <span style="font-size: 18px;">Status da avaliação:</span>
                    </v-flex>
                    <v-flex xs6>
                      <span style="font-size: 18px;">Sem nota</span>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <span style="font-size: 18px;">Data da entrega:</span>
                    </v-flex>
                    <v-flex xs6>
                      <span style="font-size: 18px;" v-if="!!send">{{ handleDate(send.sendDate) }}</span>
                      <span style="font-size: 18px;" v-else>Sem entregas</span>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <span style="font-size: 18px;">Prazo de entrega:</span>
                    </v-flex>
                    <v-flex xs6>
                      <span style="font-size: 18px;">{{ handleDate(topicItem.dateAvailableTo) }}</span>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <span style="font-size: 18px;">Tempo restante:</span>
                    </v-flex>
                    <v-flex xs6>
                      <span style="font-size: 18px;">{{ handleDate(topicItem.dateAvailableTo, 'now-date') }}</span>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap v-if="!!send">
                    <v-flex xs6>
                      <span style="font-size: 18px;">Entregue:</span>
                    </v-flex>
                    <v-flex xs6>
                      <span style="font-size: 18px;">{{ handleDate(topicItem.dateAvailableTo, 'sent')}} adiantado</span>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <span style="font-size: 18px;">Envio de arquivos:</span>
                    </v-flex>
                    <v-flex xs6>
                      <span style="font-size: 18px;" v-if="!!send"><v-btn v-on:click="modalSend = true">Editar envio</v-btn></span>
                      <span style="font-size: 18px;" v-if="!!send"><v-btn v-on:click="downloadSend($event)" color="secondary"><v-icon dark>file_download</v-icon>&nbsp;Download</v-btn></span>
                      <span style="font-size: 18px;" v-else><v-btn v-on:click="modalSend = true">Adicionar tarefa</v-btn></span>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
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
        <v-dialog v-model="modalSend" max-width="1000px">
          <v-card>
            <v-card-title>Envio de tarefa para {{topicItem.label}}</v-card-title>
            <v-divider></v-divider>
            <v-card-text v-if="handleDate(topicItem.dateAvailableTo, 'now-date') === 'Tempo esgotado' && !send">
              <h5>O tempo limite de envio acabou!</h5>
              <div class="text-xs-right">
                <v-btn color="red lighten-3" v-on:click="modalSend = false">Fechar</v-btn>
              </div>
            </v-card-text>
            <v-card-text v-else>
              <form class="my-form" v-on:submit="handleUpload($event)" enctype="multipart/form-data">

                <v-btn v-on:click="openInput($event)" icon flat>
                  <v-icon>attachment</v-icon>
                  <input id="inputFile" style="display: none;" type="file" name="file" multiple class="input-file">
                </v-btn>

                <div id="drop-area"
                  v-on:dragenter="preventDefaults($event); highlight();"
                  v-on:dragover="preventDefaults($event); highlight();"
                  v-on:dragleave="preventDefaults($event); unhighlight();"
                  v-on:drop="preventDefaults($event); unhighlight(); handleDrop($event)">

                    <p>Você pode arrastar e soltar arquivos aqui para adicioná-los.</p>

                    <v-spacer></v-spacer><br>
                    <span v-for="(file, i) in fileList">arquivo {{(parseInt(i) + 1)}}: {{file.name}}<br> </span>
                </div>
                <v-text-field
                  label="Descrição"
                  hint="Opcional"
                  persistent-hint
                  v-model="sendDesc"
                  multi-line
                ></v-text-field>
              </form>
              <div class="text-xs-right">
                <v-btn color="red lighten-3" v-on:click="modalSend = false">Cancelar</v-btn>
                <v-btn type="submit" color="green lighten-3">Entregar</v-btn>
              </div>
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import Toastr from 'toastr';
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
      disciplineRole: 0,
      sends: false,
      sendDesc: '',
      modalSend: false,
      send: [],
      discipline: [],
      subscription: [],
      fileList: [],
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
        this.modalSend = false;
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
      handleFiles(fileList) {
        this.fileList = fileList;
      },
      handleUpload() {
        const date = this.handleDate(this.topicItem.dateAvailableTo, 'now-date');
        if (date === 'Tempo esgotado') {
          Toastr.warning('Tempo limite esgotado! hahahaha');
        } else {
          const formData = new FormData();
          console.log(this.fileList);
          if (this.fileList.length === 1) {
            formData.append('file', this.fileList[0]);
            TopicStore.dispatch({
              action: TopicStore.ACTION_UPLOAD,
              data: {
                formData,
                topicItem: this.topicItem,
              },
            });
          }
        }
      },
      handleDate(date, type) {
        const newDate = new Date(date);

        const wd = newDate.getDay();
        const d = newDate.getDate();
        const m = newDate.getMonth();
        const y = newDate.getFullYear();

        let hr = newDate.getHours();
        let min = newDate.getMinutes();
        if (min === 0) {
          min = '00';
        }
        if (hr === 0) {
          hr = '00';
        }

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
          if (ttlDays <= 0 && ttlHours <= 0 && ttlMin <= 0) {
            remainingTime = `${ttlSec}seg`;
          }
          if (ttlDays <= 0 && ttlHours <= 0 && ttlMin <= 0 && ttlSec <= 0) {
            remainingTime = 'Tempo esgotado';
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
          if (ttlDays <= 0 && ttlHours <= 0 && ttlMin <= 0) {
            earlyTime = `${ttlSec}seg`;
          }
          if (ttlDays <= 0 && ttlHours <= 0 && ttlMin <= 0 && ttlSec <= 0) {
            earlyTime = 'Entregue atrasado';
          }
          return earlyTime;
        }

        const fullDate = `${this.weekDays[wd]}, ${d} de ${this.months[m]} de ${y} as ${hr}:${min} `;
        return fullDate;
      },
      handleDownloads(response, xhr, type) {
        const link = document.createElement('a');
        const contentType = xhr.getResponseHeader('content-type');
        const filename = xhr.getResponseHeader('filename');
        if (type === 'zip') {
          const blob = new Blob([response], { type: contentType }, filename);
          link.href = window.URL.createObjectURL(blob);
          link.download = filename;
          link.click();
        }
        if (type === 'file') {
          const blob = new Blob([response], { type: contentType }, filename);
          link.href = window.URL.createObjectURL(blob);
          link.download = filename;
          link.click();
        }
      },
      preventDefaults(event) {
        event.preventDefault();
        event.stopPropagation();
      },
      unhighlight() {
        const dropArea = document.getElementById('drop-area');
        dropArea.classList.remove('highlight');
      },
      highlight() {
        const dropArea = document.getElementById('drop-area');
        dropArea.classList.add('highlight');
      },
      handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        this.fileList = files;
      },
    },
    beforeDestroy() {
      TopicStore.off(null, null, this);
      UserSession.off(null, null, this);
      DisciplineStore.off(null, null, this);
    },
  };
</script>

<style scoped>
  #drop-area {
    border: 2px dashed #ccc;
    border-radius: 20px;
    width: 480px;
    font-family: sans-serif;
    margin: auto;
    padding: 20px;
  }
  #drop-area.highlight {
    border-color: purple;
  }
  p {
    margin-top: 0;
  }
  .my-form {
    margin-bottom: 10px;
  }
  #gallery {
    margin-top: 10px;
  }
  #gallery img {
    width: 150px;
    margin-bottom: 10px;
    margin-right: 10px;
    vertical-align: middle;
  }
  .button {
    display: inline-block;
    padding: 10px;
    background: #ccc;
    cursor: pointer;
    border-radius: 5px;
    border: 1px solid #ccc;
  }
  .button:hover {
    background: #ddd;
  }
  #fileElem {
    display: none;
  }
</style>
