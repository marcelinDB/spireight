var button = document.getElementById('envoie');
var list = [];

document.querySelectorAll('a#lock').forEach(element => {
  element.addEventListener('click', (e) => {
    e.preventDefault();
    e.target.style.backgroundColor = "red";
    switch (e.target.closest("a").getAttribute('href')) {
      case "nom":
        if(document.getElementById('nom').disabled == true){
          document.getElementById('nom').disabled = false
        }else{
          document.getElementById('nom').disabled = true
          e.target.style.backgroundColor = "rgb(74 222 128 / var(--tw-bg-opacity))";
        }
        list.push('nom')
        break;
      case "prenom":
        if(document.getElementById('prenom').disabled == true){
          document.getElementById('prenom').disabled = false
        }else{
          document.getElementById('prenom').disabled = true
          e.target.style.backgroundColor = "rgb(74 222 128 / var(--tw-bg-opacity))";
        }
        list.push('prenom')
        break;
      case "email":
        if(document.getElementById('email').disabled == true){
          document.getElementById('email').disabled = false
        }else{
          document.getElementById('email').disabled = true
          e.target.style.backgroundColor = "rgb(74 222 128 / var(--tw-bg-opacity))";
        }
        list.push('email')
        break;
      case "tel":
        if(document.getElementById('tel').disabled == true){
          document.getElementById('tel').disabled = false
        }else{
          document.getElementById('tel').disabled = true
          e.target.style.backgroundColor = "rgb(74 222 128 / var(--tw-bg-opacity))";
        }
        list.push('tel')
        break;
      case "societe":
        if(document.getElementById('societe').disabled == true){
          document.getElementById('societe').disabled = false
        }else{
          document.getElementById('societe').disabled = true
          e.target.style.backgroundColor = "rgb(74 222 128 / var(--tw-bg-opacity))";
        }
        list.push('societe')
        break;
      case "date":
        if(document.getElementById('date').disabled == true){
          document.getElementById('date').disabled = false
        }else{
          document.getElementById('date').disabled = true 
          e.target.style.backgroundColor = "rgb(74 222 128 / var(--tw-bg-opacity))";
        }
        list.push('date')
        break;
      case "mdp":
        if(document.getElementById('mdp').disabled == true){
          document.getElementById('mdp').disabled = false
          document.getElementById('confmdp').disabled = false
        }else{
          document.getElementById('mdp').disabled = true
          document.getElementById('confmdp').disabled = true
          e.target.style.backgroundColor = "rgb(74 222 128 / var(--tw-bg-opacity))";
        }
        list.push('mdp')
        break;
    }
  })
});

button.addEventListener('click', function (e) {
  nom = document.getElementById('nom');
  prenom = document.getElementById('prenom');
  mdp = document.getElementById('mdp');
  confmdp = document.getElementById('confmdp');
  societe = document.getElementById('societe');
  email = document.getElementById('email');
  date = document.getElementById('date');
  tel = document.getElementById('tel');
  e.preventDefault();


  response = false

  if (nom.value == "" && nom.disabled != true) {
    response = true
    nom.classList.add("border-red-500");
  } else {
    nom.classList.remove("border-red-500");
  }

  if (prenom.value == "" && prenom.disabled != true) {
    response = true
    prenom.classList.add("border-red-500");
  } else {
    prenom.classList.remove("border-red-500");
  }

  if (confmdp.value == "" && confmdp.disabled == false) {
    response = true
    confmdp.classList.add("border-red-500");
  } else {
    confmdp.classList.remove("border-red-500");
  }

  if (mdp.value == "" && mdp.disabled == false) {
    response = true
    mdp.classList.add("border-red-500");
  } else {
    mdp.classList.remove("border-red-500");
    if (confmdp.value == mdp.value && mdp.disabled == false ) {
      if (!isStrongPwd1(mdp.value)) {
        message += `Votre mot de passe doit avoir:
        - Un caractère spécifique
        - Une majuscule et minuscule
        - Des chiffres et lettres`;
        response = true
      }
    }else if(mdp.disabled == false){
      message += "Les mots de passe ne correspondent pas";
      response = true
      confmdp.classList.add("border-red-500");
      mdp.classList.add("border-red-500");
    }
  }

  if (tel.value == "" && tel.disabled != true) {
    response = true
    tel.classList.add("border-red-500");
  } else {
    tel.classList.remove("border-red-500");
  }

  if (date.value == "" && date.disabled != true) {
    response = true
    date.classList.add("border-red-500");
  } else {
    date.classList.remove("border-red-500");
  }

  if (email.value == "" && email.disabled != true) {
    response = true
    email.classList.add("border-red-500");
  } else {
    email.classList.remove("border-red-500");
  }

  if (response == false) {
    document.getElementById('formulaire').submit();
  }

  if(message != ""){
    document.getElementById('erreur').value = message
    document.getElementById('erreur').classList.remove("hidden");
  }else{
    document.getElementById('erreur').value = ""
    document.getElementById('erreur').classList.add("hidden");
  }
})
if(message != ""){
  document.getElementById('erreur').value = message
  document.getElementById('erreur').classList.remove("hidden");
}else{
  document.getElementById('erreur').value = ""
  document.getElementById('erreur').classList.add("hidden");
}
function isStrongPwd1(password) {

  var regExp = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&*()]).{8,}/;

  var validPassword = regExp.test(password);

  return validPassword;

}