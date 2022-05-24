var button = document.getElementById('envoie');

button.addEventListener('click', function (e){
  subject = document.getElementById('subject');
  email = document.getElementById('email');
  tel = document.getElementById('tel');
  message = document.getElementById('message');
  e.preventDefault();

  response = false

  if(subject.value == ""){
    response = true
    subject.classList.add("border-red-500");
  }else{
    subject.classList.remove("border-red-500");
  }

  if(tel.value == ""){
    response = true
    tel.classList.add("border-red-500");
  }else{
    tel.classList.remove("border-red-500");
  }

  if(message.value == ""){
    response = true
    message.classList.add("border-red-500");
  }else{
    message.classList.remove("border-red-500");
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