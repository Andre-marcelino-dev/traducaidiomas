document.addEventListener("DOMContentLoaded", function () {

    const tipo = document.getElementById("tipoUsuario");
    const curso = document.getElementById("campoCurso");
    const nivel = document.getElementById("campoNivel");

    function controlarCampos() {
        if (tipo.value === "professor") {
            curso.disabled = true;
            nivel.disabled = true;
            curso.value = "";
            nivel.value = "";
        } else {
            curso.disabled = false;
            nivel.disabled = false;
        }
    }

    controlarCampos();
    tipo.addEventListener("change", controlarCampos);

});