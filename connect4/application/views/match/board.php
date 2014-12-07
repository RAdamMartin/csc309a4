
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

<textarea style="visibility:hidden" name="conversation" cols="1" rows="1" ></textarea>
<?php 
	echo form_open();
	?>
<input type="hidden" id="msg" type="text" name="msg" value=""></input>
<?php
	echo form_submit('Send','Submit Play');

	echo form_close();
	
?>
<p id="statusMsg" name="statusMsg"></p>
<br>

	<div class="gameArea" style="position:relative; width:780px; height:670px">
		<canvas class="board" id="frame" style="z-index: 1; border:5px solid #FFFF00;" width="770" height="660"></canvas>
		<canvas class="board" id="plays" style="z-index: 2;" width="770" height="660"></canvas>	
	</div>

	<br>

<button style="visibility:hidden" id="0" onclick="setCol('0')">SELECT PLAY</button>
<button style="visibility:hidden" id="1" onclick="setCol('1')">SELECT PLAY</button>
<button style="visibility:hidden" id="2" onclick="setCol('2')">SELECT PLAY</button>
<button style="visibility:hidden" id="3" onclick="setCol('3')">SELECT PLAY</button>
<button style="visibility:hidden" id="4" onclick="setCol('4')">SELECT PLAY</button>
<button style="visibility:hidden" id="5" onclick="setCol('5')">SELECT PLAY</button>
<button style="visibility:hidden" id="6" onclick="setCol('6')">SELECT PLAY</button>

</body>

<script src="<?= base_url() ?>/js/match.js"></script>
<script>
var otherUser = "<?= $otherUser->login ?>";
var user = "<?= $user->login ?>";
var status = "<?= $status ?>";
var side = "<?= $side ?>";
var other = (side == 1) ? 2 : 1;
var col = -1;
var colour1 = "red";
var bgrd_col = "#FFFF00";
var colour2 = "blue";
var game = {turn:1,
			winner:0, 
			board:
			[[0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0]]};

var bgrd = document.getElementById("frame");
var bctx = bgrd.getContext("2d");
var moves = document.getElementById("plays");
var mctx = moves.getContext("2d");

var winner = document.getElementById("winner");

bctx.fillStyle = bgrd_col;
bctx.fillRect(0, 0, bgrd.width, bgrd.height);	

moves.addEventListener('click', function(event){
	var x = event.pageX - moves.offsetLeft;
	var sel = ~~(x/110);
	if (document.getElementById(sel).disabled != true){
		setCol(sel);
	}
});

if(game.winner == 0){
	if (game.turn == side){
		$('[name=statusMsg]').html("Select a play");
	} else {
		$('[name=statusMsg]').html("<?=$otherUser->login?>'s turn");
	}
} else if (game.winner == 3){
	$('[name=statusMsg]').html("Draw!");
} else if (game.winner == side){
	$('[name=statusMsg]').html("You win!");
} else {
	$('[name=statusMsg]').html("You lose!");
}

$('body').everyTime(2000,function(){
	console.log("winner: " + game.winner);
	if (status == 'waiting') {
		$.getJSON('<?= base_url() ?>arcade/checkInvitation',function(data, text, jqZHR){
				if (data && data.status=='rejected') {
					alert("Sorry, your invitation to play was declined!");
					window.location.href = '<?= base_url() ?>arcade/index';
				}
				if (data && data.status=='accepted') {
					status = 'playing';
					$('#status').html('Playing ' + otherUser);
				}
				
		});
	}
	if (game.winner == 0){
		var url = "<?= base_url() ?>board/getMsg";
		$.getJSON(url, function (data,text,jqXHR){
			if (data && data.status=='success') {
				var conversation = $('[name=conversation]').val();
				var msg = data.message;
				console.log("setting winner to "+data.winner);
				game.winner = data.winner;
				if (msg!= null && msg.length > 0){
					console.log("Received: " + msg);
					$('[name=conversation]').val(conversation + "\n" + otherUser + ": " + msg);
					console.log("setting play for " + other + " in col " + msg);
					setPlay(parseInt(msg), other);
					drawPlays();	
				}
			}
		});
	}
	if(game.winner == 0){
		if (game.turn == side){
			$('[name=statusMsg]').html("Select a play");
		} else {
			$('[name=statusMsg]').html("<?=$otherUser->login?>'s turn");
		}
	} else if (game.winner == 3){
		$('[name=statusMsg]').html("Draw!");
	} else if (game.winner == side){
		$('[name=statusMsg]').html("You win!");
	} else {
		$('[name=statusMsg]').html("You lose!");
	}
});

$('form').submit(function(event){
	if (game.turn == side && game.winner == 0){
		var msg = $('[name=msg]').val();
		setPlay(parseInt(msg), side);
		var arguments = $(this).serialize();
		var url = "<?= base_url() ?>board/postMsg";
		$.post(url,arguments, function (data,textStatus,jqXHR){
				var conversation = $('[name=conversation]').val();
				console.log("2setting winner to "+data.winner);
				game.winner = data.winner;
				$('[name=conversation]').val(conversation + "\n" + user + ": " + msg);
				});
		$('[name=msg]').val("select play");
		col = -1;
		drawPlays();
		return false;
	} else {
		event.preventDefault();
	}
});	
<?php
foreach ($matchHist as $var) {
	echo 'setPlay('.$var.',game.turn);';
}?>
drawPlays();
</script>
</html>

