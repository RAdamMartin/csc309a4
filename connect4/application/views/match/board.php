
<!DOCTYPE html>

<html>
	<head>
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/template.css">
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="<?= base_url() ?>/js/jquery.timers.js"></script>
	</head> 


<body>  
	<h1>Game Area</h1>

	<div>
	Hello <?= $user->fullName() ?>  <?= anchor('account/logout','(Logout)') ?>  
	</div>
	
	<div id='status'> 
	<?php 
		if ($status == "playing")
			echo "Playing " . $otherUser->login;
		else
			echo "Wating on " . $otherUser->login;
	?>
	</div>

<?php 
	echo form_textarea('conversation');
	
	echo form_open();
	?>
<input id="msg" type="text" name="msg" value=""></input>
<?php
	echo form_submit('Send','Send');

	echo form_close();
	
?>
	
<br>

	<div class="gameArea" style="position:relative; width:780px; height:670px">
		<canvas class="board" id="frame" style="z-index: 1; border:5px solid #FFFF00;" width="770" height="660"></canvas>
		<canvas class="board" id="plays" style="z-index: 2;" width="770" height="660"></canvas>	
	</div>

	<br>

<button id="0" onclick="setCol('0')">SELECT PLAY</button>
<button id="1" onclick="setCol('1')">SELECT PLAY</button>
<button id="2" onclick="setCol('2')">SELECT PLAY</button>
<button id="3" onclick="setCol('3')">SELECT PLAY</button>
<button id="4" onclick="setCol('4')">SELECT PLAY</button>
<button id="5" onclick="setCol('5')">SELECT PLAY</button>
<button id="6" onclick="setCol('6')">SELECT PLAY</button>

</body>

<script src="<?= base_url() ?>/js/match.js"></script>
<script>
<?php
foreach ($matchHist as $var) {
	echo 'setPlay('.$var.',game.turn);';
}?>
</script>
</html>

