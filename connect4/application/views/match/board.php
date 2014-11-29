
<!DOCTYPE html>

<html>
	<head>
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/template.css">
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="<?= base_url() ?>/js/jquery.timers.js"></script>
	<script>

		var otherUser = "<?= $otherUser->login ?>";
		var user = "<?= $user->login ?>";
		var status = "<?= $status ?>";
		var game = {turn:1, 
					board:
					[[0,0,0,0,0,0,0],
					[0,0,0,0,0,0,0],
					[0,0,0,0,0,0,0],
					[0,0,0,0,0,0,0],
					[0,0,0,0,0,0,0],
					[0,0,0,0,0,0,0]]};

		$(function(){
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
							if (msg.length > 0)
								$('[name=conversation]').val(conversation + "\n" + otherUser + ": " + msg);
						}
					});
					var url = "<?= base_url() ?>board/getMove";
					$.getJSON(url, function (data,text,jqXHR){
						if (data && data.status=='success') {
							var conversation = $('[name=conversation]').val();
							var msg = data.message;
							if (msg.length > 0)
								$('[name=conversation]').val(conversation + "\n" + otherUser + ": " + msg);
						}
					});
			});

			$('form').submit(function(){
				var arguments = $(this).serialize();
				var url = "<?= base_url() ?>board/postMsg";
				$.post(url,arguments, function (data,textStatus,jqXHR){
						var conversation = $('[name=conversation]').val();
						var msg = $('[name=msg]').val();
						$('[name=conversation]').val(conversation + "\n" + user + ": " + msg);
						});
				return false;
				});	
		});
	
	</script>
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

	<script>
		var bgrd = document.getElementById("frame");
		var bctx = bgrd.getContext("2d");
		var moves = document.getElementById("plays");
		var mctx = moves.getContext("2d");

		bctx.fillStyle = "#FFFF00";
		bctx.fillRect(0, 0, bgrd.width, bgrd.height);		

		var w = 110;
		var h = 110;
		var i,j;
		for (i = 0; i < 7; i++){
			for (j=0; j < 6; j++){
				mctx.beginPath();
				var x = i*w + w/2;
				var y = j*h + h/2;
				console.log("Drawing at " + x + "," + y);
				mctx.arc(x, y, 50, 2*Math.PI, false);
				mctx.fillStyle = "white";
				mctx.fill();
			    mctx.lineWidth = 2;
			    mctx.strokeStyle = '#FFFF00';
			    mctx.stroke();				
			}
		}
		//mctx.fillStyle
	</script>

	<br>

<button onclick="setCol('1')">SELECT PLAY</button>
<button onclick="setCol('2')">SELECT PLAY</button>
<button onclick="setCol('3')">SELECT PLAY</button>
<button onclick="setCol('4')">SELECT PLAY</button>
<button onclick="setCol('5')">SELECT PLAY</button>
<button onclick="setCol('6')">SELECT PLAY</button>
<button onclick="setCol('7')">SELECT PLAY</button>

<script>
function setCol (str) {
  $('#msg').val(str);
}
</script>

</body>

</html>

