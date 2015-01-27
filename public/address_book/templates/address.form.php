<!-- form to enter a new address -->
	<div class="container">
		<form class="form-inline" id="item-form" method="POST" action="/db_todo_list.php">
		<h1>Add a new address</h1>
			<div class="form-group">
				<label class="sr-only" for="address">Address</label>
				<input type="text" class="form-control"  id="address" placeholder="address" name="address"></label>
			</div>

			<div class="form-group">
				<label class="sr-only" for="city">City</label>
				<input name="city" type="text" placeholder="city" class="form-control"  id="city">
  			</div>	

  			<div class="form-group">
				<label class="sr-only" for="state">State</label>
				<input type="text" class="form-control"  id="state" placeholder="state" name="state"></label>
			</div>

  			<div class="form-group">
				<label class="sr-only" for="zip">Zip Code</label>
				<input type="text" class="form-control"  id="zip" placeholder="zip" name="zip"></label>
			</div>

			<button id="add-btn" type="submit" class="btn btn-default">Add</button>
		</form>
	</div>