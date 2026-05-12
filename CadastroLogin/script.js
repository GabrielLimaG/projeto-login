function mostrarSenha() {
  const input = document.getElementById("idsenha");
  const btn = document.getElementById("btnsenha");

  if (input.type === "password") {
    input.type = "text";
    btn.innerHTML = '<i class="bx bx-show"></i>';
  } else {
    input.type = "password";
    btn.innerHTML = '<i class="bx bx-hide"></i>';
  }
}

function mostrarSenha2() {
  const input = document.getElementById("idsenha");
  const btn = document.getElementById("btnsenha1");

  if (input.type === "password") {
    input.type = "text";
    btn.innerHTML = '<i class="bx bx-show"></i>';
  } else {
    input.type = "password";
    btn.innerHTML = '<i class="bx bx-hide"></i>';
  }
}

function mostrarSenha3() {
  const input = document.getElementById("idsenha2");
  const btn = document.getElementById("btnsenha2");

  if (input.type === "password") {
    input.type = "text";
    btn.innerHTML = '<i class="bx bx-show"></i>';
  } else {
    input.type = "password";
    btn.innerHTML = '<i class="bx bx-hide"></i>';
  }
}

function mostrarRec() {
  document.querySelector("#RecSenha").style.display = "block";
  document.getElementById("conf").disabled = true;
  document.getElementById("idnome").disabled = true;
  document.getElementById("idsenha").disabled = true;
  document.getElementById("idemail").disabled = true;
}

function sairRec () {
  document.querySelector("#RecSenha").style.display = "none";
  document.getElementById("conf").disabled = false;
  document.getElementById("idnome").disabled = false;
  document.getElementById("idsenha").disabled = false;
  document.getElementById("idemail").disabled = false;
}

document.addEventListener("DOMContentLoaded", function () {

    if (erroPHP === "erro-cad") {
        document.getElementById("erro-cadastro").style.display = "block";
        document.getElementById("avisos").style.display = "block";
    }

    if (erroPHP === "negado") {
        document.getElementById("aviso-erro-login").style.display = "block";
        document.getElementById("avisos").style.display = "block";
    }

    if (erroPHP === "erro_encontrado") {
        document.getElementById("aviso-erro-rec").style.display = "block";
        document.getElementById("avisos-rec").style.display = "block";
        document.querySelector("#RecSenha").style.display = "block";
    }

    if (erroPHP === "erro_rec_email") {
        document.getElementById("aviso-erro-rec-email").style.display = "block";
        document.getElementById("avisos-rec").style.display = "block";
        document.querySelector("#RecSenha").style.display = "block";
    }

    if (erroPHP === "sucesso_email") {
        document.getElementById("aviso-sucesso-rec-email").style.display = "block";
        document.getElementById("avisos-rec").style.display = "block";
        document.querySelector("#RecSenha").style.display = "block";
        document.getElementById("aviso-sucesso-rec-email").style.border = "4px solid green";
    }

    if (erroPHP === "senha_diferente") {
        document.getElementById("aviso-erro-senhadif").style.display = "block";
        document.getElementById("avisos").style.display = "block";
    }

    if (erroPHP === "tentativas") {
        document.getElementById("aviso-tentativas").style.display = "block";
        document.getElementById("avisos").style.display = "block";
    }

});
