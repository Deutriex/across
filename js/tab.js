function setTab(tabsetHeaderId, tabsetId, tabId){
	getAllTables = document.getElementsByName(tabsetId)
	getAllHeads = document.getElementsByName(tabsetHeaderId)

	for (i = 0; i < getAllTables.length; i++){
		getAllTables[i].style.display = (i == tabId) ? "block" : "none";
		if (getAllHeads[i]){
			getAllHeads[i].className = (i == tabId) ? "active" : "";
		}
	}

}