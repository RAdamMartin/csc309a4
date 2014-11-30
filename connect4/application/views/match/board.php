
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

<script>
		var otherUser = "<?= $otherUser->login ?>";
		var user = "<?= $user->login ?>";
		var status = "<?= $status ?>";
		var side = "<?= $side ?>";
		var other = (side == 1) ? 2 : 1;
		var col = -1;
		var colour1 = "red";
		var bgrd_col = "FFFF00";
		var colour2 = "blue";
		var game = {turn:1, 
					board:
					[[0,0,0,0,0,0,0],
					[0,0,0,0,0,0,0],
					[0,0,0,0,0,0,0],
					[0,0,0,0,0,0,0],
					[0,0,0,0,0,0,0],
					[0,0,0,0,0,0,0]]};

		//$(function(){
			$('body').everyTime(2000,function(){
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
					var url = "<?= base_url() ?>board/getMsg";
					$.getJSON(url, function (data,text,jqXHR){
						if (data && data.status=='success') {
							var conversation = $('[name=conversation]').val();
							var msg = data.message;
							if (msg!= null && msg.length > 0){
								console.log("Received: " + msg);
								$('[name=conversation]').val(conversation + "\n" + otherUser + ": " + msg);
								console.log("setting play for " + other + " in col " + msg);
								setPlay(parseInt(msg), other);
								drawPlays();	
							}
						}
					});
					console.log("looping");
			});

			$('form').submit(function(event){
				if (game.turn == side){
					var msg = $('[name=msg]').val();
					setPlay(parseInt(msg), side);
					var arguments = $(this).serialize();
					var url = "<?= base_url() ?>board/postMsg";
					$.post(url,arguments, function (data,textStatus,jqXHR){
							var conversation = $('[name=conversation]').val();
							$('[name=conversation]').val(conversation + "\n" + user + ": " + msg);
							});
					$('[name=msg]').val("select play");
					col = -1;
					drawPlays();
					return false;
				} else {
					alert("It is not your turn!");
					event.preventDefault();
				}
			});	
	//	});
		var bgrd = document.getElementById("frame");
		var bctx = bgrd.getContext("2d");
		var moves = document.getElementById("plays");
		var mctx = moves.getContext("2d");

		bctx.fillStyle = bgrd_col;
		bctx.fillRect(0, 0, bgrd.width, bgrd.height);	

		moves.addEventListener('click', function(event){
			var x = event.pageX - moves.offsetLeft;
			var sel = ~~(x/110);
			if (document.getElementById(sel).disabled != true){
				setCol(sel);
			}
		});

		function drawPlays(){
			console.log("-->Drawing");
			var w = 110;
			var h = 110;
			var i,j;
			for (i = 0; i < 7; i++){
				for (j=0; j < 6; j++){
					mctx.beginPath();
					var x = i*w + w/2;
					var y = j*h + h/2;
					mctx.arc(x, y, 50, 2*Math.PI, false);
					if (game.board[j][i] == 0){
						mctx.fillStyle = (col == i) ? "grey" : "white";
					} else if (game.board[j][i]%2 == 1){
						mctx.fillStyle = colour1;
					} else {
						mctx.fillStyle = colour2;
					}
					mctx.fill();
				    mctx.lineWidth = 5;
				    if (game.board[j][i] < 3){
				    	mctx.strokeStyle = bgrd_col;
				    } else {
				    	mctx.strokeStyle = "#00ff00";
				    }
				    mctx.stroke();				
				}
			}
		}

		function setPlay(play, player){
			console.log("-->Setting " + play);
			if (play < 7 && play >= 0 && player == game.turn){
				if (game.turn == 1){
					game.turn = 2;
				} else {
					game.turn = 1;
				}
				for (i=5; i >= 0; i--){
					if (game.board[i][play] == 0){
						if (player == 1){
							game.board[i][play] = 2
						} else {
							game.board[i][play] = 1
						}
						checkWin(i, play);
						if (i == 0){
							document.getElementById(play).disabled = true;
						}
						break;
					}
				}
			}
			drawPlays();
		}
		drawPlays();

		function setCol (str) {
			col = parseInt(str);
		  	$('#msg').val(str);
			drawPlays();
		}

		function checkWin(col, row){
			/*var max = 1;
			var above = (col < 6) ? (col + 1) : col;
			var below = (col > 0) ? (col - 1) : col;
			var left = (row > 0) ? (row - 1) : row;
			var right = (row < 5) ? (row + 1) : row;

			var i = below;
			var j = left;

			for (i <= above; i++){
				for (j <= right; j++){
					if ()
				}
			}*/

			for (var i = 0; i < 7; i++){
				for (var j = 0; j < 6; j++){
					//Rows
					if (i < 4 &&board[i][j] == board[i+1][j] && 
								board[i][j] == board[i+2][j] && 
								board[i][j] == board[i+3][j]){
						board[i][j] = board[i+1][j] = board[i+2][j]=board[i+3][j]=board[i][j]+2;
						return true;
					}
					else if (i>2 && board[i][j] == board[i-1][j] && 
									board[i][j] == board[i-2][j] && 
									board[i][j] == board[i-3][j]){
						board[i][j] = board[i-1][j] = board[i-1][j]=board[i-1][j]=board[i][j]+2;
						return true;
					}
					//Cols
					if (j < 3 && board[i][j] == board[i][j+1] && 
								board[i][j] == board[i][j+2] && 
								board[i][j] == board[i][j+3]){
						board[i][j] = board[i][j+1] = board[i][j+2]=board[i][j+3]=board[i][j]+2;
						return true;
					}
					else if (j > 3 && board[i][j] == board[i][j-1] && 
								board[i][j] == board[i][j-2] && 
								board[i][j] == board[i][j-3]){
						board[i][j] = board[i][j-1] = board[i][j-2]=board[i][j-3]=board[i][j]+2;
						return true;
					}
				}
			}
		}
</script>

</body>

</html>

