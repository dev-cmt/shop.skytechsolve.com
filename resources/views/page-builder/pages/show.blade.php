@extends('page-builder.layouts.app')

@section('content')

    @foreach($page->activeSections as $section)
        @php
            $sectionView = 'page-builder.sections.' . $section->type;
        @endphp

        @if(view()->exists($sectionView))
            @include($sectionView, [
                'content' => $section->content,
                'settings' => $section->settings,
                'section' => $section
            ])
        @else
            <!-- Fallback for missing sections -->
            <div class="alert alert-warning">
                Section type "{{ $section->type }}" not found.
            </div>
        @endif
    @endforeach

    @if($page->activeSections->isEmpty())
        <div class="container text-center py-5">
            <div class="empty-state">
                <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                <h3>No Content Available</h3>
                <p class="text-muted">This page doesn't have any sections yet.</p>
                @auth
                    <a href="{{ route('page.builder', $page->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Sections
                    </a>
                @endauth
            </div>
        </div>
    @endif
@endsection
