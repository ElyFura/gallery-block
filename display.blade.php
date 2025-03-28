@once
    @push('linkstack-head')
        <link rel="stylesheet" href="{{block_asset('assets/style.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
        <!-- Immer alle notwendigen CSS-Dateien laden, unabhängig vom Layout -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    @endpush
@endonce

<div class="ls-gallery ls-gallery-layout-{{ $link->layout_type ?? 'grid' }}">
    @if(isset($link->show_title) && $link->show_title == "1" && !empty(trim($link->title ?? '')))
        <h3 class="ls-gallery-title">{{ $link->title }}</h3>
    @endif

    @php
        // Bilder und Tags verarbeiten
        $images = json_decode($link->images, true) ?? [];
        $allTags = [];

        // Alle eindeutigen Tags sammeln
        foreach ($images as $image) {
            if (!empty($image['tags'])) {
                $imageTags = array_map('trim', explode(',', $image['tags']));
                foreach ($imageTags as $tag) {
                    if (!empty($tag) && !in_array($tag, $allTags)) {
                        $allTags[] = $tag;
                    }
                }
            }
        }
        sort($allTags);

        // Layouttyp bestimmen
        $layoutType = $link->layout_type ?? 'grid';

        // Bildform-Klasse bestimmen
        $imageShapeClass = 'ls-gallery-image-' . ($link->image_shape ?? 'square');

        // Spalten-Klasse für Grid-Layout
        $columnClass = '';
        if($layoutType === 'grid' && isset($link->columns) && $link->columns !== 'auto') {
            $columnClass = 'ls-gallery-grid-' . $link->columns;
        }

        // Masonry-Spalten
        $masonryColumns = isset($link->columns) && $link->columns != 'auto' ? $link->columns : 3;

        // Lazy Loading
        $lazyLoading = isset($link->lazy_load) && $link->lazy_load == "1";

        // Lightbox-Effekt
        $lightboxEffect = $link->lightbox_effect ?? 'fade';

        // Einzigartige ID für diese Galerie erzeugen (für JavaScript-Selektoren)
        $galleryId = 'gallery-' . $link->id;
    @endphp

    @if(count($allTags) > 0)
        <div class="ls-gallery-filters mb-3">
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-primary active" data-filter="all">{{block_text('All')}}</button>
                @foreach($allTags as $tag)
                    <button type="button" class="btn btn-sm btn-outline-primary" data-filter="{{ $tag }}">{{ $tag }}</button>
                @endforeach
            </div>
        </div>
    @endif

    @if($layoutType === 'grid')
        <div class="ls-gallery-container {{ $columnClass }}">
            @foreach($images as $index => $image)
                @php
                    $tags = !empty($image['tags']) ? array_map('trim', explode(',', $image['tags'])) : [];
                    $tagClasses = !empty($tags) ? implode(' ', array_map(function($tag) { return 'tag-' . preg_replace('/[^a-z0-9]/i', '-', strtolower($tag)); }, $tags)) : '';
                    $tagDataAttr = !empty($tags) ? implode(',', $tags) : '';
                @endphp
                <div class="ls-gallery-item {{ $tagClasses }}" data-tags="{{ $tagDataAttr }}">
                    <a href="{{ $image['url'] }}" data-lightbox="gallery-{{ $link->id }}"
                       data-title="{{ $image['caption'] ?? '' }}"
                       data-lightbox-type="{{ $lightboxEffect }}">
                        <img src="{{ $image['url'] }}"
                             alt="{{ $image['caption'] ?? 'Gallery image ' . ($index + 1) }}"
                             class="ls-gallery-image {{ $imageShapeClass }}"
                             @if($lazyLoading) loading="lazy" @endif>
                        @if(isset($image['caption']) && !empty($image['caption']))
                            <div class="ls-gallery-caption">{{ $image['caption'] }}</div>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>

    @elseif($layoutType === 'masonry')
        <div class="ls-gallery-masonry" style="column-count: {{ $masonryColumns }};" id="{{ $galleryId }}-masonry">
            @foreach($images as $index => $image)
                @php
                    $tags = !empty($image['tags']) ? array_map('trim', explode(',', $image['tags'])) : [];
                    $tagClasses = !empty($tags) ? implode(' ', array_map(function($tag) { return 'tag-' . preg_replace('/[^a-z0-9]/i', '-', strtolower($tag)); }, $tags)) : '';
                    $tagDataAttr = !empty($tags) ? implode(',', $tags) : '';
                @endphp
                <div class="ls-gallery-item {{ $tagClasses }}" data-tags="{{ $tagDataAttr }}">
                    <a href="{{ $image['url'] }}" data-lightbox="gallery-{{ $link->id }}"
                       data-title="{{ $image['caption'] ?? '' }}"
                       data-lightbox-type="{{ $lightboxEffect }}">
                        <img src="{{ $image['url'] }}"
                             alt="{{ $image['caption'] ?? 'Gallery image ' . ($index + 1) }}"
                             class="ls-gallery-image"
                             @if($lazyLoading) loading="lazy" @endif>
                        @if(isset($image['caption']) && !empty($image['caption']))
                            <div class="ls-gallery-caption">{{ $image['caption'] }}</div>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>

    @elseif($layoutType === 'carousel')
        <div class="ls-gallery-carousel-container">
            <div class="ls-gallery-carousel" id="{{ $galleryId }}-carousel">
                @foreach($images as $index => $image)
                    @php
                        $tags = !empty($image['tags']) ? array_map('trim', explode(',', $image['tags'])) : [];
                        $tagClasses = !empty($tags) ? implode(' ', array_map(function($tag) { return 'tag-' . preg_replace('/[^a-z0-9]/i', '-', strtolower($tag)); }, $tags)) : '';
                        $tagDataAttr = !empty($tags) ? implode(',', $tags) : '';
                    @endphp
                    <div class="ls-gallery-carousel-slide {{ $tagClasses }}" data-tags="{{ $tagDataAttr }}">
                        <div class="ls-gallery-carousel-slide-inner">
                            <a href="{{ $image['url'] }}" data-lightbox="gallery-{{ $link->id }}"
                               data-title="{{ $image['caption'] ?? '' }}"
                               data-lightbox-type="{{ $lightboxEffect }}">
                                <img src="{{ $image['url'] }}"
                                     alt="{{ $image['caption'] ?? 'Gallery image ' . ($index + 1) }}"
                                     class="ls-gallery-image {{ $imageShapeClass }}"
                                     @if($lazyLoading) loading="lazy" @endif>
                                @if(isset($image['caption']) && !empty($image['caption']))
                                    <div class="ls-gallery-caption">{{ $image['caption'] }}</div>
                                @endif
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@once
    @push('linkstack-body-end')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
        <script src="{{block_asset('assets/script.js')}}"></script>

        <script>
			document.addEventListener('DOMContentLoaded', function() {
				// Lightbox Konfiguration
				lightbox.option({
					'resizeDuration': 300,
					'wrapAround': true,
					'albumLabel': '{{block_text("Image %1 of %2")}}',
					'fadeDuration': 300,
					'imageFadeDuration': 300,
					'showImageNumberLabel': true
				});

				// Alle Carousels initialisieren
				$('.ls-gallery-carousel').each(function() {
					var $carousel = $(this);
					if (!$carousel.hasClass('slick-initialized')) {
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
					}
				});

				// Filter-Funktionalität
				$('.ls-gallery-filters .btn').on('click', function() {
					var $this = $(this);
					var $gallery = $this.closest('.ls-gallery');
					var filter = $this.data('filter');

					// Button-Stil aktualisieren
					$this.siblings().removeClass('active');
					$this.addClass('active');

					// Bilder filtern
					if (filter === 'all') {
						$gallery.find('.ls-gallery-item, .ls-gallery-carousel-slide').show();
					} else {
						$gallery.find('.ls-gallery-item, .ls-gallery-carousel-slide').each(function() {
							var tags = $(this).data('tags') || '';
							var tagArray = tags.split(',');
							if (tagArray.indexOf(filter) !== -1) {
								$(this).show();
							} else {
								$(this).hide();
							}
						});
					}

					// Karussell aktualisieren, falls vorhanden
					var $carousel = $gallery.find('.ls-gallery-carousel');
					if ($carousel.length && $carousel.hasClass('slick-initialized')) {
						$carousel.slick('setPosition');
					}
				});
			});
        </script>
    @endpush
@endonce