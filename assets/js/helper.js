errorMessage = {};
$.ajax({
    url: messagesUrl,
    dataType: 'json',
    success: function(result) {
        errorMessage = result;
    }
});

function getErrorMessage(id) {

	// Verify if codError exists
	var message = typeof errorMessage[id] === 'undefined' ? "Unknown error" : errorMessage[id];

	// Replace %?% with the arguments data
	for (i=1; i < arguments.length; i++) {
	    message = message.replace("%?%", arguments[i]);
	}

	return message;
}
