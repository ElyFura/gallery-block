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
	<div class="form-text">{{block_text('You can disable this to hide the title on your page, but still keep it for reference in your admin panel.')}}</div>
</div>

<div class="mb-3">
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
	<div class="form-text">{{block_text('Select how many images to display per row, or choose "Auto" for responsive layout.')}}</div>
</div>

<div class="mb-3">
	<label class='form-label'>{{block_text('Gallery Images')}}</label>
	<div class="small text-muted mb-2">{{block_text('Add image URLs and optional captions')}}</div>

	<div id="gallery-images-container">
		@php
			$images = json_decode($images ?? '[]', true);
            if (empty($images)) {
                $images = [['url' => '', 'caption' => '']];
            }
		@endphp

		@foreach($images as $index => $image)
			<div class="gallery-image-item mb-3">
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
				<div class="input-group">
					<span class="input-group-text">Caption</span>
					<input type="text" class="form-control gallery-image-caption"
						   placeholder="Optional image caption"
						   value="{{ $image['caption'] ?? '' }}">
				</div>
			</div>
		@endforeach
	</div>

	<button type="button" class="btn btn-primary" id="gallery-add-image">
		<i class="fa-solid fa-plus"></i> {{block_text('Add Another Image')}}
	</button>
</div>

<script>
	$(document).ready(function() {
		// Add new image fields
		$('#gallery-add-image').click(function() {
			const newItem = `
            <div class="gallery-image-item mb-3">
                <div class="input-group mb-2">
                    <span class="input-group-text">URL</span>
                    <input type="url" class="form-control gallery-image-url"
                           placeholder="https://example.com/image.jpg">
                    <button type="button" class="btn btn-danger gallery-remove-image">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <div class="input-group">
                    <span class="input-group-text">Caption</span>
                    <input type="text" class="form-control gallery-image-caption"
                           placeholder="Optional image caption">
                </div>
            </div>
        `;
			$('#gallery-images-container').append(newItem);

			// Show all remove buttons when we have more than one image
			$('.gallery-remove-image').show();
		});

		// Remove image fields
		$('#gallery-images-container').on('click', '.gallery-remove-image', function() {
			$(this).closest('.gallery-image-item').remove();

			// Hide the remove button on the last remaining item
			if ($('.gallery-image-item').length <= 1) {
				$('.gallery-remove-image').hide();
			}
		});

		// Handle form submission to collect all image data
		$('form').on('submit', function(e) {
			let images = [];

			// Collect data from all image items
			$('.gallery-image-item').each(function() {
				const url = $(this).find('.gallery-image-url').val().trim();
				// Only add images with a URL
				if (url) {
					images.push({
						url: url,
						caption: $(this).find('.gallery-image-caption').val().trim()
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