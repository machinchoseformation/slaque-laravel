//fonctionne si les éléments étaient créés au chargement de la page
$(".ma-class").on("click", mafonction);

//s'ils sont créés par le JS, utiliser plutôt ceci
$("body").on("click", ".ma-class", mafonction);

