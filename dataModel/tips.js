/* Random tips */
var Tips=[
	"Use the TAB key (&rarrb;) to input values faster",
	"Double click a graph to download it",
	"Export results to Excel at Main Menu/Summary/Export",
	"Press &uarr; and &darr; when editing inputs to quickly modify the numbers",
	"Click on the 'untitled' substage header to change its name",
	"Move the mouse over inputs to highlight related outputs",
	"Move the mouse over outputs to highlight related inputs",
	"Move the mouse over active questions to highlight related inputs and outputs",
	"Use your browser's fullscreen mode to work more comfortably",
	"Move the mouse over an output to see its formula",
];

Tips.random=function() {
	var leng=Tips.length;
	var rand=Math.floor(Math.random()*leng);
	var tip=Tips[rand];
	if(leng>1){Tips.splice(rand,1);}
	return tip;
}
