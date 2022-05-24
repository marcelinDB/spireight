var urlParams = new URLSearchParams(window.location.search);
let startRank = 20
let TVA
let checkbox = [document.getElementById('moinde10'), document.getElementById('10-40'), document.getElementById('40-100'), document.getElementById('100-200'), document.getElementById('200-plus')]
let min = [0, 10, 40, 100, 200]
let max = [10, 40, 100, 200, 201]

//gestion filtre status
if (urlParams.get('disponibilite') == "all") {
  document.getElementById("unvailable").checked = true
  document.getElementById("available").checked = true
} else if (urlParams.get('disponibilite') == "rupture") {
  document.getElementById("unvailable").checked = true
} else if (urlParams.get('disponibilite') == "disponible") {
  document.getElementById("available").checked = true
}

if (urlParams.get('search') == undefined) {
  document.getElementById('resetSearch').style.display = "none"
} else {
  document.getElementById('resetSearch').addEventListener('click', () => {
    urlParams.delete('search')
    document.getElementById('resetSearch').style.display = "none"
    start();
  })
}

if (urlParams.get('promotion') == "true") {
  document.getElementById("promotion").checked = true
}

if (urlParams.get('achat') == "true") {
  document.getElementById("achat").checked = true
}

if (urlParams.get('location') == "true") {
  document.getElementById("location").checked = true
}

if (urlParams.get('occassion') == "true") {
  document.getElementById("occassion").checked = true
}

document.querySelectorAll('#categorie input').forEach(element => {
  element.addEventListener('click', () => {
    if (urlParams.get('categorie') != undefined) {
      let categorieList = urlParams.get('categorie').split(',')
      let newUrl = urlParams.get('categorie') + "," + element.value
      if (element.checked == true) {
        urlParams.set('categorie', newUrl);
      } else {
        if (categorieList.length > 1) {
          let index = categorieList.indexOf(element.value);
          if (index > -1) {
            categorieList.splice(index, 1); // 2nd parameter means remove one item only
            let finalValue = ""
            for (let i = 0; i < categorieList.length; i++) {
              if (i == 0) {
                finalValue = categorieList[0]
              } else {
                finalValue = "," + categorieList[i]
              }
            }
            urlParams.set('categorie', finalValue);
          }
        } else {
          urlParams.delete('categorie')
        }
      }
    } else {
      urlParams.append('categorie', element.value);
    }
    start();
  })
  if (urlParams.get('categorie') != undefined) {
    let categorieList = urlParams.get('categorie').split(',')
    categorieList.forEach(element2 => {
      if (element2 == element.value) {
        element.checked = true
      }
    })
      ;
  }
});

document.querySelectorAll('#sous-categorie input').forEach(element => {
  element.addEventListener('click', () => {
    if (urlParams.get('sous-categorie') != undefined) {
      let categorieList = urlParams.get('sous-categorie').split(',')
      let newUrl = urlParams.get('sous-categorie') + "," + element.value
      if (element.checked == true) {
        urlParams.set('sous-categorie', newUrl);
      } else {
        if (categorieList.length > 1) {
          let index = categorieList.indexOf(element.value);
          if (index > -1) {
            categorieList.splice(index, 1); // 2nd parameter means remove one item only
            let finalValue = ""
            for (let i = 0; i < categorieList.length; i++) {
              if (i == 0) {
                finalValue = categorieList[0]
              } else {
                finalValue = "," + categorieList[i]
              }
            }
            urlParams.set('sous-categorie', finalValue);
          }
        } else {
          urlParams.delete('sous-categorie')
        }
      }
    } else {
      urlParams.append('sous-categorie', element.value);
    }
    start();
  })
  if (urlParams.get('sous-categorie') != undefined) {
    let categorieList = urlParams.get('sous-categorie').split(',')
    categorieList.forEach(element2 => {
      if (element2 == element.value) {
        element.checked = true
      }
    })
      ;
  }
});

function selectionCheckbox() {
  if (urlParams.get('limit') != undefined) {
    let numGold = urlParams.get('limit').split('-');
    for (let i = 0; i < min.length; i++) {
      if (min[i] >= numGold[0] && max[i] <= numGold[1]) {
        checkbox[i].checked = true
      }
    }
  }
}
selectionCheckbox();

for (let i = 0; i < checkbox.length; i++) {

  checkbox[i].addEventListener('click', () => {

    if (urlParams.get('limit') != undefined) {
      if (checkbox[i].checked == true) {
        let limit = urlParams.get('limit').split('-');
        let minNumb = 0
        let maxNumb = 0
        if (limit[0] > min[i]) {
          minNumb = min[i]
        } else {
          minNumb = limit[0]
        }
        if (limit[1] >= max[i]) {
          maxNumb = limit[1]
        } else {
          maxNumb = max[i]
        }
        urlParams.set('limit', `${minNumb}-${maxNumb}`);
      } else {
        let limit = [0, 0]
        let minNumb = 2010
        let maxNumb = 0
        let all = 0
        for (let y = 0; y < checkbox.length; y++) {
          if (checkbox[y].checked == true) {
            if (minNumb > min[y]) {
              minNumb = min[y]
            }
            if (limit[1] >= max[y]) {
              maxNumb = limit[1]
            } else {
              maxNumb = max[y]
            }
            urlParams.set('limit', `${minNumb}-${maxNumb}`);
          } else {
            all = all + 1
          }
        }
        if (all == checkbox.length) {
          urlParams.delete('limit')
        }
      }


    } else {
      urlParams.append('limit', `${min[i]}-${max[i]}`);
    }
    start();
  })

}

document.getElementById('voirPlus').addEventListener('click', () => {
  startRank = startRank + 20
  start();
})

document.getElementById('achat').addEventListener('click', () => {
  if (document.getElementById("achat").checked == true) {
    urlParams.append('achat', 'true');
  } else {
    urlParams.delete('achat')
  }
  start();
})

document.getElementById('location').addEventListener('click', () => {
  if (document.getElementById("location").checked == true) {
    urlParams.append('location', 'true');
  } else {
    urlParams.delete('location')
  }
  start();
})

document.getElementById('occassion').addEventListener('click', () => {
  if (document.getElementById("occassion").checked == true) {
    urlParams.append('occassion', 'true');
  } else {
    urlParams.delete('occassion')
  }
  start();
})

document.getElementById('pertinence').addEventListener('click', () => {
  if (urlParams.get('orderby') != undefined) {
    urlParams.set('orderby', 'pertinence');
  } else {
    urlParams.append('orderby', 'pertinence');
  }
  start();
})
document.getElementById('prixcroissant').addEventListener('click', () => {
  if (urlParams.get('orderby') != undefined) {
    urlParams.set('orderby', 'asc');
  } else {
    urlParams.append('orderby', 'asc');
  }
  start();
})
document.getElementById('prixdecroissant').addEventListener('click', () => {
  if (urlParams.get('orderby') != undefined) {
    urlParams.set('orderby', 'desc');
  } else {
    urlParams.append('orderby', 'desc');
  }
  start();
})
document.getElementById('unvailable').addEventListener('click', () => {
  if (document.querySelector("#unvailable").checked == true) {
    if (urlParams.get('disponibilite') != undefined && urlParams.get('disponibilite') == "disponible") {
      urlParams.set('disponibilite', 'all');
    } else {
      urlParams.append('disponibilite', 'rupture');
    }
  } else {
    if (urlParams.get('disponibilite') != undefined && urlParams.get('disponibilite') == "all") {
      urlParams.set('disponibilite', 'disponible');
    } else {
      urlParams.delete('disponibilite')
    }
  }
  start();
})
document.getElementById('available').addEventListener('click', () => {
  if (document.querySelector("#available").checked == true) {
    if (urlParams.get('disponibilite') != undefined && urlParams.get('disponibilite') == "rupture") {
      urlParams.set('disponibilite', 'all');
    } else {
      urlParams.append('disponibilite', 'disponible');
    }
  } else {
    if (urlParams.get('disponibilite') != undefined && urlParams.get('disponibilite') == "all") {
      urlParams.set('disponibilite', 'rupture');
    } else {
      urlParams.delete('disponibilite')
    }
  }
  start();
})
document.getElementById('promotion').addEventListener('click', () => {
  if (document.getElementById("promotion").checked == true) {
    urlParams.append('promotion', 'true');
  } else {
    urlParams.delete('promotion')
  }
  start();
})
//*
//*       __   __        _____
//* |\   |    |  |  |  |   |
//* | |  |-   |--|  |  |   |
//* |/   |__  |__|  |__|   |
//*
//*
async function start() {
  //Mise à niveau des elements avant un nouveau trie
  let boutiqueFiltre = []
  let urlValue = ""
  urlParams.forEach(function (val, key) {
    if (urlValue == "") {
      urlValue += "?" + key + "=" + val
    } else {
      urlValue += "&" + key + "=" + val
    }
  });
  window.history.pushState('', 'Boutique', location.protocol + '//' + location.host + location.pathname + urlValue);
  document.getElementById('listProduct').innerHTML = "";

  //Filtrage
  if (urlParams.get('disponibilite') != undefined || urlParams.get('promotion') != undefined || urlParams.get('limit') != undefined || urlParams.get('achat') != undefined || urlParams.get('location') != undefined || urlParams.get('occassion') != undefined || urlParams.get('categorie') != undefined || urlParams.get('sous-categorie') != undefined || urlParams.get('search') != undefined) {
    switch (urlParams.get('disponibilite')) {
      case "all":
        boutiqueFiltre = await getSearchResult(await getSubCategorieOnly(await getCategorieOnly(await getAquisitionOnly(await getProductByPrice(await getProductByPrice(await getPromotionOnly(await boutique)))))))
        break;
      case "disponible":
        boutiqueFiltre = await getSearchResult(await getSubCategorieOnly(await getCategorieOnly(await getAquisitionOnly(await getProductByPrice(await getPromotionOnly(await getRessourceByAvailableStatus(true)))))))
        break;
      case "rupture":
        boutiqueFiltre = await getSearchResult(await getSubCategorieOnly(await getCategorieOnly(await getAquisitionOnly(await getProductByPrice(await getPromotionOnly(await getRessourceByAvailableStatus(false)))))))
        break;
      case null:
        boutiqueFiltre = await getSearchResult(await getSubCategorieOnly(await getCategorieOnly(await getAquisitionOnly(await getProductByPrice(await getPromotionOnly(await boutique))))))
        break;
    }

    if (urlParams.get('orderby') == undefined || urlParams.get('orderby') == "pertinence") {
      boutiqueFiltre.sort(function (a, b) {
        return a[1].localeCompare(b[1])
      })
      document.getElementById('pertinence').classList.add("active");
      document.getElementById('prixcroissant').classList.remove("active");
      document.getElementById('prixdecroissant').classList.remove("active");
      listProduct(boutiqueFiltre);
    } else {
      if (urlParams.get('orderby') == "asc") {
        boutiqueFiltre.sort((a, b) => (a[8][0] - b[8][0] || a[8][2] - b[8][2]));
        document.getElementById('prixcroissant').classList.add("active");
        document.getElementById('pertinence').classList.remove("active");
        document.getElementById('prixdecroissant').classList.remove("active");
      } else {
        boutiqueFiltre.sort((a, b) => (b[8][0] - a[8][0] || b[8][2] - a[8][2]));
        document.getElementById('prixdecroissant').classList.add("active");
        document.getElementById('prixcroissant').classList.remove("active");
        document.getElementById('pertinence').classList.remove("active");
      }
      listProduct(boutiqueFiltre);
    }
  } else {
    if (urlParams.get('orderby') == undefined || urlParams.get('orderby') == "pertinence") {
      boutique.sort(function (a, b) {
        return a[1].localeCompare(b[1])
      })
      document.getElementById('pertinence').classList.add("active");
      document.getElementById('prixcroissant').classList.remove("active");
      document.getElementById('prixdecroissant').classList.remove("active");
      listProduct(boutique);
    } else {
      if (urlParams.get('orderby') == "asc") {
        boutique.sort((a, b) => (a[8][0] - b[8][0] || a[8][2] - b[8][2]));
        document.getElementById('prixcroissant').classList.add("active");
        document.getElementById('pertinence').classList.remove("active");
        document.getElementById('prixdecroissant').classList.remove("active");
      } else {
        boutique.sort((a, b) => (b[8][0] - a[8][0] || b[8][2] - a[8][2]));
        document.getElementById('prixdecroissant').classList.add("active");
        document.getElementById('prixcroissant').classList.remove("active");
        document.getElementById('pertinence').classList.remove("active");
      }
      listProduct(boutique);
    }
  }
}

async function getPromotionOnly(boutiqueBefore) {
  let response = []
  if (urlParams.get('promotion') == 'true') {
    boutiqueBefore.forEach(element => {
      if (1 < element[6].length) {
        response.push(element);
      }
    })
  } else {
    response = boutiqueBefore;
  }
  return await response
}

async function getCategorieOnly(boutiqueBefore) {
  let response = []
  if (urlParams.get('categorie') != undefined) {
    let categorieList = urlParams.get('categorie').split(',');
    boutiqueBefore.forEach(element => {
      categorieList.forEach(element2 => {
        if (element[7] == element2) {
          response.push(element);
        }
      });
    })
  } else {
    response = boutiqueBefore;
  }
  return await response
}

async function getSubCategorieOnly(boutiqueBefore) {
  let response = []
  if (urlParams.get('sous-categorie') != undefined) {
    let categorieList = urlParams.get('sous-categorie').split(',');
    boutiqueBefore.forEach(element => {
      categorieList.forEach(element2 => {
        if (element[9] == element2) {
          response.push(element);
        }
      });
    })
  } else {
    response = boutiqueBefore;
  }
  return await response
}

async function getAquisitionOnly(boutiqueBefore) {
  let response = []
  if (urlParams.get('achat') == 'true' || urlParams.get('location') == 'true' || urlParams.get('occassion') == 'true') {
    boutiqueBefore.forEach(element => {
      if (urlParams.get('achat') == 'true' && urlParams.get('location') == 'true' && urlParams.get('occassion') == 'true') {
        if (element[8][0] != 0 && element[8][1] != 0 && element[8][2] != 0) {
          response.push(element);
        }
      } else if (urlParams.get('achat') == 'true' && urlParams.get('location') == 'true') {
        if (element[8][0] != 0 && element[8][2] != 0) {
          response.push(element);
        }
      } else if (urlParams.get('occassion') == 'true' && urlParams.get('location') == 'true') {
        if (element[8][1] != 0 && element[8][2] != 0) {
          response.push(element);
        }
      } else if (urlParams.get('achat') == 'true' && urlParams.get('occassion') == 'true') {
        if (element[8][0] != 0 && element[8][1] != 0) {
          response.push(element);
        }
      } else if (urlParams.get('achat') == 'true') {
        if (element[8][0] != 0) {
          response.push(element);
        }
      } else if (urlParams.get('location') == 'true') {
        if (element[8][2] != 0) {
          response.push(element);
        }
      } else if (urlParams.get('occassion') == 'true') {
        if (element[8][1] != 0) {
          response.push(element);
        }
      }
    })
  } else {
    response = boutiqueBefore;
  }
  return await response
}

async function getProductByPrice(boutiqueBefore) {
  let response = []
  if (urlParams.get('limit') != undefined) {
    let limit = urlParams.get('limit').split('-')
    limit[0] = limit[0] * 1
    limit[1] = limit[1] * 1
    boutiqueBefore.forEach(element => {
      if ((limit[0] < element[8][0] && limit[1] >= element[8][0]) || (limit[0] < element[8][1] && limit[1] >= element[8][1]) || (limit[0] < element[8][2] && limit[1] >= element[8][2]) || (limit[1] == 201 && limit[0] < element[8][0]) || (limit[1] == 201 && limit[0] < element[8][1]) || (limit[1] == 201 && limit[0] < element[8][2])) {
        response.push(element);
      }
    })
  } else {
    response = boutiqueBefore;
  }
  return await response
}

async function getSearchResult(boutiqueBefore) {
  let response = []
  if (urlParams.get('search') != undefined) {
    boutiqueBefore.forEach(element => {
      if (element[1].toLowerCase().includes(urlParams.get('search').toLowerCase())) {
        response.push(element);
      }
    })
  } else {
    response = boutiqueBefore;
  }
  return await response
}

async function getRessourceByAvailableStatus(status) {
  let response = []
  if (status == true) {
    boutique.forEach(element => {
      if (0 < element[5]) {
        response.push(element);
      }
    });
  } else {
    boutique.forEach(element => {
      if (0 == element[5]) {
        response.push(element);
      }
    });
  }
  return await response
}

async function listProduct(table) {
  let loopNum = startRank
  let loopstart = 0
  if (startRank > table.length) {
    loopNum = table.length
  } else {
    if (table.length < 20) {
      loopstart = 0
    } else {
      loopstart = startRank - 20
    }
  }
  for (let i = await startRank - 20; i < await table.length; i++) {
    let promoHtml = ""
    let nouveauHtml = ""
    let ruptureHtml = ""
    if (i == table.length - 1) {
      document.getElementById('voirPlus').style.display = "none"
    }

    if (await table[i][5] == 0) {
      ruptureHtml = `<button class="justify-self-start p-2 rounded-full bg-red-400 text-white -mb-4 hover:bg-blue-500 focus:outline-none focus:bg-blue-500"><h3>Rupture</h3></button>`
    }
    if (await table[i][6].length > 0 && await ruptureHtml == "") {
      promoHtml = `<button class="justify-self-start p-2 rounded-full bg-red-400 text-white -mb-4 hover:bg-blue-500 focus:outline-none focus:bg-blue-500"><h3>Promo</h3></button>`
    }
    if (((new Date().getTime() - new Date(table[i][4]).getTime()) / (1000 * 3600 * 24)) < 32) {
      nouveauHtml = `<button class="justify-self-start p-2 rounded-full bg-red-400 text-white -mb-4 hover:bg-blue-500 focus:outline-none focus:bg-blue-500"><h3>Nouveau</h3></button>`
    }

    let displayPrice = ""
    if (await table[i][6][1] != undefined) {
      if (await table[i][8][2] != 0) {
        displayPrice = await `<span class="text-gray-500 mt-2">Location:
              <span class="line-through text-red-400">${await table[i][8][2].toFixed(2)}</span>
              <span class="text-green-600">${(await table[i][8][2] * (await table[i][6][1] / 100)).toFixed(2)}</span>
              €</span><br>`
      } else {
        displayPrice += await `<span class="text-gray-500 mt-2"></span><br>`
      }
      if (table[i][8][0] != 0 || table[i][8][1] != 0) {
        let switchPrice
        if (table[i][8][0] != 0) {
          switchPrice = table[i][8][0]
        } else {
          switchPrice = table[i][8][1]
        }
        displayPrice += await `<span class="text-gray-500 mt-2">Achat:
              <span class="line-through text-red-400">${await switchPrice.toFixed(2)}</span>
              <span class="text-green-600">${(await switchPrice * (await table[i][6][1] / 100)).toFixed(2)}</span>
              €</span>`
      } else {
        displayPrice += await `<span class="text-gray-500 mt-2"></span><br>`
      }
    } else {
      if (table[i][8][2] != 0) {
        displayPrice = await `<span class="text-gray-500 mt-2">Location:
              <span class="text-green-600">${await table[i][8][2].toFixed(2)}</span>
              €</span><br>`
      } else {
        displayPrice += await `<span class="text-gray-500 mt-2"></span><br>`
      }
      if (await table[i][8][0] != 0 || table[i][8][1] != 0) {
        let switchPrice
        if (table[i][8][0] != 0) {
          switchPrice = table[i][8][0]
        } else {
          switchPrice = table[i][8][1]
        }
        displayPrice += await `<span class="text-gray-500 mt-2">Achat:
              <span class="text-green-600">${await switchPrice.toFixed(2)}</span>
              €</span>`
      } else {
        displayPrice += await `<span class="text-gray-500 mt-2"></span><br>`
      }
    }
    addElement(await table[i][0], await table[i][1], await table[i][2], await promoHtml, await ruptureHtml, await displayPrice, await nouveauHtml);
  }
}

async function addElement(id, nom, img, promoHtml, ruptureHtml, displayPriceFinal, nouveauHtml) {
  content = document.createElement("a")
  content.innerHTML = `<a href="./article?id=${id}">
        <div class="w-full max-w-sm mx-auto shadow-md overflow-hidden">
          <div class="flex items-end justify-end h-56 w-full bg-cover" style="background-image: url(${img})">
            <div class="inline-flex justify-self-stretch">
              ${ruptureHtml}
              ${nouveauHtml}
              ${promoHtml}
              <button class="justify-self-end p-2 rounded-full bg-blue-600 text-white mx-5 -mb-4 hover:bg-blue-500 focus:outline-none focus:bg-blue-500">
                <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" stroke="currentColor">
                  <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </button>
            </div>
          </div>
          <div class="px-5 py-3">
            <h3 class="text-gray-700 uppercase">${nom}</h3>
                ${displayPriceFinal}
          </div>
        </div>
      </a>`
  document.getElementById('listProduct').appendChild(content);
}

async function addElementFiltrer(id, nom, img, promoHtml, ruptureHtml, displayPriceFinal, nouveauHtml) {
  content = document.createElement("a")
  content.innerHTML = `<a href="./article?id=${id}">
        <div class="w-full max-w-sm mx-auto shadow-md overflow-hidden">
          <div class="flex items-end justify-end h-56 w-full bg-cover" style="background-image: url(${img})">
            <div class="inline-flex justify-self-stretch">
              ${ruptureHtml}
              ${nouveauHtml}
              ${promoHtml}
              <button class="justify-self-end p-2 rounded-full bg-blue-600 text-white mx-5 -mb-4 hover:bg-blue-500 focus:outline-none focus:bg-blue-500">
                <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" stroke="currentColor">
                  <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </button>
            </div>
          </div>
          <div class="px-5 py-3">
            <h3 class="text-gray-700 uppercase">${nom}</h3>
                ${displayPriceFinal}
          </div>
        </div>
      </a>`
  document.getElementById('listProduct').appendChild(content);
}
start();