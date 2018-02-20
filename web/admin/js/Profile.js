$(document).ready(function(){

	loadDistricts = function(jsDistrict, province){
		currentVal = jsDistrict.val();
		jsDistrict.html("");
		var dataUrl = Routing.generate('ajax_district_by_province', {
			'province': province
		});
		jQuery.when(
			jQuery.getJSON(dataUrl)
		).done( function(data) {
			jsDistrict.select2({
				data: data
			});
			jsDistrict.val(currentVal).trigger('change');
		});
	}
	var jsProvince = $('.js-province > div > div > select');
	province = jsProvince.val();
	var jsDistrict = $(".js-district > div > div > select");
	loadDistricts(jsDistrict, province);
	jsProvince.on('change', function (e) {
		var province = this.value;
		loadDistricts(jsDistrict, province);
	});

});