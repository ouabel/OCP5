$(document).ready(function(){

var filterForm = $("#filter_form");
var whatInput = $('#filter_form_what');
whatInput.select2({
  language: "fr",
  placeholder: "Quoi? ex: Pédiatre",
  width: '100%',
  ajax: {
	url: whatDataUrl,
	dataType: 'json',
	delay: 250,
	data: function (params) {
	  return {
		q: params.term
	  };
	},
	processResults: function (data) {
	  return {
		results: data
	  };
	},
	cache: true
  },
  minimumInputLength: 2
});

var whereInput = $('#filter_form_where');
whereInput.select2({
  language: "fr",
  placeholder: "Où? ex: Alger",
  width: '100%',
  ajax: {
	url: whereDataUrl,
	dataType: 'json',
	delay: 250,
	data: function (params) {
	  return {
		q: params.term
	  };
	},
	processResults: function (data) {
	  return {
		results: data
	  };
	},
	cache: true
  },
  minimumInputLength: 2
});

whatInput.on('change', function (e) {
  if ( whereInput.val() !== null ) {
	filterForm.submit();
  } else {
	console.log($('.where-row'));
	$('.where-row').removeClass('hidden-xs');
	whereInput.select2('open');
  }
});

whereInput.on('change', function (e) {
  if ( whatInput.val() !== null ) {
	filterForm.submit();
  } else {
	whatInput.select2('open');
  }

});

});