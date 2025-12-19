import domReady from '@wordpress/dom-ready';

domReady(function () {

	// Character counter
	var textarea = document.getElementById('the-quip');
	var charCount = document.getElementById('the-character-count');
	var segmenter = new Intl.Segmenter('en-US', {
		granularity: 'grapheme',
	});

	textarea.addEventListener('input', function () {
		charCount.textContent = [...segmenter.segment(this.value)].length;
	});
});
