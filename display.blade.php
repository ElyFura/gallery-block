@once
    @push('linkstack-head')
        <link rel="stylesheet" href="{{block_asset('assets/style.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    @endpush
@endonce

<div class="ls-gallery">
    @if(isset($link->show_title) && $link->show_title == "1" && !empty(trim($link->title ?? '')))
        <h3 class="ls-gallery-title">{{ $link->title }}</h3>
    @endif

    @php
        // Bestimme die CSS-Klasse für die Anzahl der Spalten
        $columnClass = '';
        if(isset($link->columns) && $link->columns !== 'auto') {
            $columnClass = 'ls-gallery-grid-' . $link->columns;
        }
    @endphp

    <div class="ls-gallery-container {{ $columnClass }}">
        @php
            $images = json_decode($link->images, true) ?? [];
        @endphp

        @foreach($images as $index => $image)
            <div class="ls-gallery-item">
                <a href="{{ $image['url'] }}" data-lightbox="gallery-{{ $link->id }}"
                   data-title="{{ $image['caption'] ?? '' }}">
                    <img src="{{ $image['url'] }}" alt="{{ $image['caption'] ?? 'Gallery image ' . ($index + 1) }}"
                         class="ls-gallery-image" loading="lazy">
                    @if(isset($image['caption']) && !empty($image['caption']))
                        <div class="ls-gallery-caption">{{ $image['caption'] }}</div>
                    @endif
                </a>
            </div>
        @endforeach
    </div>
</div>

@once
    @push('linkstack-body-end')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
        <script src="{{block_asset('assets/script.js')}}"></script>
    @endpush
@endonce