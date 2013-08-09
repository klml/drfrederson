T.ready(function() {
	var topbar = document.createElement('div');
	topbar.className = 'goodbye_ie';
	topbar.innerHTML = 'Goodbye IE. Hello <a href="http://google.com/chrome" target="_blank">Google Chrome</a> / <a href="http://firefox.com" target="_blank">Firefox</a>';
	document.body.insertBefore(topbar, T.$('container'));
});
