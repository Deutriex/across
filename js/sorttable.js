	function swapElements(tableid, rowx, rowy){
		valuesX = []
		valuesY = []
		
		for (ii = 0; ii < document.querySelector(tableid).rows[rowx].cells.length; ii++){
			valuesX.push(document.querySelector(tableid).rows[rowx].cells[ii].innerHTML)	
		}

		for (jj = 0; jj < document.querySelector(tableid).rows[rowy].cells.length; jj++){
			valuesY.push(document.querySelector(tableid).rows[rowy].cells[jj].innerHTML)
		}
		
		for (jj = 0; jj < document.querySelector(tableid).rows[rowy].cells.length; jj++){
			document.querySelector(tableid).rows[rowy].cells[jj].innerHTML = valuesX[jj]
		}
		
		for (ii = 0; ii < document.querySelector(tableid).rows[rowx].cells.length; ii++){
			document.querySelector(tableid).rows[rowx].cells[ii].innerHTML = valuesY[ii]
		}
	}
	
	function parseCurrencyAmount(curr){
			if (curr.indexOf('$') == 0 && !isNaN(curr.substring(1, curr.length))){
				parsedx = parseFloat(curr.substring(1, curr.length))
			} else if (!isNaN(curr)){
				parsedx = parseFloat(curr)
			} else {
				parsedx = curr
			}
			
			return parsedx
	}
	
	String.prototype.char2charcodescomp = function(anotherstr){
		for (qsx = 0; qsx < Math.min(this.length, anotherstr.length); qsx++){
			if (this.charCodeAt(qsx) < anotherstr.charCodeAt(qsx)) return -1;
			if (this.charCodeAt(qsx) > anotherstr.charCodeAt(qsx)) return 1;
		}
		
		if (this.length < anotherstr.length) return -1;
		if (this.length > anotherstr.length) return 1;
		
		return 0;
	}
	
	function sortTable(tableid, colix){
		selTab = document.querySelector(tableid)
		if (selTab.rows[0].cells[colix].className == "sorted"){
			for (j = 1; j < parseInt(selTab.rows.length/2); j++){
				i = selTab.rows.length-j
				swapElements(tableid, j, i)	
			}
			
			if (selTab.rows[0].cells[colix].className.indexOf('inv') == -1){
				selTab.rows[0].cells[colix].className += " inv"
			} else {
				selTab.rows[0].cells[colix].className = selTab.rows[0].cells[colix].className.replace(" inv", "")
			}
			
			return
		}
		
		for (i = 1; i < selTab.rows.length; i++){
			maximumPos = -1
			minimumValue = Infinity
			for (j = i; j < selTab.rows.length; j++){
				parsed = parseCurrencyAmount(selTab.rows[j].cells[colix].innerHTML)
				
				if ((!isNaN(parsed) && !isNaN(minimumValue) && parsed < minimumValue) || (typeof(parsed) == "string" && parsed.localeCompare(minimumValue) == -1)){
					maximumPos = j
					minimumValue = parseCurrencyAmount(selTab.rows[j].cells[colix].innerHTML) 
				}
			}
			
			if (maximumPos != -1) swapElements(tableid, i, maximumPos)	
		}
		
		for (si = 0; si < selTab.rows[0].cells.length; si++){
			selTab.rows[0].cells[si].className = ""
		}
		
		selTab.rows[0].cells[colix].className = "sorted"
	}