<script>
		function campo1Change(who) {
			change(who, true);

			if (who.options[who.selectedIndex].text == 'Elogio' || who.options[who.selectedIndex].text == 'Sugestão'){
			    document.getElementById('sendmail').style.display = 'none';
			    document.getElementById('hidemail').style.display = 'none';
			    document.getElementById('mensagem').style.display = 'none';
			}
		}

		function change(name, isCampo) {
			if(!isCampo) {
				if(name.value == 0)
					document.getElementById('sendmail').style.display = 'none';
				else
					document.getElementById('sendmail').style.display = 'block';
			}

			
			document.getElementById('hid').innerHTML = name.options[name.selectedIndex].text;
			var i;
			for (i = 0; i < document.getElementsByClassName('coll').length; i++) {
				document.getElementsByClassName('coll')[i].setAttribute('aria-expanded', 'false');
				document.getElementsByClassName('coll')[i].classList.remove('in');
			}

			var divs = document.getElementsByClassName('tagged');
			var titles = document.getElementsByClassName('title');

			for (i = 0; i < document.getElementsByClassName('tagged').length; i++) {
				divs[i].style.display = 'none';
				titles[i].style.display = 'none';

				document.getElementById('hhelp'+i).style.display = 'none';
				document.getElementById('thelp'+i).style.display = 'none';

			} 
			getOutput(name.options[name.selectedIndex].text);
			//document.getElementById('thelp'+name.value).innerHTML = name.options[name.selectedIndex].text;
			return name;
		}

        function getOutput(id) {
			getRequest(
			'sae_ajax.php',	// URL do PHP
			drawOutput,		// funcao de sucesso 
			drawError,		// funcao de erro (catch)
			id				// parametro
			);
			return false;
		}

		function drawError() {
			console.log('Error.');
		}

		function drawOutput(response) {
			var js = JSON.parse(response);

			var i;
			var title = document.getElementsByClassName('title');
			var body = document.getElementsByClassName('tagged');

			for (i = 0; i < js.length; i++) {
				body[i].style.display = '';
				body[i].innerHTML = js[i].description;
				
				title[i].style.display = '';
				title[i].innerHTML = js[i].title;

				document.getElementById('hhelp'+i).style.display = 'block';
				document.getElementById('thelp'+i).style.display = 'block';

			} 
		}

		function getRequest(url, success, error, id) {
			var req = false;
			try{
				// browsers normais
				req = new XMLHttpRequest();
			} catch (e){
				// IE
				try {
					req = new ActiveXObject('Msxml2.XMLHTTP');
				} catch(e) {
				// IE mais antigo
					try{
						req = new ActiveXObject('Microsoft.XMLHTTP');
					} catch(e) {
						return false;
					}
				}
			}
			if (!req) return false;
			if (typeof success != 'function') success = function () {};
			if (typeof error!= 'function') error = function () {};
			req.onreadystatechange = function(){
				if(req.readyState == 4) {
					return req.status === 200 ? 
					success(req.responseText) : error(req.status);
				}
			}
			req.open('GET', url+'?topic='+id, true);
			req.send(null);
			return req;
		}
        </script>