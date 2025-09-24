@extends('templates.app')

{{-- ngisi yield --}}
@section('content')
    <div class="container my-5">
        <h5 class="mb-5">Seluruh Film Sedang Tayang</h5>
        <div class="container d-flex flex-wrap gap-5 mt-4">
            @foreach ($movies as $key => $item)
                <div class="card" style="width: 18rem;">
                    <img src="{{ asset('storage/' . $item['poster']) }}" class="card-img-top" alt="{{ $item['title'] }}"
                        style="height: 350px; object-fit: cover;">
                    <div class="card-body bg-warning" style="padding: 0 !important; text-align: center;">
                        {{-- Karna default card text ad paddingnya, biar paddingnya yang dibaca dari style jadi dikasi !important (memprioritaskan style) --}}
                        <p class="card-text" style="padding: 0 !important; text-align: center; font-weight: bold;"><a
                                href="{{ route('schedules.detail') }}">BELI TIKET</a></p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
