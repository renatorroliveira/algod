<template>

	<div id="app">
		<nav class="blue-grey darken-2">
			<div class="nav-wrapper container">
				<a href="#/" class="brand-logo">
					<img src="./assets/logoAGlighten.png" style="height: 50px; margin: 10px auto;" />
				</a>
				<ul id="nav-mobile" class="right hide-on-med-and-down">
					<li><a href="#/login">Entrar</a></li>
					<li><a href="#/register">Cadastre-se</a></li>
				</ul>
			</div>
		</nav>

	<div class="blue-grey darken-3" style="height: 5px; margin: 5px auto;"></div>

		<div class="container" style="margin: 20px auto;">
			<router-view></router-view>
		</div>

		<footer class=" blue-grey darken-3 page-footer">
			<div class="container center-align">
				<div class="row">
						<h5 class="white-text">Instituto Federal Catarinense Campus Concórdia</h5>
						<h6 class="white-text">Curso Técnico em Informática</h6>
				</div>
			</div>
			<div class="footer-copyright">
				<div class="container center-align">
					<h6>© 2017 Copyright Text</h6>
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
