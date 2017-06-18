<template>
	<div id="principal">
	<div class="col s12 m6 z-depth-2">
    	<div class="card light-blue">
    		<div class="card-content white-text">
    			<span class="titulo-teste">Disciplinas</span>
 			</div>
    	</div>
    </div>

		<div class="container">
	  	<ul class="collapsible z-depth-4" data-collapsible="expandable">
	    	<li>
	      		<div class="collapsible-header"><a class="texto-teste">{{ ano }}° Ano</a></div>
	      		<div class="collapsible-body white">
	      			<ul class="collapsible" data-collapsible="expandable">
	      				<li>
	      					<div class="collapsible-header">
	      						<a class="texto-teste">{{ materia1 }}</a>
	      					</div>
	      					<div class="collapsible-body white">
	      						<a href="#/login">Entrar</a>
	      					</div>
	      				</li>
	      				<li>
	      					<div class="collapsible-header"><a class="texto-teste">{{ materia2 }}</a></div>
	      					<div class="collapsible-body white"><a href="#/login">Entrar</a></div>
	      				</li>
	      				<li>
	      					<div class="collapsible-header"><a class="texto-teste">{{ materia3 }}</a></div>
	      					<div class="collapsible-body white"><a href="#/login">Entrar</a></div></li>
	      				<li>
	      					<div class="collapsible-header"><a class="texto-teste">{{ materia4 }}</a></div>
	      					<div class="collapsible-body white"><a href="#/login">Entrar</a></div>
	      				</li>
	      			</ul>
	      		</div>
	    	</li>
	    </ul>
	</div>
	</div>
</template>

<script>
export default {
  name: 'disciplina',
  data () {
    return {
    	ano: '2',
		materia1: 'Desenvolvimento Web II',
		materia2: 'Programação II',
		materia3: 'Desenvolvimento de Sistemas para Dispositivos Móveis I',
		materia4: 'Redes I'
    }
  },
}
</script>