var button = document.getElementById('envoie');

button.addEventListener('click', function (e){
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

  if(nom.value == ""){
    response = true
    nom.classList.add("border-red-500");
  }else{
    nom.classList.remove("border-red-500");
  }

  if(prenom.value == ""){
    response = true
    prenom.classList.add("border-red-500");
  }else{
    prenom.classList.remove("border-red-500");
  }

  if(mdp.value == ""){
    response = true
    mdp.classList.add("border-red-500");
  }else{
    mdp.classList.remove("border-red-500");
  }

  if(confmdp.value == ""){
    response = true
    confmdp.classList.add("border-red-500");
  }else{
    confmdp.classList.remove("border-red-500");
  }

  if(tel.value == ""){
    response = true
    tel.classList.add("border-red-500");
  }else{
    tel.classList.remove("border-red-500");
  }

  if(date.value == ""){
    response = true
    date.classList.add("border-red-500");
  }else{
    date.classList.remove("border-red-500");
  }

  if(email.value == ""){
    response = true
    email.classList.add("border-red-500");
  }else{
    email.classList.remove("border-red-500");
  }

  if(response == false){
    if(confmdp.value == mdp.value){
      if(isStrongPwd1(mdp.value)){
        document.getElementById('formulaire').submit(); 
      }else{
        message += `Votre mot de passe doit avoir:
        - Un caractère spécifique
        - Une majuscule et minuscule
        - Des chiffres et lettres`;
        confmdp.classList.add("border-red-500");
        mdp.classList.add("border-red-500");
      }
    }else{
      message += "Les mots de passe ne correspondent pas";
      confmdp.classList.add("border-red-500");
      mdp.classList.add("border-red-500");
    }
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