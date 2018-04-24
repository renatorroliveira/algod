<template>
  <v-container v-if="disciplineRole >= 30">
    <v-layout row wrap>
      <v-flex xs12 text-xs-center>
        <v-card color="blue-grey darken-1 white--text">
          <v-card-text>
            <v-layout row wrap>
              <v-flex xs1>
                <v-btn flat icon :title="`Voltar para ${topicItem.label}`" :to="`/discipline/${discipline.id}/task/${topicItem.id}`">
                  <v-icon dark>chevron_left</v-icon>
                </v-btn>
              </v-flex>
              <v-flex xs11 text-xs-center>
                <span class="display-1">Envios de {{topicItem.label}}</span>
              </v-flex>
            </v-layout>
          </v-card-text>
        </v-card>
        <v-spacer class="mb-5"></v-spacer>
      </v-flex>

      <v-flex xs12>
        <v-card>
          <v-card-title>
            <v-flex xs8>
              <span style="font-size: 20px;"><strong>Envios de {{topicItem.label}}</strong></span>
            </v-flex>
            <v-flex xs4 class="text-xs-right">
              <v-btn title="Fazer download de todos os envios" v-if="!!sendList" flat v-on:click="downloadAllSends()">
                <v-icon>file_download</v-icon>
                Download de todos os envios
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
                  <td class="text-xs-left">{{ handleDate(props.item.sendDate) }}</td>
                  <td class="text-xs-left" v-if="avaliations[props.index]">{{ avaliations[props.index].score }}</td>
                  <td class="text-xs-left" v-else>0.0</td>
                  <td class="text-xs-left"><v-btn :title="`Fazer download de ${props.item.name}`" flat icon v-on:click="downloadUserFile($event, props.item.user)"><v-icon>file_download</v-icon></v-btn></td>
                  <td class="text-xs-left">
                    <v-btn v-on:click="modalAvaliation = true; sendToAvail = props.item;">Avaliar</v-btn>
                  </td>
                </template>
              </v-data-table>
            </template>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
    <v-dialog v-model="modalAvaliation" max-width="690px">
      <v-card>
        <v-card-title>
          <h5 v-if="!!sendToAvail">Avaliação de {{sendToAvail.user.name}}</h5>
        </v-card-title>
        <v-card-text>
          <form v-on:submit="sendAvaliation($event)">
            <v-text-field
              label="Nota"
              v-model="score"
              type="text"
              required
            ></v-text-field>
            <v-text-field
              label="Comentário"
              v-model="comment"
              type="text"
              multi-line
            ></v-text-field>
            <v-flex text-xs-right>
              <v-btn type="submit">Enviar avaliação</v-btn>
              <v-btn v-on:click="modalAvaliation = false">Cancelar</v-btn>
            </v-flex>
          </form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
  <v-container v-else>
    <h5>Você não tem permissão para acessar isto.</h5>
    <v-btn :to="`/discipline/${discipline.id}/task/${topicItem.id}`">Voltar</v-btn>
  </v-container>
</template>

<script>
  import UserSession from '@/store/UserSession';
  import TopicStore from '@/store/Topic';
  import DisciplineStore from '@/store/Discipline';

  export default {
    data: () => ({
      topicItem: [],
      discipline: [],
      subscription: [],
      avaliations: [],
      accessLevel: UserSession.get('accessLevel'),
      sendList: [],
      disciplineRole: 0,
      modalAvaliation: false,
      sendToAvail: null,
      evaluation: [],
      score: '',
      comment: '',
      months: [
        'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out',
        'Nov', 'Dez',
      ],
      weekDays: [
        'Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb',
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
          align: 'left',
        },
        {
          text: 'Nota',
          value: 'score',
          align: 'left',
        },
        {
          text: 'Arquivo',
          value: 'file',
          align: 'left',
        },
        {
          text: 'Avaliar',
          value: 'avaliation',
          align: 'left',
        },
      ],
      rowsPerPageItems: [
        5, 10, 25,
        {
          text: 'Todas',
          value: -1,
        },
      ],
    }),
    created() {
      TopicStore.dispatch({
        action: TopicStore.ACTION_GET_TOPIC_ITEM,
        data: this.$router.currentRoute.params.topicItemId,
      });
      TopicStore.dispatch({
        action: TopicStore.ACTION_GET_EVALUATIONS,
        data: this.$router.currentRoute.params.topicItemId,
      });
      TopicStore.dispatch({
        action: TopicStore.ACTION_SENDS,
        data: this.$router.currentRoute.params.topicItemId,
      });
      TopicStore.on('sends', (sends) => {
        this.sendList = sends;
      }, this);
      TopicStore.on('evaluations', (avaliationList) => {
        this.avaliations = avaliationList;
      }, this);
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_GET_SUBSCRIPTION,
        data: this.$router.currentRoute.params.id,
      });
      TopicStore.on('evaluated', () => {
        this.modalAvaliation = false;
        this.sendToAvail = null;
        TopicStore.dispatch({
          action: TopicStore.ACTION_GET_EVALUATIONS,
          data: this.$router.currentRoute.params.topicItemId,
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
      TopicStore.on('successDownloadZip', (zipFile, xhr) => {
        this.handleDownload(zipFile, xhr);
      }, this);
      TopicStore.on('successDownload', (file, xhr) => {
        this.handleDownload(file, xhr);
      }, this);
      TopicStore.on('getTopicItemById', (topicItem) => {
        this.topicItem = topicItem.data;
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_GET_SUBSCRIBED_USERS,
          data: this.topicItem.topic.discipline.id,
        });
      }, this);
    },
    methods: {
      downloadUserFile(event, user) {
        event.preventDefault();
        TopicStore.dispatch({
          action: TopicStore.ACTION_DOWNLOAD_USER_FILE,
          data: {
            topicItem: this.topicItem,
            user,
          },
        });
      },
      downloadAllSends() {
        TopicStore.dispatch({
          action: TopicStore.ACTION_SENDS_DOWNLOAD,
          data: this.$router.currentRoute.params.topicItemId,
        });
      },
      sendAvaliation(event) {
        event.preventDefault();
        TopicStore.dispatch({
          action: TopicStore.ACTION_SEND_EVALUATION,
          data: {
            topicItem: {
              id: this.$router.currentRoute.params.topicItemId,
            },
            avaliation: {
              score: this.score,
              comment: this.comment,
            },
            user: this.sendToAvail.user,
          },
        });
      },
      handleDownload(response, xhr) {
        const link = document.createElement('a');
        const contentType = xhr.getResponseHeader('content-type');
        const filename = xhr.getResponseHeader('filename');

        const blob = new Blob([response], { type: contentType }, filename);
        link.href = window.URL.createObjectURL(blob);
        link.download = filename;
        link.click();
      },
      getScore(user) {
        console.log(user);
      },
      handleDate(date) {
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
        const fullDate = `${this.weekDays[wd]}, ${d} de ${this.months[m]} de ${y} as ${hr}:${min} `;
        return fullDate;
      },
    },
  };
</script>
