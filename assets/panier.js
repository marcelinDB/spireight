var tva = 1.20
var cost = 0

console.log(panier)
if(panier.length == 0){
    content = document.createElement("div")
    content.classList.add("grid", "gap-4", "grid-cols-11", "sm:grid-cols-11", "lg:grid-cols-11", "xl:grid-cols-11");
    content.innerHTML = `
    <a href="" class="col-start-1 col-end-3 w-full m-auto h-[209px]"><div class="col-start-1 col-end-3 w-full m-auto h-[209px] bg-center bg-cover " style="background-image:url(''); background-size: cover; ">
    </div></a>
    <div class="col-start-3 col-end-6 w-full m-auto">
        <h1 class=""></h1>
    </div>
    <div class="col-start-6 col-end-8 w-full m-auto">
        <div class="relative inline-block w-full text-gray-700">
            <h1 id="jour" class="inline-block">Aucun article</h1>
        </div>
    </div>
    <div class="col-start-8 col-end-9 w-full m-auto">
    </div>
    <div class="col-start-9 col-end-10 w-full m-auto">
        <h1 id="quantite" class="inline-block"></h1>
        <div class="w-10 h-10 inline-block align-middle mt-[-10px]">
            
        </div>
    </div>
    <div class="col-start-10 col-end-11 w-full m-auto">
        <h1 id="jour" class="inline-block"></h1>
        <div class="w-10 h-10 inline-block align-middle mt-[-10px]">
           
        </div>
    </div>
    <div class="col-start-11 col-end-12 w-full m-auto">
        <h1 class=""></h1>
    </div>
</div>`
    cost = cost + 0
    document.getElementById('product').appendChild(content);
}
panier.forEach(component => {
    var nomtype = component['nomType']
    var selector = ""
    var type
    content = document.createElement("div")
    for (let i = 0; i < component[0].length; i++) {
        if(component[0][i][0] == nomtype && component[0][i][3] != 0){
            selector =  "<option selected>"+component[0][i][0]+"</option>"+selector
            type = component[0][i][2]
        }else if(component[0][i][3] != 0){
            selector =  selector+"<option>"+component[0][i][0]+"</option>"
        }
    }
    content.classList.add("grid", "gap-4", "grid-cols-11", "sm:grid-cols-11", "lg:grid-cols-11", "xl:grid-cols-11");
    content.innerHTML = `
    <a href="./article?id=`+component['idprod']+`" class="col-start-1 col-end-3 w-full m-auto h-[209px]"><div class="col-start-1 col-end-3 w-full m-auto h-[209px] bg-center bg-cover " style="background-image:url('`+component['img']+`'); background-size: cover; ">
    </div></a>
    <div class="col-start-3 col-end-6 w-full m-auto flex justify-center items-center">
        <input class="inline-block hidden" name="id[]" value="`+component['Primary']+`" >
        <input class="inline-block hidden" name="stock[]" value="`+component['id']+`" >
        <h1 class="inline-block">`+component['nom']+`</h1>
        <button class="inline-block w-[40px] h-[40px]" type="submit" name="trash" value="`+component['Primary']+`">
            <i class="fa fa-trash-o text-red-500 hover:text-red-700 text-2xl" aria-hidden="true"></i>
        </button>
    </div>
    <div class="col-start-6 col-end-8 w-full m-auto">
        <div class="relative inline-block w-full text-gray-700">
                    <select id="selecter" class="w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline" placeholder="Regular input">
                        `+selector+`
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
                    </div>
        </div>
    </div>
    <div class="col-start-8 col-end-9 w-full m-auto">
        <input class="appearance-none block w-[80px] border-none border select-none border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 text-white" id="date" name="date[]" type="date" placeholder="">
    </div>
    <div class="col-start-9 col-end-10 w-full m-auto">
        <input class="inline-block hidden" id="quantityInput" name="quantity[]" value="`+component['userQuantity']+`" >
        <input class="inline-block hidden" id="type" name="type[]" value="`+type+`" >
        <h1 class="inline-block" id="quantity">`+component['userQuantity']+`</h1>
        <div class="w-10 h-10 inline-block align-middle mt-[-10px]">
            <a href="" id="upQuantity" class="rotate-180 flex items-center px-2 mx-auto">
                <svg class="fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
            </a>
            <a href="" id="downQuantity" class="flex items-center px-2 mx-auto">
                <svg class="fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
            </a>
        </div>
    </div>
    <div class="col-start-10 col-end-11 w-full m-auto">
        <input class="inline-block hidden" id="day" name="jour[]" value="`+component['jour']+`" >
        <h1 class="inline-block" id="days">`+component['jour']+`</h1>
        <div class="w-10 h-10 inline-block align-middle mt-[-10px]">
            <a href="" id="upday" class="rotate-180 flex items-center px-2 mx-auto">
                <svg class="fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
            </a>
            <a href="" id="downday" class="flex items-center px-2 mx-auto">
                <svg class="fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
            </a>
        </div>
    </div>
    <div class="col-start-11 col-end-12 w-full m-auto">
        <h1 class="" id="priceRef">`+component['prix']+` €</h1>
    </div>
</div>`
    cost = cost + (component['prix'] * component['userQuantity'])
    document.getElementById('product').appendChild(content);
    content2 = document.createElement("div")
    content2.classList.add('grid', 'gap-4', 'grid-cols-2', 'sm:grid-cols-2', 'lg:grid-cols-2', 'xl:grid-cols-2', 'mt-6', 'absolute', 'inset-x-0', 'mt-[10px]');
    content2.innerHTML = `<div class="col-start-1 col-end-2 w-full m-auto">
    <h1 class="text-middle" id='priceDetail'>`+component['prix']+` *`+component['userQuantity']+` *1</h1>
</div>
<div class="col-start-2 col-end-3 w-full m-auto">
    <h1 class="" id="priceDetailTot">`+(component['prix']*component['userQuantity'])+` €</h1>
</div>`
document.getElementById('recap').appendChild(content2);
});

document.getElementById('total').innerText = (tva*cost)+" €"
document.getElementById('totalHt').innerText = (cost)+" €"

var buttonQuantity = document.querySelectorAll('a#upQuantity')
var buttonDownQuantity = document.querySelectorAll('a#downQuantity')
var buttonDay = document.querySelectorAll('a#upday')
var buttonDownDay = document.querySelectorAll('a#downday')

for (let y = 0; y < buttonQuantity.length; y++) {
    buttonQuantity[y].addEventListener('click',function(e){
        e.preventDefault();
        if(panier[y]['quantite'] > document.querySelectorAll('#quantity')[y].innerText*1){
            document.querySelectorAll('#quantity')[y].innerText = document.querySelectorAll('#quantity')[y].innerText*1 +1
            document.querySelectorAll('#quantityInput')[y].setAttribute('value', document.querySelectorAll('#quantity')[y].innerText*1) 
            document.querySelectorAll('#priceDetail')[y].innerText = document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')+"*"+document.querySelectorAll('#quantity')[y].innerText+"*"+document.querySelectorAll('#days')[y].innerText
            document.querySelectorAll('#priceDetailTot')[y].innerText = (document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')*document.querySelectorAll('#quantity')[y].innerText*document.querySelectorAll('#days')[y].innerText)+' €'
            if(document.querySelectorAll('#selecter')[y].value != "Location"){
                document.querySelectorAll('#priceDetail')[y].innerText = document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')+"*"+document.querySelectorAll('#quantity')[y].innerText
                document.querySelectorAll('#priceDetailTot')[y].innerText = (document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')*document.querySelectorAll('#quantity')[y].innerText+' €')
                document.querySelectorAll('#days')[y].innerText = 0
            }
            totalCost()
        }
    })
}

for (let y = 0; y < buttonDownQuantity.length; y++) {
    buttonDownQuantity[y].addEventListener('click',function(e){
        e.preventDefault();
        if(document.querySelectorAll('#quantity')[y].innerText*1 > 0){
            document.querySelectorAll('#quantity')[y].innerText = document.querySelectorAll('#quantity')[y].innerText*1 - 1
            document.querySelectorAll('#quantityInput')[y].setAttribute('value', document.querySelectorAll('#quantity')[y].innerText*1) 
            document.querySelectorAll('#priceDetail')[y].innerText = document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')+"*"+document.querySelectorAll('#quantity')[y].innerText+"*"+document.querySelectorAll('#days')[y].innerText
            document.querySelectorAll('#priceDetailTot')[y].innerText = (document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')*document.querySelectorAll('#quantity')[y].innerText*document.querySelectorAll('#days')[y].innerText)+' €'
            if(document.querySelectorAll('#selecter')[y].value != "Location"){
                document.querySelectorAll('#priceDetail')[y].innerText = document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')+"*"+document.querySelectorAll('#quantity')[y].innerText
                document.querySelectorAll('#priceDetailTot')[y].innerText = (document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')*document.querySelectorAll('#quantity')[y].innerText+' €')
                document.querySelectorAll('#days')[y].innerText = 0
            }
            totalCost()
        }

    })
}

for (let y = 0; y < buttonDay.length; y++) {
    buttonDay[y].addEventListener('click',function(e){
        e.preventDefault();
        if(document.querySelectorAll('#selecter')[y].value == "Location" && document.querySelectorAll('#days')[y].innerText*1 >= 0){
            document.querySelectorAll('#days')[y].innerText = document.querySelectorAll('#days')[y].innerText*1 +1
            document.querySelectorAll('#priceDetail')[y].innerText = document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')+"*"+document.querySelectorAll('#quantity')[y].innerText+"*"+document.querySelectorAll('#days')[y].innerText
            document.querySelectorAll('#priceDetailTot')[y].innerText = (document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')*document.querySelectorAll('#quantity')[y].innerText*document.querySelectorAll('#days')[y].innerText)+' €'
            document.querySelectorAll('#day')[y].setAttribute('value', document.querySelectorAll('#days')[y].innerText*1) 
            totalCost()
        }else{
            document.querySelectorAll('#priceDetail')[y].innerText = document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')+"*"+document.querySelectorAll('#quantity')[y].innerText
            document.querySelectorAll('#priceDetailTot')[y].innerText = (document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')*document.querySelectorAll('#quantity')[y].innerText+' €')
            document.querySelectorAll('#days')[y].innerText = 0
            totalCost()
        }
    })
}

for (let y = 0; y < buttonDownDay.length; y++) {
    buttonDownDay[y].addEventListener('click',function(e){
        e.preventDefault();
        if(document.querySelectorAll('#selecter')[y].value == "Location" && document.querySelectorAll('#days')[y].innerText*1 >= 0 && document.querySelectorAll('#days')[y].innerText*1-1 != -1){
            document.querySelectorAll('#days')[y].innerText = document.querySelectorAll('#days')[y].innerText*1 -1
            document.querySelectorAll('#priceDetail')[y].innerText = document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')+"*"+document.querySelectorAll('#quantity')[y].innerText+"*"+document.querySelectorAll('#days')[y].innerText
            document.querySelectorAll('#priceDetailTot')[y].innerText = (document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')*document.querySelectorAll('#quantity')[y].innerText*document.querySelectorAll('#days')[y].innerText)+' €'
            document.querySelectorAll('#day')[y].setAttribute('value', document.querySelectorAll('#days')[y].innerText*1)
            totalCost()
        }else{
            document.querySelectorAll('#priceDetail')[y].innerText = document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')+"*"+document.querySelectorAll('#quantity')[y].innerText
            document.querySelectorAll('#priceDetailTot')[y].innerText = (document.querySelectorAll('#priceRef')[y].innerText.replace('€', '')*document.querySelectorAll('#quantity')[y].innerText+' €')
            document.querySelectorAll('#days')[y].innerText = 0
            totalCost()
        }
    })
}

function totalCost(){
    var costSum = 0
    for (let i = 0; i < document.querySelectorAll('#priceRef').length; i++) {
        costSum = costSum + (document.querySelectorAll('#priceDetailTot')[i].innerText.replace('€', '') * 1)
    }
    document.getElementById('total').innerText = (tva*costSum)+" €"
    document.getElementById('totalHt').innerText = (costSum)+" €"
}

document.querySelectorAll('#selecter').forEach(element => {
    element.w
});

for (let z = 0; z < document.querySelectorAll('#selecter').length; z++) {
    document.querySelectorAll('#selecter')[z].addEventListener('change',function(e){
        e.preventDefault();
        for (let k = 0; k < panier[z][0].length; k++) {
            if(document.querySelectorAll('#selecter')[z].value == panier[z][0][k][0]){
                document.querySelectorAll('#priceRef')[z].innerHTML = panier[z][0][k][1]+" €"
                document.querySelectorAll('#type')[z].setAttribute('value', panier[z][0][k][2]*1) 
                if(panier[z][0][k][0] != "Location"){
                    document.querySelectorAll('#priceDetail')[z].innerText = document.querySelectorAll('#priceRef')[z].innerText.replace('€', '')+"*"+document.querySelectorAll('#quantity')[z].innerText
                    document.querySelectorAll('#priceDetailTot')[z].innerText = (document.querySelectorAll('#priceRef')[z].innerText.replace('€', '')*document.querySelectorAll('#quantity')[z].innerText+' €')
                    document.querySelectorAll('#days')[z].innerText = 0
                }
                totalCost()
            }
        }
    })
}

window.addEventListener('resize', ()=>{
    if(window.innerWidth <= 1280){
        document.getElementById('estimation').style.width = document.querySelector("main").clientWidth+"px";
    }else{
        document.getElementById('estimation').style.width = "100%";
    }
})

if(window.innerWidth <= 1280){
    document.getElementById('estimation').style.width = document.querySelector("main").clientWidth+"px";
}else{
    document.getElementById('estimation').style.width = "100%";
}

