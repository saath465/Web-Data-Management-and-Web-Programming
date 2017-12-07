
/*Java script program for the pong game, associated backend program for pong.html
-->The program uses the startGame() and loopdloop() functions to control the movement of the ball and the actions required to be performed
	when the ball interacts with the side walls and the players paddle.
-->The move_paddle() function is used to control the player paddle using the mouse movements and uses the on screen presence of the
	mouse to move the paddle and get the co-ordinates from the user movement*/

//Global Declaration of variables used across the functions.
var elem;
var court;
var ba_rad;
var min_x;
var max_y;
var min_y;
var max_x;
var b_x;
var ball;
var b_y;
var diff_x;
var pad_h;
var diff_y;
var dt;
var id_call;
var strike;
var final_score = 0;			//Set at the begining of load as the max_score.
var set_speed;


/*The function is used to initialize the game and the variables at the start of every game and the
same function is called when the player gets out*/

function init_game(){
	document.getElementById('messages').innerHTML = " Start Game! Don't Forget to set the speed First!!!!!!!!!!! ";
	elem = document.getElementById("ball");
	b_x = randomIntFromInterval(10,30);
	b_y = randomIntFromInterval(30,250);
	strike = 0;
	document.getElementById('strikes').innerHTML = strike;
	elem.style.top = b_y + 'px';
	elem.style.left = b_x + 'px';
}

/*The function is used to reset the speed and the other variables when the user hits the reset game*/

function reset(){
	clearInterval(id_call);
	init_game();
}

/*An associate function to retrieve the speed from the radio button chosen by the user and set the appropriate speed*/

function setSpeed(speed_data){
	if(speed_data == 0)
		set_speed = 40;
	else if(speed_data == 1)
		set_speed = 25;
	else
		set_speed = 15;
}

/*An associate function to create a random numbers between the 2 minimum and maximum numbers passed as the parameters */

function randomIntFromInterval(min,max)
{
    return Math.floor(Math.random()*(max-min+1)+min);			//Math.random() to obtain the random number.
}

/*An associate function to update the max_score of the game if the current score is grater than the  max_score*/

function update_final_score(Strokes){
	if(Strokes > final_score)
		final_score = Strokes;
}

/*This is the main function which is called when the start button is clicked or the mouse button is clicked on the court*/
function startGame(){

	court = document.getElementById('court');				//retrieve the object reference for the elements
	elem = document.getElementById("ball");
	pad = document.getElementById("paddle");
	ball = document.getElementById("ball");
	ba_rad = ball.offsetHeight;									//ball radius
	var ang = randomIntFromInterval(5,10)*((Math.PI/4)/180);
	dt = 0.75;//randomIntFromInterval(0,1);;					//dt variable
	diff_x = 15 * ang;//randomIntFromInterval(5,20);					//Define the values for dx and dy.
	diff_y = 18 * ang;//randomIntFromInterval(5,20);
	min_x = court.offsetLeft - ba_rad;							//define the court sides and their co-ordinates
	min_y = court.offsetTop - court.offsetHeight/2;
	max_x = court.offsetWidth - ball.offsetWidth - ba_rad;
	max_y = (min_y + court.offsetHeight) - ba_rad;
	pad_h = (court.offsetHeight/2) - (pad.offsetHeight/2);
	court.onmousemove = move_paddle;								//Call the mouse move funxtion to capture the mouse movements.
	var loop_count = set_speed;									//retrieve the speed variable from the user
	id_call = setInterval(loopdloop, loop_count);				//SetInterval function is used to call the ball movements function to execute every loop_count milliSeconds.

	/* This function is used to handle the ball movemenets once the game is started and to control the outcome of how the ball
			behaves when it touched the paddle and how it behaces when it moves out of the boundry on the right wall*/

	function loopdloop(){
		b_x = b_x + (diff_x * dt);			//update the value of x and y co-ordinates of the ball every iteration

		b_y = b_y + (diff_y * dt);

		//if ball is at the right wall
		if ((diff_x > 0) && (b_x > max_x))
		{
			var ball_ofset = b_y + pad_h + ball.offsetWidth + ball.offsetHeight + 15;

			//if the ball toches the paddle
			if((ball_ofset > pad.offsetTop) && ( ball_ofset < (pad.offsetTop + pad.offsetHeight))){
				b_x = max_x;
				diff_x = - diff_x;
				strike++;
				document.getElementById('strikes').innerHTML = strike;
			}
			//When the user misses the ball
			else
			{
				update_final_score(strike);
				document.getElementById('score').innerHTML = final_score;
				document.getElementById('messages').innerHTML = "You Loose, Start Game Again!!";
				court.onmousemove = '';
				clearInterval(id_call);
				init_game();
			}

		}
		//left wall
		else if ((diff_x < 0) && (b_x < min_x)){
			b_x = min_x;
			diff_x = -diff_x;
		}
		//lower wall
		else if((diff_y > 0) && (b_y > max_y)){
			b_y = max_y;
			diff_y = -diff_y;
		}
		//upper wall
		else if((diff_y < 0) && (b_y < min_y)){
			b_y = min_y;
			diff_y = -diff_y;
		}
		//update the position of the ball for every iteration
		else{
			elem.style.top = b_y + 'px';
			elem.style.left = b_x + 'px';

		}
	}
}

/* This is the function to control the movement of the paddle by tracking the mouse movements of the user*/
function move_paddle(evt){
	// Fetch y coordinate of mouse
   var pad = document.getElementById("paddle");
	 /*capture the current value of y co-ordiante by taking the scroll position and the court top position as the reference*/
   var y = (evt.clientY - (court.offsetTop - document.documentElement.scrollTop));
   // Here, (court.offsetTop - document.documentElement.scrollTop) will get the relative
   // position of "box" w.r.t to current scroll postion

   // If y below lower boundary (cannot go above upper boundary -
   // mousemove event only generated when mouse is inside box
   if(y > (court.offsetHeight - pad.offsetHeight))
      y = (court.offsetHeight - pad.offsetHeight);
   // Set position

   pad.style.top = y + 'px';
}
