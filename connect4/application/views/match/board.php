
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
	<div class="gameArea" style="position:relative; width:710px; height:610px">
		<canvas class="board" id="frame" style="z-index: 1; border:5px solid #FFFF00;"></canvas>
		<canvas class="board" id="plays" style="z-index: 2;"></canvas>	
	</div>

	<script>
		var bgrd = document.getElementById("frame");
		var bctx = bgrd.getContext("2d");
		var moves = document.getElementById("plays");
		var mctx = moves.getContext("2d");

		bctx.fillStyle = "#FFFF00";
		bctx.fillRect(0, 0, bgrd.width, bgrd.height);		

		var w = 50;
		var h = 50;
		for (var i = 0; i < 7; i++){
			for (var j=0; j < 6; j++){
				mctx.beginPath();
				mctx.arc(i*w + w/2, j*h + h/2, 10, 2*Math.PI, false);
				mctx.fillStyle = "#FF00FF";
				mctx.fill();
			    mcxt.lineWidth = 2;
			    mcxt.strokeStyle = '#FFFF00';
			    mcxt.stroke();				
			}
		}
		//mctx.fillStyle
	</script>

	<br>

<?php 
	
	echo form_textarea('conversation');
	
	echo form_open();
	echo form_input('msg');
	echo form_submit('Send','Send');
	echo form_close();
	
?>
	
	
	
	
</body>

</html>

