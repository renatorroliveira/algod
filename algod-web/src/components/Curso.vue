<template>
	<div id="principal" class="container">
		<div class="col s12 m6">
			<div class="card">
				<div class="card-content texto-teste">
					<span class="card-title">Atividades</span>
					<div>
						<ol>
							<hr>
							<li><a href="#/atividade" >
								lista de atividades //fazer com v-for
							</a><hr></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
  name: 'curso',
  data () {
    return {
      atividade: ''
    }
  },
}
</script>
