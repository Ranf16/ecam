<!--BUTTONS NEW LOAD SAVE CLEAR in INDEX.PHP--> 
<!--functions are defined inside sidebar.php-->
<!--menu-->
<div style=text-align:left;padding:0em class=flex id=menu_buttons>
	<style>
		#menu_buttons button {
			display:block;
		}
	</style>

	<!--new-->
	<button class="button add" onclick=newSystem()>
		<?php write('#new')?>
	</button>

	<!--load-->
	<input type=file id=loadfile accept=".json" onchange="loadFile(event)" style=display:none>

	<!--open-->
	<button class="button edit" onclick=document.getElementById('loadfile').click()>
		<?php write('#open')?>
	</button>

	<?php
		if(isset($_COOKIE['GLOBAL']))
		{ 
			?>
			<!--SAVE-->
			<button class="button save" onclick=saveToFile()>
				<?php write('#save_as')?>
				<script>document.write(" '"+Global.General.Name+".json'")</script>
			</button>
			<!--CLEAR-->
			<button class="button delete" onclick=clearSystem()>
				<?php write('#clear')?>
			</button>
			<!--NEXT-->
			<button class="button next" onclick="event.stopPropagation();window.location='getStarted.php'">
				<?php write('#next')?>
			</button>
		  <?php 
		}
	?>
</div>
