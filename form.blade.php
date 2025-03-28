<link rel="stylesheet" href="{{ block_asset('assets/style.css') }}">

<div class="mb-3">
	<label for='title' class='form-label'>{{block_text('Gallery Title')}}</label>
	<input type='text' name='title' id='title' value='{{ $title ?? "" }}' class='form-control' />
</div>

<div class="form-check mb-3">
	<input class="form-check-input" type="checkbox" name="show_title" id="show_title" value="1"
		   @if(!isset($show_title) || $show_title == "1") checked @endif>
	<label class="form-check-label" for="show_title">
		{{block_text('Show title on page')}}
	</label>
</div>

<!-- Layout-Optionen -->
<div class="mb-3">
	<label for="layout_type" class="form-label">{{block_text('Gallery Layout')}}</label>
	<select name="layout_type" id="layout_type" class="form-select">
		<option value="grid" @if(!isset($layout_type) || $layout_type == "grid") selected @endif>{{block_text('Grid (Standard)')}}</option>
		<option value="masonry" @if(isset($layout_type) && $layout_type == "masonry") selected @endif>{{block_text('Masonry (Pinterest-Stil)')}}</option>
		<option value="carousel" @if(isset($layout_type) && $layout_type == "carousel") selected @endif>{{block_text('Carousel (Diashow)')}}</option>
	</select>
	<div class="form-text">{{block_text('Select the layout style for your gallery.')}}</div>
</div>

<!-- Einstellungen für Grid und Masonry -->
<div id="grid_options" class="mb-3 layout-option" @if(isset($layout_type) && $layout_type != "grid" && $layout_type != "masonry") style="display: none;" @endif>
	<label for="columns" class="form-label">{{block_text('Images per row')}}</label>
	<select name="columns" id="columns" class="form-select">
		<option value="auto" @if(!isset($columns) || $columns == "auto") selected @endif>{{block_text('Auto (responsive)')}}</option>
		<option value="1" @if(isset($columns) && $columns == "1") selected @endif>1</option>
		<option value="2" @if(isset($columns) && $columns == "2") selected @endif>2</option>
		<option value="3" @if(isset($columns) && $columns == "3") selected @endif>3</option>
		<option value="4" @if(isset($columns) && $columns == "4") selected @endif>4</option>
		<option value="5" @if(isset($columns) && $columns == "5") selected @endif>5</option>
		<option value="6" @if(isset($columns) && $columns == "6") selected @endif>6</option>
	</select>
</div>

<!-- Bildgröße und -format -->
<div class="mb-3">
	<label for="image_shape" class="form-label">{{block_text('Image Shape')}}</label>
	<select name="image_shape" id="image_shape" class="form-select">
		<option value="square" @if(!isset($image_shape) || $image_shape == "square") selected @endif>{{block_text('Square (1:1)')}}</option>
		<option value="natural" @if(isset($image_shape) && $image_shape == "natural") selected @endif>{{block_text('Natural Proportions')}}</option>
		<option value="portrait" @if(isset($image_shape) && $image_shape == "portrait") selected @endif>{{block_text('Portrait (3:4)')}}</option>
		<option value="landscape" @if(isset($image_shape) && $image_shape == "landscape") selected @endif>{{block_text('Landscape (4:3)')}}</option>
		<option value="widescreen" @if(isset($image_shape) && $image_shape == "widescreen") selected @endif>{{block_text('Widescreen (16:9)')}}</option>
	</select>
	<div class="form-text">{{block_text('Choose how your images should be displayed.')}}</div>
</div>

<!-- Lightbox Optionen -->
<div class="mb-3">
	<label for="lightbox_effect" class="form-label">{{block_text('Lightbox Effect')}}</label>
	<select name="lightbox_effect" id="lightbox_effect" class="form-select">
		<option value="fade" @if(!isset($lightbox_effect) || $lightbox_effect == "fade") selected @endif>{{block_text('Fade')}}</option>
		<option value="slide" @if(isset($lightbox_effect) && $lightbox_effect == "slide") selected @endif>{{block_text('Slide')}}</option>
		<option value="none" @if(isset($lightbox_effect) && $lightbox_effect == "none") selected @endif>{{block_text('None')}}</option>
	</select>
</div>

<!-- Lazy Loading Option -->
<div class="form-check mb-3">
	<input class="form-check-input" type="checkbox" name="lazy_load" id="lazy_load" value="1"
		   @if(!isset($lazy_load) || $lazy_load == "1") checked @endif>
	<label class="form-check-label" for="lazy_load">
		{{block_text('Enable lazy loading')}}
	</label>
	<div class="form-text">{{block_text('Images will only load when they come into view, improving page load speed.')}}</div>
</div>

<div class="mb-3">
	<label class='form-label'>{{block_text('Gallery Images')}}</label>
	<div class="small text-muted mb-2">{{block_text('Add image URLs and optional captions. Drag images or use the buttons to reorder.')}}</div>

	<div id="gallery-images-container">
		@php
			$images = json_decode($images ?? '[]', true);
            if (empty($images)) {
                $images = [['url' => '', 'caption' => '', 'tags' => '']];
            }
		@endphp

		@foreach($images as $index => $image)
			<div class="gallery-image-item mb-3" data-index="{{ $index }}">
				<div class="gallery-image-handle">
					<i class="fa-solid fa-grip-vertical"></i>
					<span class="position-badge">{{ $index + 1 }}</span>
				</div>

				<div class="gallery-image-controls">
					<button type="button" class="btn btn-sm btn-outline-secondary move-up" title="{{block_text('Move Up')}}">
						<i class="fa-solid fa-arrow-up"></i>
					</button>
					<button type="button" class="btn btn-sm btn-outline-secondary move-down" title="{{block_text('Move Down')}}">
						<i class="fa-solid fa-arrow-down"></i>
					</button>
				</div>

				<div class="input-group mb-2">
					<span class="input-group-text">URL</span>
					<input type="url" class="form-control gallery-image-url"
						   placeholder="https://example.com/image.jpg"
						   value="{{ $image['url'] }}">
					<button type="button" class="btn btn-danger gallery-remove-image"
							@if(count($images) <= 1) style="display:none" @endif>
						<i class="fa-solid fa-trash"></i>
					</button>
				</div>

				<div class="input-group mb-2">
					<span class="input-group-text">Caption</span>
					<input type="text" class="form-control gallery-image-caption"
						   placeholder="Optional image caption"
						   value="{{ $image['caption'] ?? '' }}">
				</div>

				<div class="input-group">
					<span class="input-group-text">Tags</span>
					<input type="text" class="form-control gallery-image-tags"
						   placeholder="tag1, tag2, tag3"
						   value="{{ $image['tags'] ?? '' }}">
				</div>

				<div class="form-text">{{block_text('Add comma-separated tags to enable filtering.')}}</div>
			</div>
		@endforeach
	</div>

	<button type="button" class="btn btn-primary mt-2" id="gallery-add-image">
		<i class="fa-solid fa-plus"></i> {{block_text('Add Another Image')}}
	</button>
</div>

<script>
	$(document).ready(function() {
		// Zeige/Verstecke Layout-spezifische Optionen
		$('#layout_type').on('change', function() {
			const layout = $(this).val();

			// Grid und Masonry haben Spaltenoptionen
			if (layout === 'grid' || layout === 'masonry') {
				$('#grid_options').show();
			} else {
				$('#grid_options').hide();
			}
		});

		// Aktualisiere Positions-Badges
		function updatePositionBadges() {
			$('.gallery-image-item').each(function(index) {
				$(this).attr('data-index', index);
				$(this).find('.position-badge').text(index + 1);

				// Aktiviere/Deaktiviere Auf/Ab-Buttons je nach Position
				$(this).find('.move-up').prop('disabled', index === 0);
				$(this).find('.move-down').prop('disabled', index === $('.gallery-image-item').length - 1);
			});
		}

		// Nach oben verschieben
		$('#gallery-images-container').on('click', '.move-up', function() {
			const currentItem = $(this).closest('.gallery-image-item');
			const prevItem = currentItem.prev('.gallery-image-item');

			if (prevItem.length) {
				currentItem.insertBefore(prevItem);
				updatePositionBadges();
			}
		});

		// Nach unten verschieben
		$('#gallery-images-container').on('click', '.move-down', function() {
			const currentItem = $(this).closest('.gallery-image-item');
			const nextItem = currentItem.next('.gallery-image-item');

			if (nextItem.length) {
				currentItem.insertAfter(nextItem);
				updatePositionBadges();
			}
		});

		// Aktualisiere initial die Positionen
		updatePositionBadges();

		// Add new image fields
		$('#gallery-add-image').click(function() {
			const index = $('.gallery-image-item').length;
			const newItem = `
            <div class="gallery-image-item mb-3" data-index="${index}">
                <div class="gallery-image-handle">
                    <i class="fa-solid fa-grip-vertical"></i>
                    <span class="position-badge">${index + 1}</span>
                </div>

                <div class="gallery-image-controls">
                    <button type="button" class="btn btn-sm btn-outline-secondary move-up" title="{{block_text('Move Up')}}">
                        <i class="fa-solid fa-arrow-up"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary move-down" title="{{block_text('Move Down')}}">
                        <i class="fa-solid fa-arrow-down"></i>
                    </button>
                </div>

                <div class="input-group mb-2">
                    <span class="input-group-text">URL</span>
                    <input type="url" class="form-control gallery-image-url"
                           placeholder="https://example.com/image.jpg">
                    <button type="button" class="btn btn-danger gallery-remove-image">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>

                <div class="input-group mb-2">
                    <span class="input-group-text">Caption</span>
                    <input type="text" class="form-control gallery-image-caption"
                           placeholder="Optional image caption">
                </div>

                <div class="input-group">
                    <span class="input-group-text">Tags</span>
                    <input type="text" class="form-control gallery-image-tags"
                           placeholder="tag1, tag2, tag3">
                </div>

                <div class="form-text">{{block_text('Add comma-separated tags to enable filtering.')}}</div>
            </div>
        `;
			$('#gallery-images-container').append(newItem);

			// Show all remove buttons when we have more than one image
			$('.gallery-remove-image').show();

			// Aktualisiere die Positions-Badges
			updatePositionBadges();
		});

		// Remove image fields
		$('#gallery-images-container').on('click', '.gallery-remove-image', function() {
			$(this).closest('.gallery-image-item').remove();

			// Hide the remove button on the last remaining item
			if ($('.gallery-image-item').length <= 1) {
				$('.gallery-remove-image').hide();
			}

			// Aktualisiere die Positions-Badges
			updatePositionBadges();
		});

		// Handle form submission to collect all image data
		$('form').on('submit', function(e) {
			let images = [];

			// Collect data from all image items in the current order
			$('.gallery-image-item').each(function() {
				const url = $(this).find('.gallery-image-url').val().trim();
				// Only add images with a URL
				if (url) {
					images.push({
						url: url,
						caption: $(this).find('.gallery-image-caption').val().trim(),
						tags: $(this).find('.gallery-image-tags').val().trim()
					});
				}
			});

			// Create a hidden input with the JSON data
			const imagesInput = $('<input>')
					.attr('type', 'hidden')
					.attr('name', 'images')
					.val(JSON.stringify(images));

			$(this).append(imagesInput);
		});
	});
</script>

<style>
	.gallery-image-item {
		position: relative;
		border: 1px solid #dee2e6;
		border-radius: 6px;
		padding: 15px 15px 15px 45px;
		background-color: rgba(0, 0, 0, 0.02);
		margin-bottom: 15px;
	}

	.gallery-image-handle {
		position: absolute;
		left: 15px;
		top: 50%;
		transform: translateY(-50%);
		color: #6c757d;
		cursor: pointer;
		display: flex;
		flex-direction: column;
		align-items: center;
	}

	.position-badge {
		display: inline-block;
		background-color: #6c757d;
		color: white;
		width: 24px;
		height: 24px;
		border-radius: 50%;
		text-align: center;
		line-height: 24px;
		margin-top: 5px;
		font-size: 12px;
	}

	.gallery-image-controls {
		position: absolute;
		right: 15px;
		top: 15px;
		display: flex;
		gap: 5px;
	}

	.gallery-image-controls button {
		padding: 0.2rem 0.4rem;
		font-size: 0.75rem;
	}
</style>