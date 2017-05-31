<template>
	<div id="app">
		<nav class="black">
			<div class="nav-wrapper container">
				<a href="#/" class="brand-logo">
					<img src="./assets/logo.png" style="height: 50px; margin: 10px auto;" />
				</a>
				<ul id="nav-mobile" class="right hide-on-med-and-down">
					<li><a href="#/login">Entrar</a></li>
					<li><a href="#/register">Cadastre-se</a></li>
				</ul>
			</div>
		</nav>
		<div class="blue-grey" style="height: 5px; margin: 5px auto;"></div>

		<div class="container" style="margin: 20px auto;">
			<router-view></router-view>
		</div>

		<footer class="page-footer">
			<div class="container">
			<div class="row">
				<div class="col l6 s12">
					<h5 class="white-text">Renatorro</h5>
					<p class="grey-text text-lighten-4">Ta muito Lindo pode Fala</p>
				</div>
			</div>
		</div>
		<div class="footer-copyright">
			<div class="container">
				© 2017 Copyright Text
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
