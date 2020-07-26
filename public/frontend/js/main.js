'use strict';

window.onload = function(e){
	
	const inputs = document.querySelectorAll('#family-calculator-form input');
	const sum = document.querySelector('#sum');
	const familySimulatorDataName = 'familySimulatorData';
	// Check for saved the Family Simulator Data 
	let savedData = localStorage.getItem(familySimulatorDataName);

	//async function to send POST data
	const postCORS = (url, data) => {
		return new Promise((resolve, reject) => {
			var xhr = new XMLHttpRequest();
			if (!('withCredentials' in xhr)) xhr = new XDomainRequest(); // fix IE8/9
			xhr.open("POST", url, true);
	
			//Send the proper header information along with the request
			xhr.setRequestHeader("Content-Type", "application/json");
	
			xhr.onreadystatechange = function() { // Call a function when the state changes.
				if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
					var response = this.response || this.responseText;
					response = JSON.parse(response);
					return resolve(response);
				}else if(this.readyState === XMLHttpRequest.DONE && this.status !== 200) {
					var response = this.response || this.responseText;
					response = JSON.parse(response);
					return reject(response);
				}
			}
			xhr.send(JSON.stringify({data: data}));
		});
	}

	//add a listener to all inputs
	inputs.forEach(input => {
		input.addEventListener('click', async (e) => {
			try {
				e.preventDefault()

				if(input.name === "reset"){
					localStorage.removeItem(familySimulatorDataName);
					sum.innerHTML = '';
					return;
				}

				savedData = localStorage.getItem(familySimulatorDataName);
				const savedDataTMP = (!!savedData && JSON.parse(savedData))? JSON.parse(savedData).data : [];
				await postCORS('../backend/logic.php?action=' + input.name, JSON.stringify(savedDataTMP))
				.then ( res => {
					console.log(res.result)
					// Save the Family Simulator Data to localStorage
					localStorage.setItem(familySimulatorDataName, JSON.stringify(res.result));
					populateSum(res.result)
				}).catch ( err => {
					console.log(err)
					alert(err.message)
				})
				
			  } catch(err) {
				console.error(err) // or alert it, or put the message on the page
				alert(err.message)
			  }

		
		}, false);
	});

	const populateSum = (data) => {
		if(!!data && !!data.numbers && !!data.data){
			
			let res = `<h2>Home members:</h2>`
			res += `<ul>`

			data.data.forEach(el => {
				switch(el.type){
					case 'mum':
						res += `<li>Mum: ${el.qty}</li>`
					break;
					case 'dad':
						res += `<li>Dad: ${el.qty}</li>`
						break;
					case 'children':
						res += `<li>Children: ${el.qty}</li>`
					break;
					case 'cat':
						res += `<li>Cats: ${el.qty}</li>`
					break;
					case 'dog':
						res += `<li>Dogs: ${el.qty}</li>`
					break;
					case 'goldfish':
						res += `<li>Goldfish: ${el.qty}</li>`
					break;
				}
			});

			res += `<li><b>Total Members</b>: ${data.numbers.totalMembers}</li>`
			res += `<li><b>Monthly Food Costs</b>:  ${data.numbers.cost}</li>`
			res += `</ul>`

			sum.innerHTML = res;
		}else{
			console.log(":(");
		}
	}

	

	// If there are any saved items
	if (!!savedData) {
		console.log(savedData)
		populateSum(JSON.parse(savedData))
	}else{
		console.log("No data in local storage... yet :)")
	}

}