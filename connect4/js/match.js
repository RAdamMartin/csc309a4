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
			} else if (game.board[j][i] == 1){
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
				if (game.turn > 2){
					if (game.turn == side){
						alert("You Win!");
					} else {
						alert("You Lose!");
					}
				}
				break;
			}
		}
	}
	drawPlays();
}

function setCol (str) {
	col = parseInt(str);
  	$('#msg').val(str);
	drawPlays();
}

function checkWin(row, col){
	/*var max = 1;
	var above = (col < 6) ? (col + 1) : col;
	var below = (col > 0) ? (col - 1) : col;
	var left = (row > 0) ? (row - 1) : row;
	var right = (row < 5) ? (row + 1) : row;*/

	var count = 1;
	var i = row+1;
	var j = col+1;

	while(i <5 && j <6){
		if (game.board[i][j] == game.board[row][col]){
			count++;
			i++;
			j++;
		} else {
			break;
		}
		if (count == 4){
			game.turn = game.board[i][j] += 2;
			while (count > 0){
				game.board[i][j] += 2;
				i--;
				j--;
				count--;
			}
			break;
		}
	}
	if (count != 0){
		i = row-1;
		j = col-1;
		while(i >=0 && j >=0){
			if (game.board[i][j] == game.board[row][col]){
				count++;
				i--;
				j--;
			} else {
				break;
			}
			if (count == 4){
				game.turn = game.board[i][j] += 2;
				while (count > 0){
					game.board[i][j] += 2;
					i++;
					j++;
					count--;
				}
			}
		}
	}

	var count = 1;
	var i = row-1;
	var j = col+1;

	while(i <5 && j <6){
		if (game.board[i][j] == game.board[row][col]){
			count++;
			i--;
			j++;
		} else {
			break;
		}
		if (count == 4){
			game.turn = game.board[i][j] += 2;
			while (count > 0){
				game.board[i][j] += 2;
				i++;
				j--;
				count--;
			}
			break;
		}
	}
	if (count != 0){
		i = row+1;
		j = col-1;
		while(i >=0 && j >=0){
			if (game.board[i][j] == game.board[row][col]){
				count++;
				i++;
				j--;
			} else {
				break;
			}
			if (count == 4){
				game.turn = game.board[i][j] += 2;
				while (count > 0){
					game.board[i][j] += 2;
					i--;
					j++;
					count--;
				}
			}
		}
	}

	for (var i = 0; i < 6; i++){
		for (var j = 0; j < 7; j++){
			//Cols
			if (game.board[i][j] > 0){
				if (i < 3 && game.board[i][j] == game.board[i+1][j] && 
							game.board[i][j] == game.board[i+2][j] && 
							game.board[i][j] == game.board[i+3][j]){
					game.board[i][j] += 2;
					game.board[i+1][j] += 2;
					game.board[i+2][j] += 2;
					game.board[i+3][j] += 2;
					game.turn = game.board[i][j] += 2;
				}
				else if (i > 2 && game.board[i][j] == game.board[i-1][j] && 
								game.board[i][j] == game.board[i-2][j] && 
								game.board[i][j] == game.board[i-3][j]){
					game.board[i][j] += 2;
					game.board[i-1][j] += 2;
					game.board[i-2][j] += 2;
					game.board[i-3][j] += 2;
					game.turn = game.board[i][j] += 2;
				}
				//Rows
				if (j < 4 && game.board[i][j] == game.board[i][j+1] && 
							game.board[i][j] == game.board[i][j+2] && 
							game.board[i][j] == game.board[i][j+3]){
					game.board[i][j] += 2;
					game.board[i][j+1] += 2;
					game.board[i][j+2] += 2;
					game.board[i][j+3] += 2;
					game.turn = game.board[i][j] += 2;
				}
				else if (j > 2 && game.board[i][j] == game.board[i][j-1] && 
							game.board[i][j] == game.board[i][j-2] && 
							game.board[i][j] == game.board[i][j-3]){
					game.board[i][j] += 2;
					game.board[i][j+1] += 2;
					game.board[i][j+2] += 2;
					game.board[i][j+3] += 2;
					game.turn = game.board[i][j] += 2;
				}
			}
		}
	}
}