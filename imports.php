<!--utf8 and CSS-->
<meta charset="utf-8">
<link rel="stylesheet" href="css.css">
<link rel="icon" href="img/favicon.ico" type="image/x-icon">
<meta name="description" content="">
<!--libs-->
<script src="js/lz-string.js"></script>
<script src="js/charts/loader.js"></script>
<!--translation--><?php include'languages/write.php' ?>
<!--order is important. first dataModel, then cookies-->
<script src="dataModel/global.js"></script>
<script src="dataModel/substages.js"></script>
<script async src="dataModel/info.js"></script>
<script async src="dataModel/dataQuality.js"></script>
<script async src="dataModel/level3variables.js"></script>
<script async src="dataModel/level2warnings.js"></script>
<script async src="dataModel/level2only.js"></script>
<script src="dataModel/questions.js"></script>
<script async src="dataModel/formulas.js"></script>
<script async src="dataModel/units.js"></script>
<script async src="dataModel/tables.js"></script>
<script src="dataModel/refValues.js"></script>
<script async src="dataModel/exceptions.js"></script>
<script async src="dataModel/normalization.js"></script>
<script async src="dataModel/averagedVariables.js"></script>
<script async src="dataModel/opps.js"></script>
<script src="dataModel/constants.js"></script>
<script src="js/cookies.js"></script>
<script src="js/updateGlobalFromCookies.js"></script>
<script src="dataModel/tips.js"></script>
<script src="utils.js"></script>
<!--graphs: import after translation--><?php include'graphs.php'?>

<!--utils-->
<script>
	/** Colors for GHG emissions */
	var ColorsGHG = {
		ws_KPI_GHG_elec      :"#3366CC", //electricity
		ws_KPI_GHG_ne        :"#DC3912", //fuel
		ww_KPI_GHG_elec      :"#FF9900", //electricity
		ww_KPI_GHG_ne_ch4_wwt:"#109618", //methane treated
		ww_KPI_GHG_ne_n2o_tre:"#990099", //nitrogen treated
		ww_KPI_GHG_ne_tsludge:"#0099C6", //transport
		ww_KPI_GHG_ne_ch4_unt:"#DD4477", //methane untreated
		ww_KPI_GHG_ne_n2o_unt:"#66AA00", //nitrogen untreated
		ww_KPI_GHG_ne_engines:"#B82E2E", //fuel
	}

	<?php
		//create a menu for folding the parent div.card element
		function cardMenu($name)
		{
			echo "
			<div class=menu onclick=this.parentNode.classList.toggle('folded')>
				<button></button>
				$name
			</div>
			";
		}
	?>

	/** Find a variable code inside 'Global'*/
	function locateVariable(code)
	{
		var localization={};//e.g {"level":"Water","sublevel":"Abstraction"}
		localization['toString']=function()
		{
			var levelName=(function()
			{
				switch(localization.level)
				{
					case "Water": return translate('Water');break;
					case "Waste": return translate('Waste');break;
					default: return localization.level;break;
				}
			})();

			if(localization.sublevel)
			{
				var sublevelName=(function()
				{
					switch(localization.sublevel)
					{
						default: return localization.sublevel; break;
					}
				})(); 
				return levelName+"/"+sublevelName
			}
			else
				return levelName;
		};

		for(var level in Global)
		{
			for(var field in Global[level])
			{
				if(typeof(Global[level][field])=='object')
				{
					for(var subfield in Global[level][field])
					{
						if(code==subfield)
						{
							localization["level"]=level;
							localization["sublevel"]=field;
							return localization;
							break;
						}
					}
				}
				else
				{
					if(code==field)
					{
						localization["level"]=level;
						localization["sublevel"]=0;
						return localization;
						break;
					}
				}
			}
		}
		return false;
	}

	/** return 3.999,4 instead of 3999.4*/
	function format(number)
	{
		//for work in progess formulas: -999
		if(number==-999){return "<span style=background:yellow>Formula under development</span>";}

		var str=new Intl.NumberFormat('en-EN',{maximumFractionDigits:2}).format(number);
		if(str=="NaN" || !isFinite(number)) return "<span style=color:#666>~<?php write('#missing_inputs')?></span>";
		return str;
	}

	/** make a table row inactive. Used according to Questions**/
	function disableRow(row)
	{
		row.style.display='none';
		return
		//Change color
		row.style.background='#eee';
		row.style.color='#aaa';
		row.style.cursor="not-allowed";
	}
</script>

<!--title-->
<title>ECAM Web Tool</title>
