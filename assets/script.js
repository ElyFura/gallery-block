document.addEventListener('DOMContentLoaded', function() {
	// Hier könntest du zusätzliche Gallery-Funktionen ergänzen
	// Zum Beispiel eine Lightbox für die Vollbild-Anzeige der Bilder

		// Lightbox Konfiguration
		lightbox.option({
			'resizeDuration': 300,
			'wrapAround': true,
			'albumLabel': 'Bild %1 von %2',
			'fadeDuration': 300,
			'imageFadeDuration': 300,
			'positionFromTop': 50
		});

	const galleryImages = document.querySelectorAll('.ls-gallery-image');

	galleryImages.forEach(image => {
		image.addEventListener('click', function() {
			// Einfache Vergrößerung beim Klick
			this.classList.toggle('fullscreen');
		});
	});
});