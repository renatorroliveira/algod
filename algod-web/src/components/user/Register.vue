<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 md6 offset-md3>
        <v-card>
          
          <v-card-text>
            <h1 class="title">Search for new keywords using a phrase, website or category</h1>
            <h2 class="subheading mb-5">Enter one or more of the following</h2>
            <v-text-field
              label="Your product or service"
              value="Grocery delivery"
              hint="For example, flowers or used cars"
              persistent-hint
            ></v-text-field>
            <v-text-field
              label="Your landing page"
              hint="www.example.com/page"
              persistent-hint
            ></v-text-field>
            <v-select
              label="Your product category"
              hint="Enter or select a product category"
              persistent-hint
              :items="options"
              v-model="select"
            ></v-select>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
  
</template>

<script>
export default {
  name: 'registration',
  data() {
    return {
      email: '',
      password: '',
      passwordconfirm: '',
      fullname: '',
      nickname: '',
      select: [],
      options: [
        {
          value: 1,
          text: 'Option 1',
        },
        {
          value: 2,
          text: 'Option 2',
        },
        {
          value: 3,
          text: 'Option 3',
        },
      ],
    };
  },
  methods: {
    register(event) {
      event.preventDefault();
      // Faria o cadastro com AJAX
      console.log(this.email);
      console.log(this.password);
    },
  },
};
</script>
