function deco_soc_sharing_window(url, name) {

	var popup_width = 300;
	var popup_height = 400;
	var popup_top = Math.max(0, (window.outerHeight - popup_height) / 2);
	var popup_left = Math.max(0, (window.outerWidth - popup_width) / 2);

	if (window.showModalDialog) {
		window.showModalDialog(url, name, "dialogWidth:500px;dialogHeight:500px");
	} else {
		window.open(url, name, 'height=500,width=500,toolbar=no,directories=no,status=no,linemenubar = no,scrollbars = no,resizable=no,modal=yes,left=' + popup_left + ',top=' + popup_top);
	}
}
