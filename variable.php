<!doctype html><html><head>
	<meta charset=utf-8>
	<title>ECAM Web Tool</title>
	<?php include'imports.php'?>
	<style>
		th,td{padding:1.5em}
		td.th{background:#00aff1;color:white;vertical-align:middle}
		td.input{color:#666;background-color:#eee;cursor:cell}
		td.input input{font-size:18px}
	</style>
	<script>
		function init()
		{
			updateInfoTable()
			updateResult()
		}

		/**
		 * Update a field from the Global object
		 * @param {string} field - The field of the CurrentLevel object
		 * @param {float} newValue - The new value of the field
		 */
		function updateField(field,newValue)
		{
			//if CurrentLevel[field] is a number, parse float
			if(typeof(currentStage[field])=="number"){ newValue=parseFloat(newValue);}
			//if a unit change is set, get it:
			var multiplier = Units.multiplier(field);
			/*update the object*/currentStage[field]=multiplier*newValue
			/*update views*/init()
		}

		/** Refresh table id=info */
		function updateInfoTable()
		{
			var t=document.getElementById('info')
			while(t.rows.length>0)t.deleteRow(-1)
			var newRow,newCell

			//Stage
			newRow=t.insertRow(-1)
			newCell=newRow.insertCell(-1)
			newCell.className='th'
			newCell.innerHTML="Stage"
			newCell=newRow.insertCell(-1)
			newCell.innerHTML="<a href=edit.php?level="+level+">"+levelAlias+"</a>"
			if(sublevel!=0)
				newCell.innerHTML+=" &rsaquo; <a href=edit.php?level="+level+"&sublevel="+sublevel+">"+sublevel+"</a>"

			//Type (input or output)
			newRow=t.insertRow(-1)
			newCell=newRow.insertCell(-1)
			newCell.className='th'
			newCell.innerHTML="Type"
			newRow.insertCell(-1).innerHTML=typeof(currentStage[id])=="function"?"Output":"Input"

			//Description
			newRow=t.insertRow(-1)
			newCell=newRow.insertCell(-1)
			newCell.className='th'
			newCell.innerHTML="Variable code"
			newRow.insertCell(-1).innerHTML=id

			//Magnitude
			newRow=t.insertRow(-1)
			newCell=newRow.insertCell(-1)
			newCell.className='th'
			newCell.innerHTML="Magnitude"
			newRow.insertCell(-1).innerHTML=Info[id].magnitude

			//Select units -- only inputs!

			if(typeof(currentStage[id])=='number')
			{
				newRow=t.insertRow(-1)
				newCell=newRow.insertCell(-1)
				newCell.className='th'
				newCell.innerHTML="Unit"
				newRow.insertCell(-1).innerHTML=(function()
				{
					var str="<select onchange=Units.selectUnit('"+id+"',this.value)>";
					if(Units[Info[id].magnitude]===undefined)
					{
						return Info[id].unit
					}
					var currentUnit = Global.Configuration.Units[id] || Info[id].unit
					for(unit in Units[Info[id].magnitude])
					{
						if(unit==currentUnit)
							str+="<option selected>"+unit+"</option>";
						else
							str+="<option>"+unit+"</option>";
					}
					str+="</select>"
					return str
				})();
			}

			//Value
			newRow=t.insertRow(-1)
			newCell=newRow.insertCell(-1)
			newCell.className='th'
			newCell.innerHTML="Value"
			newCell=newRow.insertCell(-1)
			if(typeof(currentStage[id])=="function")
			{
				var aux="<b>Formula</b>: "+id+" = "+Formulas.prettify(currentStage[id].toString())+
					"<br><br>"+
					"<b>Current Value</b>: "+currentStage[id]()+" "+Info[id].unit
				newCell.innerHTML=aux
				//add a row with matched variables in formula
				newRow=t.insertRow(-1)
				newCell=newRow.insertCell(-1)
				newCell.className='th'
				newCell.innerHTML="Inputs involved"
				newCell=newRow.insertCell(-1)
				var matches=Formulas.idsPerFormula(currentStage[id].toString())
				var aux=""
				matches.forEach(function(match)
				{
					var match_localization = locateVariable(match)
					match_level = match_localization.level
					match_sublevel = match_localization.sublevel
					var match_stage = match_sublevel ? Global[match_level][match_sublevel] : Global[match_level]

					aux+="<div><a href=variable.php?id="+match+" title='"+Info[match].description+"'>"+match+"</a> "+
					" = "+match_stage[match]+" "+Info[match].unit+
					"</div>"
				})
				newCell.innerHTML=aux
			}
			else
			{
				var currentUnit = Global.Configuration.Units[id] || Info[id].unit
				var multiplier = Units.multiplier(id);
				var currentValue = currentStage[id]/multiplier;
				newCell.innerHTML=currentValue+" "+currentUnit
				newCell.className='input'
				newCell.setAttribute('onclick',"transformField(this)")
			}
		}

		/** 
		 * Add a <input> to a <td> cell to make modifications in the Global object
		 * @param {element} element - the <td> cell
		 */
		function transformField(element)
		{
			element.removeAttribute('onclick')
			element.innerHTML=""
			var input=document.createElement('input')
			input.className='input'
			input.autocomplete='off'
			input.setAttribute('onkeypress',"if(event.which==13){updateField('"+id+"',this.value)}")
			input.setAttribute('onblur',"updateField('"+id+"',this.value)") //now works ok!
			var multiplier = Units.multiplier(id);
			var currentValue = currentStage[id]/multiplier;
			input.value=currentValue
			element.appendChild(input)
			input.select()
		}
	</script>
</head><body onload=init()><center>
<!--NAVBAR--><?php include"navbar.php"?>
<!--STAGES--><?php include"navStages.php"?>
<?php
	//specified input
	if(!isset($_GET['id']))die('no input specified');
	$id=$_GET['id'];
	//make the id variable live in javascript scope
	echo "<script>var id='$id';</script>";
?>

<script>
	//Define some global variables
	if(!Info[id])
	{
		document.write("<div>ERROR. Variable not defined in dataModel/Info.js</div>")
		window.stop()
	}
	var localization = locateVariable(id)
	var level 		 = localization.level
	var sublevel 	 = localization.sublevel
	var currentStage = sublevel ? Global[level][sublevel] : Global[level]
	//make the user see "Water Supply" instead of "Water"
	var levelAlias
	switch(level)
	{
		case "Water":levelAlias="Water Supply";break
		case "Waste":levelAlias="Wastewater";break
		default:levelAlias=level;break;
	}
</script>

<!--TITLE--><h1><script>document.write(Info[id].description)</script></h1>
<!--subtitle--><h4>Detailed info</h4>
<!--VARIABLE INFO--><table style="text-align:left" id=info></table>
<!--CURRENT JSON--><?php include'currentJSON.php'?>
