<template>
	<div id="app">
		<nav>
			<div class="nav-wrapper container">
				<a href="#/" class="brand-logo">
					<img src="./assets/logo-50px.png" style="height: 50px; margin: 10px auto;" />
				</a>
				<ul id="nav-mobile" class="right hide-on-med-and-down">
					<li><a href="#/login">Entrar</a></li>
					<li><a href="#/register">Cadastre-se</a></li>
					<li><a href="#/disciplina">Disciplina</a></li>
					<li><a href="#/curso">Cursos</a></li>
					<li><a href="#/atividade">Atividade</a></li>
				</ul>
			</div>
		</nav>

	<div class="blue-grey darken-3" style="height: 5px; margin: 5px auto;"></div>

		<div class="container" style="margin: 20px auto;">
			<router-view></router-view>
		</div>

		<footer class="page-footer">
			<div class="container center-align">
				<div class="row">
					<h5 class="white-text">Instituto Federal Catarinense Campus Concórdia</h5>
					<h6 class="white-text">Curso Técnico em Informática para Internet</h6>
				</div>
			</div>
			<div class="footer-copyright">
				<div class="container center-align">
					<h6>© 2017 Copyright</h6>
				</div>
			</div>
		</footer>

	</div>
</template>

<script>
export default {
	name: 'app'
}
</script>

<style scoped>
	body {
    display: flex;
    min-height: 100vh;
    flex-direction: column;
  }

  main {
    flex: 1 0 auto;
  }
</style>