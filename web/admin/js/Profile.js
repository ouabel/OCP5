$(document).ready(function(){

	loadDistricts = function(jsDistrict, province){
		currentVal = jsDistrict.val();
		jsDistrict.html("");
		jQuery.when(
			jQuery.getJSON(districtsDataUrl + '?province=' + province)
		).done( function(data) {
			jsDistrict.select2({
				placeholder: 'Sélectionnez une commune',
				data: data
			});
			jsDistrict.val(currentVal).trigger('change');
		});
	}
	var jsProvince = $('#profile_province');
	province = jsProvince.val();
	var jsDistrict = $("#profile_district_autocomplete");
	loadDistricts(jsDistrict, province);
	jsProvince.on('change', function (e) {
		var province = this.value;
		loadDistricts(jsDistrict, province);
	});

	var latitudeInput = $('#profile_latitude');
	var longitudeInput = $('#profile_longitude');

	$('.js-pick-location').locationpicker({
		location: {
			latitude: latitudeInput.val(),
			longitude: longitudeInput.val()
		},
		radius: 300,
		inputBinding: {
			latitudeInput: latitudeInput,
			longitudeInput: longitudeInput
		}
	}).height('400px');;

});