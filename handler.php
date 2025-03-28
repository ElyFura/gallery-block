<?php
// Wichtig: Es darf keine Namespace-Deklaration sein!
// Die Funktion muss im globalen Namespace sein, nicht im App\Http\Controllers Namespace

// Prüfe, ob die Funktion bereits existiert, um Fehler bei mehreren Aufrufen zu vermeiden
if (!function_exists('handleLinkType')) {
	/**
	 * Handling der Formularverarbeitung für den Gallery Block
	 */
	function handleLinkType($request, $linkType) {
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
				'required',
				'string',
			],
			'images' => [
				'required',
				'string',
				'max:50000',
			],
		];

		// Get the title from the request, with fallback to empty string
		$title = $request->has('title') ? $request->title : '';

		// Get the show_title flag
		$showTitle = $request->has('show_title') ? "1" : "0";

		// Get the columns setting
		$columns = $request->columns ?? 'auto';

		// Prepare the link data
		$linkData = [
			'title' => $title,
			'show_title' => $showTitle,
			'columns' => $columns,
			'images' => $request->images,
			'custom_icon' => 'fa-solid fa-images',
		];

		return ['rules' => $rules, 'linkData' => $linkData];
	}
}