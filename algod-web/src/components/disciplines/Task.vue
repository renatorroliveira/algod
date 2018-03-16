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
            <div v-if="topicItem.contentType === 1">
              <form id="upload-widget" method="post" action="/upload" class="dropzone">
                <div class="fallback">
                  <input name="file" type="file" />
                </div>
              </form>

                <upload-button title="Escolher arquivo" :selectedCallback="fileSelected">
              </upload-button>
              <v-btn dark class="btn--dark-flat-focused jbtn-file"></v-btn>
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
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import DisciplineStore from '@/store/Discipline';
  import UploadButton from './UploadButton';

  export default {
    data: () => ({
      topicItem: [],
      content: '',
    }),
    created() {
      DisciplineStore.dispatch({
        action: DisciplineStore.ACTION_GET_TOPIC_ITEM,
        data: this.$router.currentRoute.params.topicItemId,
      });
      DisciplineStore.on('getTopicItemById', (topicItem) => {
        this.topicItem = topicItem.data;
      }, this);
    },
    methods: {
      fileSelected(e) {
        console.log(e);
        DisciplineStore.dispatch({
          action: DisciplineStore.ACTION_UPLOAD,
          data: e,
        });
      },
    },
    components: {
      UploadButton,
    },
  };
</script>
