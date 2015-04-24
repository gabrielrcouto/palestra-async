//$(document).ready(function() {
	//Use funções de start para cada função do site

	//Inicia todos os módulos
	for(var propertyName in Modules) {
	   Modules[propertyName].start();
	}

	//Inicia todas as páginas
	for(var propertyName in Pages) {
	   Pages[propertyName].start();
	}
//});