var button = document.getElementById('envoie');

button.addEventListener('click', function (e){
  mdp = document.getElementById('mdp');
  email = document.getElementById('email');
  e.preventDefault();

  response = false

  if(mdp.value == ""){
    response = true
    mdp.classList.add("border-red-500");
  }else{
      mdp.classList.remove("border-red-500");
  }

  if(email.value == ""){
    response = true
    email.classList.add("border-red-500");
  }else{
      email.classList.remove("border-red-500");
  }

  if(response == false){
    document.getElementById('formulaire').submit(); 
  }
})