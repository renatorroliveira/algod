<template>
	<div class="row">
		<div class="col m6 offset-m3">
	    	<form class="card-panel z-depth-2" v-on:submit="register($event)">
	    		<h2 class="center">Cadastre-se</h2>

	    		<div class="input-field">
					<input id="fullname" name="fullname" v-model="fullname" type="text" class="validate" required />
					<label for="fullname">Nome completo</label>
				</div>
	    		<div class="input-field">
					<input id="nickname" name="nickname" v-model="nickname" type="text" class="validate" required />
					<label for="nickname">Apelido (nome curto)</label>
				</div>
	    		<div class="input-field">
					<input id="email" name="email" v-model="email" type="email" class="validate" required />
					<label for="email">E-mail</label>
				</div>
	    		<div class="input-field">
					<input id="password" name="password" v-model="password" type="password" class="validate" required />
					<label for="password">Senha</label>
				</div>
	    		<div class="input-field">
					<input id="passwordconfirm" name="passwordconfirm" v-model="passwordconfirm" type="password" class="validate" required />
					<label for="passwordconfirm">Confirme a senha</label>
				</div>
	    		<div class="row valign-wrapper">
					<div class="input-field col s4">
						<input value="Cadastrar" type="submit" class="btn" />
					</div>
					<div class="col s8 right-align">
						<div><a href="#/login">Já tem uma conta? Faça o login.</a></div>
						<div><a href="#/forgot-password">Esqueceu sua senha?</a></div>
					</div>
				</div>
	    	</form>
	    </div>
	</div>
</template>

<script>
export default {
  name: 'registration',
  data () {
    return {
      email: '',
      password: '',
      passwordconfirm: '',
      fullname: "",
      nickname: "",
    }
  },
  methods: {
  	register: function(event) {
  		event && event.preventDefault();
  		// Faria o cadastro com AJAX
  		console.log(this.email);
  		console.log(this.password);
  	}
  }
}
</script>
