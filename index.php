<?php
session_start();
require('new-connection.php');

?>

<!DOCTYPE html>
<html lang='en'>
<head>
	<style type="text/css">
 *{
 	padding: 1px;
 	margin: 0px;
 	font-family: helvetica;
 }
 	.space {
 		color: white;
 	}
	.month, .day {
		display: inline-block;
		border: 1px solid black;
	}
	.day {
		width: 30px;
		height: 30px;
	}
	.open {
		background-color: blue;
		color: white;
		font-weight: 700;
	}
	.start {
		background-color: yellow;
		color: black;
		font-weight: 700;
	}
	.closed {
		background-color: black;
		color: lightgray;
	}

	.table {
		padding: 1px;
	}

	.seat {
		width: 50px;
		height: 50px;
		padding: 1px;
		display: inline-block;
		border: 1px solid black;

	}

	.available {
		background-color: green;
	}

	.unavailable {
		background-color: gray;

	}
	
	.selected {
		background-color: yellow;
	}

	</style>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<script>
	$(document).ready(function(){
		

		//DATE CHANGE
		
		var seatMax = 100;
		var last_date = "2015-03-24";
		var starter = 0;
		$('#'+last_date).addClass("start");
		
		$('.open').click(function(){
			if(starter == 0){
				$('#'+last_date).removeClass("start");		
				$('#'+last_date).toggleClass("open");
				$('#'+last_date).toggleClass("selected");
				starter = 1;
			}
			$('#'+last_date).toggleClass("open");
			$('#'+last_date).toggleClass("selected");
			$(this).toggleClass("open");
			$(this).toggleClass("selected");
			last_date = $(this).attr("id");
			$('#showDate').text(last_date);
			//get new seats

			$.ajax({
				type: "POST",
				url: "dates.php",
				data: {date: last_date}
				})
				.done(function(response){

					var taken = JSON.parse(response);
					//set all to available
					for(i = 0; i < seatMax; i++){
						$('#'+i).removeClass('unavailable');
						$('#'+i).removeClass('selected');
						$('#'+i).addClass('available');
					}
					for(i = 0; i < taken.length; i++ ){
						console.log(taken[i]['seatNumber']);
						$('#'+taken[i]['seatNumber']).addClass('unavailable');
						$('#'+taken[i]['seatNumber']).removeClass('available');
					}
				});

			return false;
		});
		//END DATE CHANGE
		var added = [];
		var index = 0;
		var cid = 0;
		var indexOf = function(needle) {
	    if(typeof Array.prototype.indexOf === 'function') {
	        indexOf = Array.prototype.indexOf;
	    } else {
	        indexOf = function(needle) {
	            var i = -1, index = -1;

	            for(i = 0; i < this.length; i++) {
	                if(this[i] === needle) {
	                    index = i;
	                    break;
	                }
	            }
	            return index;
	        };
	    }
	    return indexOf.call(this, needle);
		};

		$('.seat').on("click",function(){
		
			if($(this).attr('class')=='seat available' || $(this).attr('class')=='seat selected' ){
				$(this).toggleClass("available");
				$(this).toggleClass("selected");

				id = $(this).attr("id");
				index = indexOf.call(added, id);
				
				if(index == -1){
					added.push(id);
					$('#cart').append("<p id='c"+id+"'>Seat " + added[added.length - 1]+" Added</p>");	

				} else {
					added.splice(index, 1);
					cid = "#c" + id;
					$(cid).remove();
				}
			}
			
		});
		$('form').submit(function(){
			var stringed = JSON.stringify(added);
			console.log(stringed);

			for(i = 0; i < added.length; i++){
				$('#cartform').append("<input type='hidden' name='cart[]' value='" + added[i]+ "'>"); 
			}
			

		});

	});
	</script>

</head>
<body>
	<div class='wrapper'>
		<div class='date-select'>
			<div class='month'>
				<div class='week'>
					<div class='day'>
						<p>Sun</p>
					</div>
					<div class='day'>
						<p>Mon</p>
					</div>
					<div class='day'>
						<p>Tue</p>
					</div>
					<div class='day'>
						<p>Wed</p>
					</div>
					<div class='day'>
						<p>Thu</p>
					</div>
					<div class='day'>
						<p>Fri</p>
					</div>
					<div class='day'>
						<p>Sat</p>
					</div>
				</div>
		</div>
		<?php	
			$gridcount = 0;
			$daycount = 1;
			$month = '03';
			$year = '2015';
			$startday = 3; //0=on, 1=sun, 2=mon,
			$lastday = 31;
			for($x = 0; $x < 6; $x++){
				echo "<div class='week'>";
					for($y = 0; $y < 7; $y++){
						$gridcount++;

						if($gridcount == $startday){
							$startday = 0;
						}

						if(($y == 4 || $y == 6) && $daycount < $lastday){
							if($daycount < 10){
								echo "<div id='{$year}-{$month}-0{$daycount}' class='day open'>";
							} else {
								echo "<div id='{$year}-{$month}-{$daycount}' class='day open'>";
							}

						} else {
							echo "<div class='day closed'>";
						}
					
							if($startday == 0 && $daycount != $lastday){
								echo "<p>".$daycount."</p>";
								$daycount++;
							} else {
								echo "<p class='space'>.</p>";
							}
						echo "</div> <!--day-->";
					}
				echo "</div> <!--week-->";
				if($gridcount-$startday > $lastday){
					$x = 7;
				}
			}
		?>	

		</div><!--month-->
		<!--seating chart-->
		<p>SEATS</p>
		<div id='seating_chart'>
			<?php
				$seatnum = 1;

				for($x = 0; $x < 10; $x++ ){
					echo "<div class='table'>";
					for($y = 0; $y < 10; $y++){
						echo "<div id='{$seatnum}' class='seat'>";
							echo "<p>".$seatnum."</p>";
						echo "</div>";
						$seatnum++;
					}
					echo "</div>";
				}
			$seats_selected = array();
			date_default_timezone_set('US/Pacific');
			$purchaseDate = date('Y-m-d');
			?>
		</div>
		<form id='cartform' action='process.php' method='post'>
			<input type='hidden' name='purchaseDate' value='<?php echo $purchaseDate ?>'>
			<input id='showDate' type='hidden' name='showDate' placeholder='Show Date'>
			<input type='text' name='first' placeholder='First Name'>
			<input type='text' name='last' placeholder='Last Name'>
			<input type='text' name='email' placeholder='Email Address'>
			<input type='submit' value='Checkout'>
		</form>
		<div id='cart'>
			<p>Cart Buddy</p>
		<div>	
	</div>
</body>
</html>
