<?php
// Wichtig: Es darf keine Namespace-Deklaration sein!
// Die Funktion muss im globalen Namespace sein, nicht im App\Http\Controllers Namespace

// Prüfe, ob die Funktion bereits existiert, um Fehler bei mehreren Aufrufen zu vermeiden
if (!function_exists('handleLinkType')) {
	/**
	 * Handling der Formularverarbeitung für den Gallery Block
	 */
	function handleLinkType($request, $linkType): array
	{
		// Define validation rules
		$rules = [
			'title' => [
				'nullable',
				'string',
				'max:255',
			],
			'show_title' => [
				'sometimes',
				'boolean',
			],
			'columns' => [
				'nullable',
				'string',
			],
			'layout_type' => [
				'nullable',
				'string',
			],
			'image_shape' => [
				'nullable',
				'string',
			],
			'lightbox_effect' => [
				'nullable',
				'string',
			],
			'lazy_load' => [
				'sometimes',
				'boolean',
			],
			'images' => [
				'required',
				'string',
				'max:50000',
			],
		];

		// Get the title from the request, with fallback to empty string
		$title = $request->has('title') ? $request->title : '';

		// Get boolean flags
		$showTitle = $request->has('show_title') ? "1" : "0";
		$lazyLoad = $request->has('lazy_load') ? "1" : "0";

		// Get selection options with defaults
		$layoutType = $request->layout_type ?? 'grid';
		$columns = $request->columns ?? 'auto';
		$imageShape = $request->image_shape ?? 'square';
		$lightboxEffect = $request->lightbox_effect ?? 'fade';

		// Prepare the link data
		$linkData = [
			'title' => $title,
			'show_title' => $showTitle,
			'layout_type' => $layoutType,
			'columns' => $columns,
			'image_shape' => $imageShape,
			'lightbox_effect' => $lightboxEffect,
			'lazy_load' => $lazyLoad,
			'images' => $request->images,
			'custom_icon' => 'fa-solid fa-images',
		];

		return ['rules' => $rules, 'linkData' => $linkData];
	}
}