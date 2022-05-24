var button = document.getElementById('envoie');

button.addEventListener('click', function (e){
  nom = document.getElementById('nom');
  marque = document.getElementById('marque');
  description = document.getElementById('description');
  img = document.getElementById('img');
  img1 = document.getElementById('img1');
  img2 = document.getElementById('img2');
  img3 = document.getElementById('img3');
  img4 = document.getElementById('img4');
  categorie = document.getElementById('categorie');
  date = document.getElementById('date');
  e.preventDefault();

  response = false

  if(nom.value == ""){
    response = true
    nom.classList.add("border-red-500");
  }else{
    nom.classList.remove("border-red-500");
  }

  if(marque.value == ""){
    response = true
    marque.classList.add("border-red-500");
  }else{
    marque.classList.remove("border-red-500");
  }

  if(description.value == ""){
    response = true
    description.classList.add("border-red-500");
  }else{
    description.classList.remove("border-red-500");
  }

  if(img.value == ""){
    response = true
    img.classList.add("border-red-500");
  }else{
    img.classList.remove("border-red-500");
  }

  if(img1.value == ""){
    response = true
    img1.classList.add("border-red-500");
  }else{
    img1.classList.remove("border-red-500");
  }

  if(img2.value == ""){
    response = true
    img2.classList.add("border-red-500");
  }else{
    img2.classList.remove("border-red-500");
  }

  if(img3.value == ""){
    response = true
    img3.classList.add("border-red-500");
  }else{
    img3.classList.remove("border-red-500");
  }

  if(img4.value == ""){
    response = true
    img4.classList.add("border-red-500");
  }else{
    img4.classList.remove("border-red-500");
  }

  if(categorie.value == ""){
    response = true
    categorie.classList.add("border-red-500");
  }else{
    categorie.classList.remove("border-red-500");
  }

  if(response == false){
        document.getElementById('formulaire').submit(); 
  }
})