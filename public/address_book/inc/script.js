(function() {
	
	$('.dlt-btn-address').on('click', function() {
		var addressId = $(this).data('address-id');
		$('#delete-address').val(addressId);
		var confirmation = confirm('Are you sure you would like to delete');
		
		if (confirmation) {
			$("#delete-address-form").submit();
		}

	});			

	$('.dlt-btn-person').on("click", function() {
		var personId = $(this).data('person-id');
		$('#delete-person').val(personId);
		var confirmation = confirm('Are you sure you would like to delete');
		
		if (confirmation) {
			$("#delete-person-form").submit();
		}
	});
		
})();