<template>
	<div id="app">
		<div id="navbar" class="blue-grey darken-3">
			<div id="navbar-content">
				<a href="#/">
					<img src="./assets/logo-50px.png" style="display:block; width: auto; margin:10px auto;" />
				</a>
				<div>
					<div><a href="#/disciplina">Disciplina</a></div>
					<div><a href="#/curso">Cursos</a></div>
					<div><a href="#/atividade">Atividade</a></div>
				</div>
			</div>

			<footer class="page-footer">
				<div class="center-align">
					<p style="font-size:12px;" class="white-text">Instituto Federal Catarinense Campus Concórdia</p>
					<b style="font-size:16px;" class="white-text">Curso Técnico em Informática para Internet</b>
				</div>

				<div class="footer-copyright">
					<div class="center-align">
						<span>© 2017 Copyright</span>
					</div>
				</div>
			</footer>

	</div>
		<main class="container" style="margin: 20px auto;">
			<router-view></router-view>
		</main>
	</div>
</template>

<script>
export default {
	name: 'app'
}
</script>

<style scoped>
#app {
    display: flex;
    min-height: 100vh;
    flex-direction: row;
}

#navbar {
	min-width: 200px;
	flex: 4;
	display: flex;
	flex-direction: column;
	min-height: 100vh;
}
#navbar-content {
	padding: 15px;
	flex: 1 0 auto;
}
main {
	flex: 16;
	padding: 20px;
}
</style>
