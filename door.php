<?php
session_start();
require('new-connection.php');

$query = "SELECT showDate, seatNumber FROM transactions";

$unavailable = fetch_all($query);

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
	
		var taken = <?php
		// function get_unavailable($unavailable) {
		// 	return $unavailable;
		// }

		// echo json_encode(array_map("get_unavailable", $taken));
		echo json_encode($unavailable);
		?>;
		
		console.log(taken);
		console.log(taken[0]['seatNumber']);
		
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
		$('.available').click(function(){
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

		});
		$("form").submit(function(){
			var stringed = JSON.stringify(added);
			console.log(stringed);

			for(i = 0; i < added.length; i++){
				$('#cartform').append("<input type='hidden' name='cart[]' value='" + added[i]+ "'>"); 
			}
			
			//event.preventDefault();
		});
		// $('.available').on('click',function(e){
		// 	e.preventDefault();
		// 	$(this).parent().remove();
		// });
	});
	</script>

</head>
<body>
	<div class='wrapper'>
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
		<?php	
			$gridcount = 0;
			$daycount = 1;
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
							echo "<div class='day open'>";
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
		<?php
			$seatnum = 1;

			for($x = 0; $x < 10; $x++ ){
				echo "<div class='table'>";
				for($y = 0; $y < 10; $y++){

							if($y % 7 == 0){
								echo "<div id='{$seatnum}' class='seat available'>";
							} else {
								echo "<div class='seat unavailable'>";
							}
								echo "<p>".$seatnum."</p>";
							echo "</div>";
					
					$seatnum++;
				}
				echo "</div>";
			}
		$seats_selected = array();
		$purchaseDate = date('Y-m-d');
		?>

		

		<form id='cartform' action='process.php' method='post'>
			<input type='hidden' name='purchaseDate' value='<?php echo $purchaseDate ?>'>
			<input type='hidden' name='showDate' value='2015-03-21'>
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
