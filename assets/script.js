document.addEventListener('DOMContentLoaded', function() {
	// Lightbox Konfiguration (wird später in display.blade.php überschrieben)
	lightbox.option({
		'resizeDuration': 300,
		'wrapAround': true,
		'albumLabel': 'Bild %1 von %2',
		'fadeDuration': 300,
		'imageFadeDuration': 300,
		'positionFromTop': 50
	});

	// Wenn Masonry-Layout aktiviert ist
	if (document.querySelector('.ls-gallery-masonry')) {
		// imagesLoaded Library garantiert, dass die Bilder geladen sind, bevor Masonry berechnet wird
		if (typeof imagesLoaded !== 'undefined') {
			var masonryContainer = document.querySelector('.ls-gallery-masonry');
			imagesLoaded(masonryContainer, function() {
				// Masonry ist hier keine eigene Library, sondern wird mit CSS umgesetzt
				// Dieser Code ist nur um sicherzustellen, dass alle Bilder geladen sind
				masonryContainer.classList.add('images-loaded');
			});
		}
	}

	// Filterfunktionalität (wird später in display.blade.php überschrieben)
	const filterButtons = document.querySelectorAll('.ls-gallery-filters button');
	if (filterButtons.length > 0) {
		filterButtons.forEach(button => {
			button.addEventListener('click', function() {
				const filter = this.getAttribute('data-filter');

				// Button-Stil aktualisieren
				filterButtons.forEach(btn => btn.classList.remove('active'));
				this.classList.add('active');

				// Bilder filtern
				const items = document.querySelectorAll('.ls-gallery-item');
				items.forEach(item => {
					if (filter === 'all') {
						item.style.display = '';
					} else {
						const tags = item.getAttribute('data-tags').split(',');
						if (tags.includes(filter)) {
							item.style.display = '';
						} else {
							item.style.display = 'none';
						}
					}
				});
			});
		});
	}

	// Lazy Loading für Bilderoptimierung
	const lazyImages = document.querySelectorAll('.ls-gallery img[loading="lazy"]');
	if ('loading' in HTMLImageElement.prototype) {
		// Browser unterstützt native lazy loading
		console.log('Browser unterstützt natives lazy loading');
	} else {
		// Fallback für Browser ohne native lazy loading Unterstützung
		lazyImages.forEach(img => {
			const src = img.getAttribute('src');
			img.setAttribute('data-src', src);
			img.removeAttribute('src');

			// Beobachter für Intersection Observer API
			const observer = new IntersectionObserver(entries => {
				entries.forEach(entry => {
					if (entry.isIntersecting) {
						const image = entry.target;
						image.src = image.dataset.src;
						observer.unobserve(image);
					}
				});
			});

			observer.observe(img);
		});
	}
});