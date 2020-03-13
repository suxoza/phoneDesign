require('./bootstrap')
require('./jkq.js')

class Base {

	constructor(){}

	get matchCNT(){
		return this.matcheCoount
	}

	set matchCNT(newValue){
		this.matcheCoount = newValue
		if(this.matcheCoount.toString() != this.matchesCountTarget.innerHTML)
			this.matchesCountTarget.innerHTML = this.matcheCoount.toString()
	}

	get inputVal(){
		return this.currentValue
	}

	set inputVal(newVal){
		this.currentValue = newVal
		if(this.currentValue != this.input.innerHTML)
			this.input.innerHTML = this.currentValue

		
		if(this.currentValue.length >= 2)
			this.searchAction()
		else
			this.makeEmpty()
	}

	hintText(elems, len){
		let result = ``
		for(let i = 0; i < elems.length; i++){
			if(i == len)break
			let elem = elems[i]
			let hidden = i > 0 && len < 4?' hidden':(len > 4)?' border-b border-gray-800 mt-4 p-3':''
			result += `<div class="flex flex-col`+hidden+`">
                        	<div class="text-2xl text-white">`+elem.name+`</div>
                        	<div class="text-xl text-gray-500 font-sans">H: `+elem.phone+`</div>
                       </div>`
		}
		return result
	}
}

const Phone = new class extends Base {
	constructor(){
		super()
		
		this.currentValue = ''		// input current value
		this.alreadyIsEmpty = true // empty result
		this.input = document.getElementById("input") // input by itself
		this.hintResultDiv = $_("#hintResultDiv") // hintResultDiv
		this.showAll = $_(".showAll") // showAll hint
		this.modal = $_("#modal") // hint modal
		this.matchesCountTarget = document.getElementsByClassName('matchCount')[0] // matches count target element
		this.matcheCoount = 0 // match count
		this.hintHtmls = document.getElementById("hintHtmls") // hintHtmls

		this.hintAlreadyShown = false

		this.liveEvents() // all events called on load
	}

	liveEvents(){
		const targets = ['numbers', 'clearButton','matches', 'showAll', 'closeHintWindow']
		let self = this
		targets.forEach(v => {
			$_('.'+v).on("click", function() {
				self[v+'Trigger'](this)
			})
		})
	}

	showAllTrigger(ths){
		this.modal.toggleClass("-mt-74").addClass('h-full')
		axios({
			method: 'post',
			url: '/search',
			data: {
				searchString: Number(this.currentValue),
				showAll: 1
			}
		}).then(data => {
			let result = data.data
			this.modal.find('.modalInner')[0].innerHTML = this.hintText(result.data, 100)
		})
	}

	closeHintWindowTrigger(ths){
		this.modal.toggleClass("-mt-74").removeClass('h-full')
	}

	numbersTrigger(ths){
		const th = $_(ths)
		let value = Number(th.data('value').toString().trim())
		if(value == 1 || value > 9)
			return this.notify("Not implemented!")
		this.inputVal += value.toString()
	}

	clearButtonTrigger(ths){
		const th = $_(ths)
		if(this.inputVal.length)
			this.inputVal = this.inputVal.slice(0, -1)
	}

	matchesTrigger(ths = null, trigger = 'show'){
		if(trigger == 'hide' || this.hintAlreadyShown){
			this.hintAlreadyShown = false
			
			return this.hintResultDiv.removeClass('h-64').addClass('h-24').find($_('.wasHidden').addClass('hidden'))
		}
		if(this.matchCNT > 1){
			this.hintResultDiv.removeClass('h-24').addClass('h-64').find($_('.hidden').removeClass('hidden').addClass("wasHidden"))
			this.hintAlreadyShown = true
			if(this.matchCNT > 3){
				this.showAll.removeClass("hidden")
			}
		}
	}
	// private methods

	async searchAction(){

		let result = await axios({
			method: 'post',
			url: '/search',
			data: {
				searchString: Number(this.currentValue)
			}
		})
		
		let data = result.data
		if(!Object.keys(data).length)return
		if(this.alreadyIsEmpty){
			console.info('make visible')
			this.hintResultDiv.removeClass('-mb-32').addClass('-mb-0')
			this.matchesCountTarget.parentNode.classList.remove("invisible")	
		}
		this.matchesTrigger(null, 'hide')
		this.matchCNT = data.length
		this.alreadyIsEmpty = false

		this.hintHtmls.innerHTML = this.hintText(data.data, 3)

	}

	makeEmpty(){
		if(this.alreadyIsEmpty)return
		this.alreadyIsEmpty = true
		console.info('make invisible')
		this.matchesTrigger(null, 'hide')
		this.hintResultDiv.removeClass('-mb-0').addClass('-mb-32')
		this.matchesCountTarget.parentNode.classList.add('invisible')
		this.matchCNT = 0
	}

	notify(message){
		console.log(message)
	}

}
