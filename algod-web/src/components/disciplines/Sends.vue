<template>
  <v-container>
    <v-layout row wrap>
      <v-flex xs12>
        <v-card>
          <v-card-title>
            <v-flex xs8>
              <span style="font-size: 20px;"><strong>Envios de {{topicItem.label}}</strong></span>
            </v-flex>
            <v-flex xs4 class="text-xs-right">
              <v-btn title="Fazer download de todos os envios" v-if="sendList.length > 0" flat v-on:click="downloadAllSends()">
                <v-icon>file_download</v-icon>
                Download
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
                  <td class="text-xs-right">
                    <v-btn v-if="avaliation.score === 0.0" v-on:click="modalAvaliation = true; userToAvail = props.item.user.name;">Avaliar</v-btn>
                    <v-btn v-else v-on:click="modalAvaliation = true">Editar avaliação</v-btn>
                  </td>
                </template>
              </v-data-table>
            </template>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
    <v-dialog v-model="modalAvaliation">
      <v-card>
        <v-card-text>
          fkjsdkl
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
  import TopicStore from '@/store/Topic';
  import DisciplineStore from '@/store/Discipline';

  export default {
    data: () => ({
      topicItem: [],
      discipline: [],
      subscription: [],
      avaliation: [],
      sendList: [],
      modalAvaliation: false,
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
        },
        {
          text: 'Nome do arquivo',
          value: 'filename',
        },
        {
          text: 'Avaliar',
          value: 'avaliation',
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
        action: TopicStore.ACTION_AVALIATION,
        data: this.$router.currentRoute.params.topicItemId,
      });
      TopicStore.dispatch({
        action: TopicStore.ACTION_SENDS,
        data: this.$router.currentRoute.params.topicItemId,
      });
      TopicStore.on('sends', (sends) => {
        this.sendList = sends;
      }, this);

      TopicStore.on('successDownloadZip', (zipFile, xhr) => {
        console.log(zipFile, xhr);
        // this.handleDownloads(zipFile, xhr, 'zip');
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
      downloadAllSends() {
        TopicStore.dispatch({
          action: TopicStore.ACTION_SENDS_DOWNLOAD,
          data: this.$router.currentRoute.params.topicItemId,
        });
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
