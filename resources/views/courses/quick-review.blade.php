@extends('layouts.app')

@section('title', $review->judul_review . ' - ' . $course->nama_course)

@section('content')
<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
            <li class="breadcrumb-item"><a href="{{ route('courses.show', $course->course_id) }}">{{ $course->nama_course }}</a></li>
            <li class="breadcrumb-item active">Quick Review</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Quick Review Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-lightbulb fs-4 me-3"></i>
                        <div>
                            <h5 class="mb-0">Quick Review #{{ $review->urutan_review }}</h5>
                            <small class="opacity-75">{{ $course->nama_course }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h2 class="card-title mb-4">{{ $review->judul_review }}</h2>
                    
                    <div class="review-content">
                        {!! nl2br(e($review->isi_review)) !!}
                    </div>

                    @if($review->tipe_review)
                        <div class="mt-4 p-3 bg-light rounded">
                            <small class="text-muted">
                                <i class="fas fa-tag"></i> 
                                Tipe: {{ ucfirst($review->tipe_review) }}
                            </small>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('courses.show', $course->course_id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Course
                        </a>
                        
                        <div class="text-muted small">
                            Quick Review untuk {{ $course->nama_course }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.review-content {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #2d3748;
}

.review-content p {
    margin-bottom: 1.2rem;
}
</style>
@endsection
