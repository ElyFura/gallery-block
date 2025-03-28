@once
    @push('linkstack-head')
        <link rel="stylesheet" href="{{block_asset('assets/style.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
        @if(isset($link->layout_type) && $link->layout_type == "carousel")
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
        @endif
        @if(isset($link->layout_type) && $link->layout_type == "masonry")
            <style>
                .ls-gallery-masonry {
                    column-count: {{ isset($link->columns) && $link->columns != 'auto' ? $link->columns : 3 }};
                    column-gap: 15px;
                }
                .ls-gallery-masonry .ls-gallery-item {
                    break-inside: avoid;
                    margin-bottom: 15px;
                    display: block;
                    width: 100%;
                    height: auto;
                }
                @media (max-width: 768px) {
                    .ls-gallery-masonry {
                        column-count: 2;
                    }
                }
                @media (max-width: 480px) {
                    .ls-gallery-masonry {
                        column-count: 1;
                    }
                }
            </style>
        @endif
    @endpush
@endonce

<div class="ls-gallery">
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

        // Lazy Loading
        $lazyLoading = isset($link->lazy_load) && $link->lazy_load == "1";

        // Lightbox-Effekt
        $lightboxEffect = $link->lightbox_effect ?? 'fade';
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
        <div class="ls-gallery-masonry">
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
            <div class="ls-gallery-carousel">
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
        @if(isset($link->layout_type) && $link->layout_type == "carousel")
            <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
        @endif
        @if(isset($link->layout_type) && $link->layout_type == "masonry")
            <script src="https://cdnjs.cloudflare.com/ajax/libs/imagesloaded/4.1.4/imagesloaded.pkgd.min.js"></script>
        @endif
        <script src="{{block_asset('assets/script.js')}}"></script>

        <script>
			document.addEventListener('DOMContentLoaded', function() {
				// Lightbox-Optionen basierend auf den Einstellungen
				lightbox.option({
					'resizeDuration': 300,
					'wrapAround': true,
					'albumLabel': '{{block_text("Image %1 of %2")}}',
					'fadeDuration': {{ $lightboxEffect == 'fade' ? 300 : 0 }},
					'imageFadeDuration': {{ $lightboxEffect == 'fade' ? 300 : 0 }},
					'showImageNumberLabel': true
				});

                @if(isset($link->layout_type) && $link->layout_type == "carousel")
				// Karussell initialisieren
				$('.ls-gallery-carousel').slick({
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
                @endif

				// Filter-Funktionalität
				const filterButtons = document.querySelectorAll('.ls-gallery-filters button');
				if (filterButtons.length > 0) {
					filterButtons.forEach(button => {
						button.addEventListener('click', function() {
							const filter = this.getAttribute('data-filter');

							// Button-Stil aktualisieren
							filterButtons.forEach(btn => btn.classList.remove('active'));
							this.classList.add('active');

							// Bilder filtern
							const items = document.querySelectorAll('.ls-gallery-item, .ls-gallery-carousel-slide');
							items.forEach(item => {
								if (filter === 'all') {
									item.style.display = '';
									if (item.classList.contains('ls-gallery-carousel-slide')) {
										item.classList.remove('slick-hidden');
									}
								} else {
									const tags = item.getAttribute('data-tags').split(',');
									if (tags.includes(filter)) {
										item.style.display = '';
										if (item.classList.contains('ls-gallery-carousel-slide')) {
											item.classList.remove('slick-hidden');
										}
									} else {
										item.style.display = 'none';
										if (item.classList.contains('ls-gallery-carousel-slide')) {
											item.classList.add('slick-hidden');
										}
									}
								}
							});

                            @if(isset($link->layout_type) && $link->layout_type == "carousel")
							// Karussell nach Filterung aktualisieren
							$('.ls-gallery-carousel').slick('setPosition');
                            @endif
						});
					});
				}
			});
        </script>
    @endpush
@endonce