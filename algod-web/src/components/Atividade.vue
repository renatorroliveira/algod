<template>
	<div id="principal" class="container">
      <div class="col s12 m6">
        <div class="card">
          <div class="card-content texto-teste">
            <span class="card-title">Nome Da atividade</span>
          <div class="card z-depth-3">
            <div class="card-content texto-teste">
              <p class="texto-teste">Descrição da atividade</p>
            </div>
          </div>
          <form>
            <textarea v-model="resposta" rows="20" cols="80" maxlength="1000" class="texto-teste">Digite sua resposta</textarea>
            <button class="btn waves-effect waves-light" type="submit" name="action">Enviar
              <i class="material-icons right">send</i>
            </button>
          </form>
        </div>
      </div>
    </div>
</div>

</template>

<script>
export default {
  name: 'atividade',
  data () {
    return {
      resposta: ''
    }
  },
}
</script>
