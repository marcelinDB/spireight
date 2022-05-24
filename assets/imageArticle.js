// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
let buttonImage = document.querySelectorAll('#content > main > div > div.w-full.mx-auto.overflow-hidden > div img')

for(let i = 0; i < buttonImage.length; i++){
	buttonImage[i].addEventListener('click', (e) => {
		document.getElementById('principal').src = buttonImage[i].src;
	})
}
