// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
let mobileStatus = false;
window.mobileCheck = async function() {
	let check = false;
		(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
        if(check == false){
            mobileStatus = false
        }else{
            mobileStatus = true
        }
        return check;
	};
	
	let menuResize = (status) =>{
		if(status == true){
			document.getElementById('nav').style.display = "none"
			document.getElementById('buttonMenuBurger').style.display = "block"
		}else{
			document.getElementById('nav').style.display = "grid"
			document.getElementById('buttonMenuBurger').style.display = "none"
		}
	}
	
	window.addEventListener('resize', ()=>{
    window.mobileCheck()
	if(window.innerWidth < 639 || mobileStatus == true){
		menuResize(true)
	}else{
		menuResize(false)
	}
	document.getElementById('searchBarMobile').style.display = "none"
	});

	window.mobileCheck()
	if(window.innerWidth <= 639 || mobileStatus == true){
        menuResize(true)
	}

    let menuList =  document.querySelectorAll('#nav > div.space-x-2.flex.justify-center > a');
	for(let nomMenu of menuList){
		let id = nomMenu.id
		nomMenu.addEventListener('mouseover', (e) => {
            window.mobileCheck()
			if(mobileStatus == false && window.innerWidth > 639){
				document.getElementById('dropmenu').style.display="grid"
				gestionMenu(e.target.id)
			}
		})
	}

	
	function gestionMenu(target) {
		let menuChoose = [];
		switch (target) {
			case 'audio':
				menuChoose = menuArray[0];
				break;
			case 'eclairage':
					menuChoose = menuArray[1];
					break;
			case 'video':
					menuChoose = menuArray[2];
					break;
			case 'mobilier':
					menuChoose = menuArray[4];
					break;		
			case 'structure':
					menuChoose = menuArray[3];
					break;	
			case 'packs':
					menuChoose = menuArray[5];
					break;	
		}
		document.getElementById('dropmenu').innerHTML = "";
		if(menuChoose[0].length >= 3){
			let Content = ""
			for (let g = 0; g < 3; g++) {
				Content = "";
				for (let k = g; k < menuChoose[0].length; k=k+3) {
					Content += "<h2 class='ml-[20px] mt-[10px]'>"+menuChoose[0][k]+"</h2>"
					for (let h = 0; h < menuChoose[1][k].length; h++) {
						Content += "<a href='./boutique?sous-categorie="+menuChoose[1][k][h]+"'><p class='ml-[35px]'>"+menuChoose[1][k][h]+"</p></a>"
					}
				}
				var newDiv = document.createElement('div');
				newDiv.id = ""
				newDiv.classList.add('col-start-'+(2+(g*3)), 'col-span-3', 'drop-shadow-md', 'bg-white', 'mb-[5px]');
				newDiv.innerHTML = Content
				// ajoute le nœud texte au nouveau div créé
				document.getElementById('dropmenu').appendChild(newDiv);
			}
		}else if(menuChoose[0].length > 1){
			let Content = ""
			for (let g = 0; g < 2; g++) {
				Content = "";
				for (let k = g; k < menuChoose[0].length; k=k+3) {
					Content += "<h2 class='ml-[20px] mt-[10px]'>"+menuChoose[0][k]+"</h2>"
					for (let h = 0; h < menuChoose[1][k].length; h++) {
						Content += "<a href='./boutique?sous-categorie="+menuChoose[1][k][h]+"'><p class='ml-[35px]'>"+menuChoose[1][k][h]+"</p></a>"
					}
				}
				var newDiv = document.createElement('div');
				newDiv.id = ""
				newDiv.classList.add('col-start-'+(2+(g*5)), 'col-span-4', 'drop-shadow-md', 'bg-white', 'mb-[5px]');
				newDiv.innerHTML = Content
				// ajoute le nœud texte au nouveau div créé
				document.getElementById('dropmenu').appendChild(newDiv);
			}
		}else{
			let Content = ""
				Content = "";
				for (let k = 0; k < menuChoose[0].length; k=k+3) {
					Content += "<h2 class='ml-[20px] mt-[10px]'>"+menuChoose[0][k]+"</h2>"
					for (let h = 0; h < menuChoose[1][k].length; h++) {
						Content += "<a href='./boutique?sous-categorie="+menuChoose[1][k][h]+"'><p class='ml-[35px]'>"+menuChoose[1][k][h]+"</p></a>"
					}
				}
				var newDiv = document.createElement('div');
				newDiv.id = ""
				newDiv.classList.add('col-start-'+5, 'col-span-3', 'drop-shadow-md', 'bg-white', 'mb-[5px]');
				newDiv.innerHTML = Content
				// ajoute le nœud texte au nouveau div créé
				document.getElementById('dropmenu').appendChild(newDiv);
		}


  
  }

	document.getElementById('dropmenu').addEventListener('mouseleave', (e) => {
			document.getElementById('dropmenu').style.display="none"
		})
	document.querySelector('#nav').addEventListener('mouseleave', (e) => {
			document.getElementById('dropmenu').style.display="none"
		})
	document.querySelector('#nav').addEventListener('click', (e) => {
            window.mobileCheck()
			if(window.innerWidth > 639 && mobileStatus == false){
				document.getElementById('searchbar').style.display = "none"
				document.getElementById('search').style.display = "grid"
			}
		})
	document.getElementById('search').addEventListener('click', (e) => {
			// Stopper l'événement
			e.preventDefault();
			if(document.getElementById('searchBarMobile').style.display == "block"){
				document.getElementById('searchBarMobile').style.display = "none"
			}else{
                window.mobileCheck()
			if(window.innerWidth > 639 && mobileStatus == false){
				document.getElementById('searchbar').style.display = "block"
				document.getElementById('search').style.display = "none"
			}else{
				document.getElementById('searchBarMobile').style.display = "block"
			}}
		})
	document.getElementById('content').addEventListener('click', (e) => {
        window.mobileCheck()
			if(window.innerWidth > 639 && mobileStatus == false){
				document.getElementById('searchbar').style.display = "none"
				document.getElementById('search').style.display = "grid"
			}else{
				document.getElementById('searchBarMobile').style.display = "none"
			}
		})
	document.getElementById('buttonMenuBurger').addEventListener('click', (e) => {
			document.getElementById('searchBarMobile').style.display = "none"
			if(document.getElementById('nav').style.display == "none"){
				document.getElementById('nav').style.display = "block"
			}else{
				document.getElementById('nav').style.display = "none"
			}
		})

document.getElementById("buttonSearchBar").addEventListener('click', ()=>{
	location.replace("./boutique?search="+document.getElementById("valueSearchBar").value)
})
document.getElementById("valueSearchBar").addEventListener('keypress', (e)=>{
	if (e.key === 'Enter') {
		location.replace("./boutique?search="+document.getElementById("valueSearchBar").value)
	}
})


document.getElementById("buttonSearchBar1").addEventListener('click', ()=>{
	location.replace("./boutique?search="+document.getElementById("valueSearchBar1").value)
})
document.getElementById("valueSearchBar1").addEventListener('keypress', (e)=>{
	if (e.key === 'Enter') {
		location.replace("./boutique?search="+document.getElementById("valueSearchBar1").value)
	}
})