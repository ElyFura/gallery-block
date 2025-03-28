document.addEventListener('DOMContentLoaded', function() {
	// Lightbox Konfiguration
	if (typeof lightbox !== 'undefined') {
		lightbox.option({
			'resizeDuration': 300,
			'wrapAround': true,
			'albumLabel': 'Bild %1 von %2',
			'fadeDuration': 300,
			'imageFadeDuration': 300,
			'positionFromTop': 50
		});
	}

	// Alle Carousels initialisieren
	if (typeof $ !== 'undefined' && typeof $.fn.slick !== 'undefined') {
		initializeCarousels();
	} else {
		// Warte auf jQuery und Slick, falls sie verzögert geladen werden
		var checkSlickInterval = setInterval(function() {
			if (typeof $ !== 'undefined' && typeof $.fn.slick !== 'undefined') {
				clearInterval(checkSlickInterval);
				initializeCarousels();
			}
		}, 100);
	}

	// Filter-Funktionalität
	initializeFilters();

	// Hilfsfunktion zum Initialisieren aller Carousels
	function initializeCarousels() {
		$('.ls-gallery-carousel').each(function() {
			var $carousel = $(this);
			if (!$carousel.hasClass('slick-initialized')) {
				try {
					$carousel.slick({
						dots: true,
						arrows: true,
						infinite: true,
						speed: 500,
						slidesToShow: 1,
						slidesToScroll: 1,
						adaptiveHeight: false,
						autoplay: true,
						autoplaySpeed: 4000,
						centerMode: false,
						centerPadding: '0px',
						focusOnSelect: true
					});
				} catch (e) {
					console.error('Fehler beim Initialisieren des Carousels:', e);
				}
			}
		});
	}

	// Hilfsfunktion zum Initialisieren der Filter
	function initializeFilters() {
		var filterButtons = document.querySelectorAll('.ls-gallery-filters button');
		filterButtons.forEach(function(button) {
			button.addEventListener('click', function() {
				var filter = this.getAttribute('data-filter');
				var gallery = this.closest('.ls-gallery');

				// Button-Stil aktualisieren
				var buttons = gallery.querySelectorAll('.ls-gallery-filters button');
				buttons.forEach(function(btn) {
					btn.classList.remove('active');
				});
				this.classList.add('active');

				// Bilder filtern
				var items = gallery.querySelectorAll('.ls-gallery-item, .ls-gallery-carousel-slide');
				items.forEach(function(item) {
					if (filter === 'all') {
						item.style.display = '';
						if (item.classList.contains('slick-slide')) {
							item.classList.remove('slick-hidden');
						}
					} else {
						var tags = item.getAttribute('data-tags') || '';
						var tagArray = tags.split(',');
						if (tagArray.indexOf(filter) !== -1) {
							item.style.display = '';
							if (item.classList.contains('slick-slide')) {
								item.classList.remove('slick-hidden');
							}
						} else {
							item.style.display = 'none';
							if (item.classList.contains('slick-slide')) {
								item.classList.add('slick-hidden');
							}
						}
					}
				});

				// Karussell aktualisieren, falls vorhanden
				if (typeof $ !== 'undefined' && typeof $.fn.slick !== 'undefined') {
					var $carousel = $(gallery).find('.ls-gallery-carousel');
					if ($carousel.length && $carousel.hasClass('slick-initialized')) {
						$carousel.slick('setPosition');
					}
				}
			});
		});
	}

	// Lazy Loading für Bilder
	var lazyLoadImages = document.querySelectorAll('img[loading="lazy"]');
	if ('loading' in HTMLImageElement.prototype) {
		// Der Browser unterstützt natives Lazy Loading
		console.log('Native lazy loading wird unterstützt');
	} else {
		// Fallback für Browser ohne natives Lazy Loading
		if ('IntersectionObserver' in window) {
			var imageObserver = new IntersectionObserver(function(entries, observer) {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						var lazyImage = entry.target;
						lazyImage.src = lazyImage.dataset.src;
						lazyImage.removeAttribute('data-src');
						imageObserver.unobserve(lazyImage);
					}
				});
			});

			lazyLoadImages.forEach(function(lazyImage) {
				imageObserver.observe(lazyImage);
			});
		} else {
			// Fallback für ältere Browser ohne IntersectionObserver
			var active = false;

			var lazyLoad = function() {
				if (active === false) {
					active = true;

					setTimeout(function() {
						lazyLoadImages.forEach(function(lazyImage) {
							if ((lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== "none") {
								lazyImage.src = lazyImage.dataset.src;
								lazyImage.removeAttribute('data-src');

								lazyLoadImages = Array.prototype.filter.call(lazyLoadImages, function(image) {
									return image !== lazyImage;
								});

								if (lazyLoadImages.length === 0) {
									document.removeEventListener("scroll", lazyLoad);
									window.removeEventListener("resize", lazyLoad);
									window.removeEventListener("orientationchange", lazyLoad);
								}
							}
						});

						active = false;
					}, 200);
				}
			};

			document.addEventListener("scroll", lazyLoad);
			window.addEventListener("resize", lazyLoad);
			window.addEventListener("orientationchange", lazyLoad);
		}
	}
});