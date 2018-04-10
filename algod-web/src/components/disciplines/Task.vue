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

      <v-flex xs12 text-xs-justify>
        <v-card style="min-height:200px;">
          <v-card-text>
            <p>{{topicItem.description}}</p>
            <v-spacer class="mb-3"></v-spacer>

            <div v-if="topicItem.type === 'Task'">
              <form id="formulario" v-on:submit="uploadFile($event)" enctype="multipart/form-data">
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

          <v-dialog v-model="sends" max-width="1000px">
            <v-card>
              <v-card-title><h3>Envios</h3></v-card-title>
              <v-card-text>
                <template>
                  <v-data-table
                    :headers="headers"
                    :items="items"
                    hide-actions
                    class="elevation-1"
                    >
                    <template slot="items" slot-scope="props">
                      <td>{{ props.item.name }}</td>
                      <td class="text-xs-right">{{ props.item.date }}</td>
                      <td class="text-xs-right">{{ props.item.arq }}</td>
                    </template>
                  </v-data-table>
                </template>
              </v-card-text>
            </v-card>
          </v-dialog>

        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import UserSession from '@/store/UserSession';
  import TopicStore from '@/store/Topic';

  export default {
    data: () => ({
      accessLevel: UserSession.get('accessLevel'),
      topicItem: [],
      content: '',
      file: null,
      sends: false,
      headers: [{ text: 'Nome', align: 'left', sortable: true, value: 'name' },
         { text: 'Data', value: 'date' },
         { text: 'Arquivo', value: 'arq' }],
      items: [{ value: false, name: 'Gustavo', date: '22/04/2018', arq: 'jogo.rar' },
              { value: false, name: 'Guilherme', date: '23/04/2018', arq: 'joguinho.rar' }],
    }),
    created() {
      TopicStore.dispatch({
        action: TopicStore.ACTION_GET_TOPIC_ITEM,
        data: this.$router.currentRoute.params.topicItemId,
      });
      TopicStore.on('getTopicItemById', (topicItem) => {
        this.topicItem = topicItem.data;
      }, this);
    },
    methods: {
      fileSelected(e) {
        console.log(e);
      },
      uploadFile(event) {
        event.preventDefault();
        const formData = new FormData();
        console.log(event.target);
        console.log(formData);
        // if (form[0].children[0].files[0]) {
        //   TopicStore.dispatch({
        //     action: TopicStore.ACTION_UPLOAD,
        //     data: {
        //       formData: form[0].children[0].files[0],
        //       topicItem: this.topicItem,
        //     },
        //   });
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
