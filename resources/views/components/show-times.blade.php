@props(['times' => []])

<div class="show-times">
    @if(count($times) > 0)
        @foreach($times as $time)
            <span class="badge bg-primary me-1 mb-1">{{ $time }}</span>
        @endforeach
    @else
        <span class="text-muted">No show times available</span>
    @endif
</div>
