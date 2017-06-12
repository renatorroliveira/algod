<template>
	<div class="row">
		<div class="col m6 offset-m3">
	    	<form class="card-panel z-depth-2" v-on:submit="login($event)">
	    		<h2 class="center">Faça seu Login</h2>

	    		<div class="input-field">
					<input id="email" name="email" v-model="email" type="email" class="validate" required />
					<label for="email">E-mail</label>
				</div>
	    		<div class="input-field">
					<input id="password" name="password" v-model="password" type="password" class="validate" required />
					<label for="password">Senha</label>
				</div>
	    		<div class="row valign-wrapper">
					<div class="input-field col s4">
						<input value="Entrar" type="submit" class="btn" />
					</div>
					<div class="col s8 right-align">
						<div><a href="#/register">Não tem uma conta? Cadastre-se.</a></div>
						<div><a href="#/forgot-password">Esqueceu sua senha?</a></div>
					</div>
				</div>
	    	</form>
	    </div>
	</div>
</template>

<script>
export default {
  name: 'login',
  data () {
    return {
      email: '',
      password: '',
    }
  },
  methods: {
  	login: function(event) {
  		event && event.preventDefault();
  		// Faria o login com AJAX
  		console.log(this.email);
  		console.log(this.password);
  	}
  }
}
</script>
