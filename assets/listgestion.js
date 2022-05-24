


var vue = ""
produit.sort(function(a, b) {
  return a.cat.localeCompare(b.cat)
})
content = document.createElement("div")
content.classList.add("grid", "gap-4", "grid-cols-11", "sm:grid-cols-11", "lg:grid-cols-11", "xl:grid-cols-11")
for (let i = 0; i < produit.length; i++) {
  vue += `<a href="./article?id=${produit[i].id}" class="col-start-1 col-end-5 w-[50%] m-auto h-[209px]">
  <div class="col-start-1 col-end-3 w-full m-auto h-[209px] bg-center bg-cover " style="background-image:url('${produit[i].img}'); background-size: cover; "></div>
  </a>
  <div class="col-start-5 col-end-8 w-full m-auto flex justify-center items-center">
  <h1 class="inline-block">${produit[i].nom}</h1>
  </div>
  <div class="col-start-8 col-end-10 w-full m-auto">
    <h1 class="">${produit[i].cat}</h1>
  </div>
  <div class="col-start-10 col-end-12 w-full m-auto">
   <h1 class=""><a href="./gestion_produit?idproduct=${produit[i].id}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Redirection</a></h1>
  </div>`
}
content.innerHTML = vue
document.getElementById('produit').appendChild(content);

var order = 1
document.getElementById('boutonCategorie').addEventListener('click',()=>{
  document.getElementById('produit').innerHTML = ""
  vue = ""
  if (order == 0) {
    order = 1
    produit.sort(function(a, b) {
      return a.cat.localeCompare(b.cat)
    })
  }else{
    order = 0
    produit.sort(function(a, b) {
      return b.cat.localeCompare(a.cat)
    })
  }
  content = document.createElement("div")
content.classList.add("grid", "gap-4", "grid-cols-11", "sm:grid-cols-11", "lg:grid-cols-11", "xl:grid-cols-11")
for (let i = 0; i < produit.length; i++) {
  vue += `<a href="./article?id=${produit[i].id}" class="col-start-1 col-end-5 w-[50%] m-auto h-[209px]">
  <div class="col-start-1 col-end-3 w-full m-auto h-[209px] bg-center bg-cover " style="background-image:url('${produit[i].img}'); background-size: cover; "></div>
  </a>
  <div class="col-start-5 col-end-8 w-full m-auto flex justify-center items-center">
  <h1 class="inline-block">${produit[i].nom}</h1>
  </div>
  <div class="col-start-8 col-end-10 w-full m-auto">
    <h1 class="">${produit[i].cat}</h1>
  </div>
  <div class="col-start-10 col-end-12 w-full m-auto">
   <h1 class=""><a href="./gestion_produit?idproduct=${produit[i].id}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Redirection</a></h1>
  </div>`
}
content.innerHTML = vue
document.getElementById('produit').appendChild(content);
})