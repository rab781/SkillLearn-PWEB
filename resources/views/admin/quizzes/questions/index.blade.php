@extends('layouts.admin')

@section('title', 'Manage Quiz Questions - ' . $quiz->judul_quiz)

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
<style>
/* Professional Quiz Question Management Styling */
:root {
    --primary-color: #4f46e5;
    --primary-dark: #3730a3;
    --secondary-color: #64748b;
    --success-color: #059669;
    --warning-color: #d97706;
    --danger-color: #dc2626;
    --info-color: #0891b2;
    --light-bg: #f8fafc;
    --white: #ffffff;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 01), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --radius-sm: 6px;
    --radius-md: 8px;
    --radius-lg: 12px;
    --radius-xl: 16px;
}

/* Reset and base styles */
.quiz-question-manager {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-color: var(--gray-50);
    min-height: 100vh;
}

.quiz-question-manager * {
    box-sizing: border-box;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border-radius: var(--radius-xl);
    color: white;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-lg);
}

.page-header .breadcrumb {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: var(--radius-md);
    padding: 0.75rem 1rem;
    margin-bottom: 1.5rem;
}

.page-header .breadcrumb-item a {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    font-weight: 500;
}

.page-header .breadcrumb-item.active {
    color: rgba(255, 255, 255, 0.8);
    font-weight: 400;
}

.page-header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title-section {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-icon {
    background: rgba(255, 255, 255, 0.2);
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    backdrop-filter: blur(10px);
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
    color: white;
}

.page-subtitle {
    font-size: 1rem;
    margin: 0;
    opacity: 0.9;
    font-weight: 400;
}

.page-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

/* Buttons - Complete and Clean Definition */
.btn-custom {
    display: inline-flex !important;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border: 2px solid transparent;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    white-space: nowrap;
    line-height: 1.5;
    text-align: center;
    vertical-align: middle;
    position: relative;
    overflow: hidden;
}

.btn-custom:hover {
    transform: translateY(-1px);
    text-decoration: none;
}

.btn-custom:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
    color: white !important;
    border-color: var(--primary-color);
    box-shadow: var(--shadow-sm);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark) 0%, #312e81 100%) !important;
    box-shadow: var(--shadow-md);
    color: white !important;
    border-color: var(--primary-dark);
}

.btn-success {
    background: linear-gradient(135deg, var(--success-color) 0%, #047857 100%) !important;
    color: white !important;
    border-color: var(--success-color);
    box-shadow: var(--shadow-sm);
}

.btn-success:hover {
    background: linear-gradient(135deg, #047857 0%, #065f46 100%) !important;
    box-shadow: var(--shadow-md);
    color: white !important;
    border-color: #047857;
}

.btn-warning {
    background: linear-gradient(135deg, var(--warning-color) 0%, #b45309 100%) !important;
    color: white !important;
    border-color: var(--warning-color);
    box-shadow: var(--shadow-sm);
}

.btn-warning:hover {
    background: linear-gradient(135deg, #b45309 0%, #92400e 100%) !important;
    box-shadow: var(--shadow-md);
    color: white !important;
    border-color: #b45309;
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger-color) 0%, #b91c1c 100%) !important;
    color: white !important;
    border-color: var(--danger-color);
    box-shadow: var(--shadow-sm);
}

.btn-danger:hover {
    background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%) !important;
    box-shadow: var(--shadow-md);
    color: white !important;
    border-color: #b91c1c;
}

.btn-info {
    background: linear-gradient(135deg, var(--info-color) 0%, #0e7490 100%) !important;
    color: white !important;
    border-color: var(--info-color);
    box-shadow: var(--shadow-sm);
}

.btn-info:hover {
    background: linear-gradient(135deg, #0e7490 0%, #0c5c72 100%) !important;
    box-shadow: var(--shadow-md);
    color: white !important;
    border-color: #0e7490;
}

.btn-secondary {
    background: linear-gradient(135deg, var(--gray-500) 0%, var(--gray-600) 100%) !important;
    color: white !important;
    border-color: var(--gray-500);
    box-shadow: var(--shadow-sm);
}

.btn-secondary:hover {
    background: linear-gradient(135deg, var(--gray-600) 0%, var(--gray-700) 100%) !important;
    box-shadow: var(--shadow-md);
    color: white !important;
    border-color: var(--gray-600);
}

.btn-info:hover {
    background: #0e7490;
    box-shadow: var(--shadow-md);
    color: white;
}

.btn-secondary {
    background: var(--gray-500);
    color: white;
    box-shadow: var(--shadow-sm);
}

.btn-secondary:hover {
    background: var(--gray-600);
    box-shadow: var(--shadow-md);
    color: white;
}

.btn-sm {
    padding: 0.5rem 0.875rem;
    font-size: 0.8125rem;
}

.btn-lg {
    padding: 1rem 1.75rem;
    font-size: 1rem;
    font-weight: 700;
}

/* Specific button fixes for visibility */
button[onclick="addAnswerOption()"] {
    background: linear-gradient(135deg, var(--success-color) 0%, #047857 100%) !important;
    color: white !important;
    border: 2px solid var(--success-color) !important;
    min-height: 40px !important;
    min-width: 120px !important;
}

button[onclick="saveQuestion()"] {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
    color: white !important;
    border: 2px solid var(--primary-color) !important;
    min-height: 40px !important;
    min-width: 120px !important;
}

button[onclick="cancelEditQuestion()"] {
    background: linear-gradient(135deg, var(--gray-500) 0%, var(--gray-600) 100%) !important;
    color: white !important;
    border: 2px solid var(--gray-500) !important;
    min-height: 40px !important;
    min-width: 120px !important;
}

button[onclick="showQuestionBuilder()"] {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
    color: white !important;
    border: 2px solid var(--primary-color) !important;
    min-height: 45px !important;
    min-width: 200px !important;
    font-size: 1rem !important;
}

/* Cards */
.card-custom {
    background: var(--white);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: all 0.2s ease;
}

.card-custom:hover {
    box-shadow: var(--shadow-md);
}

.card-header-custom {
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    padding: 1.25rem 1.5rem;
}

.card-body-custom {
    padding: 1.5rem;
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Question Builder Form */
.question-builder {
    background: var(--white);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    border: 2px dashed var(--gray-300);
    margin-bottom: 2rem;
    transition: all 0.3s ease;
}

.question-builder:hover {
    border-color: var(--primary-color);
    box-shadow: var(--shadow-md);
}

.question-builder.active {
    border-color: var(--primary-color);
    border-style: solid;
    box-shadow: var(--shadow-lg);
}

.form-section {
    padding: 2rem;
    border-bottom: 1px solid var(--gray-100);
}

.form-section:last-child {
    border-bottom: none;
}

.form-section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Form Controls */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-control-custom {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-md);
    font-size: 0.9375rem;
    background: var(--white);
    transition: all 0.2s ease;
    font-family: inherit;
}

.form-control-custom:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-control-custom::placeholder {
    color: var(--gray-400);
}

/* Answer Options */
.answer-options-container {
    margin-bottom: 1.5rem;
  }

.answer-option-item {
    background: var(--gray-50);
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-lg);
    padding: 1rem;
    margin-bottom: 0.75rem;
    transition: all 0.2s ease;
}

.answer-option-item:hover {
    border-color: var(--primary-color);
    background: var(--white);
    box-shadow: var(--shadow-sm);
}

.answer-option-item.correct {
    background: #ecfdf5;
    border-color: var(--success-color);
}

.answer-option-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.answer-label {
    background: var(--primary-color);
    color: white;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.answer-label.correct {
    background: var(--success-color);
}

.correct-answer-toggle {
    margin-left: auto;
}

/* Custom Radio Button Styling */
.form-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
    padding: 0;
}

.form-check-input {
    width: 20px !important;
    height: 20px !important;
    margin: 0 !important;
    background-color: white !important;
    border: 3px solid var(--primary-color) !important;
    border-radius: 50% !important;
    cursor: pointer !important;
    position: relative !important;
}

.form-check-input:checked {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2) !important;
}

.form-check-input:checked::before {
    content: '✓' !important;
    color: white !important;
    font-weight: bold !important;
    font-size: 12px !important;
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    line-height: 1 !important;
}

.form-check-label {
    color: var(--primary-color) !important;
    font-weight: 600 !important;
    font-size: 0.875rem !important;
    cursor: pointer !important;
    margin: 0 !important;
}

/* Answer option styling */
.answer-option-item {
    background: var(--gray-50);
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-lg);
    padding: 1rem;
    margin-bottom: 0.75rem;
    transition: all 0.2s ease;
}

.answer-option-item:hover {
    border-color: var(--primary-color);
    background: var(--white);
    box-shadow: var(--shadow-sm);
}

.answer-input {
    width: 100% !important;
    padding: 0.75rem 1rem !important;
    border: 2px solid var(--gray-300) !important;
    border-radius: var(--radius-md) !important;
    font-size: 0.9375rem !important;
    background: var(--white) !important;
    transition: all 0.2s ease !important;
}

.answer-input:focus {
    outline: none !important;
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
}

.answer-input::placeholder {
    color: var(--gray-400) !important;
}

/* Button Improvements for Better Visibility */
.btn-custom {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    font-weight: 600;
    letter-spacing: 0.025em;
    position: relative;
    overflow: hidden;
}

.btn-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn-custom:hover::before {
    left: 100%;
}

.btn-custom:active {
    transform: translateY(1px);
}

/* Specific button type improvements */
.btn-secondary {
    background: linear-gradient(135deg, var(--gray-500) 0%, var(--gray-600) 100%);
    border: 1px solid var(--gray-400);
}

/* Question Cards */
.question-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    margin-bottom: 1.5rem;
    overflow: hidden;
    transition: all 0.2s ease;
}

.question-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.question-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 1.25rem 1.5rem;
    position: relative;
}

.question-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.question-number-badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-md);
    font-size: 0.8125rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.drag-handle {
    cursor: grab;
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.25rem;
    padding: 0.5rem;
    border-radius: var(--radius-sm);
    transition: all 0.2s ease;
}

.drag-handle:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.drag-handle:active {
    cursor: grabbing;
}

.question-info {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.question-badge {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    padding: 0.25rem 0.625rem;
    border-radius: var(--radius-md);
    font-size: 0.75rem;
    font-weight: 500;
    backdrop-filter: blur(10px);
}

.question-content {
    padding: 1.5rem;
}

.question-text {
    font-size: 1.0625rem;
    font-weight: 600;
    color: var(--gray-900);
    line-height: 1.6;
    margin-bottom: 1.25rem;
}

.answer-list {
    display: grid;
    gap: 0.75rem;
}

.answer-item {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    padding: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.2s ease;
}

.answer-item:hover {
    background: var(--white);
    border-color: var(--gray-300);
}

.answer-item.correct {
    background: #ecfdf5;
    border-color: var(--success-color);
    position: relative;
}

.answer-item.correct::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--success-color);
    border-radius: var(--radius-sm) 0 0 var(--radius-sm);
}

.answer-indicator {
    width: 1.75rem;
    height: 1.75rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8125rem;
    font-weight: 600;
    flex-shrink: 0;
    background: var(--gray-300);
    color: var(--gray-600);
}

.answer-indicator.correct {
    background: var(--success-color);
    color: white;
}

.answer-text {
    flex: 1;
    color: var(--gray-700);
    font-size: 0.9375rem;
}

.question-actions {
    padding: 1rem 1.5rem;
    background: var(--gray-50);
    border-top: 1px solid var(--gray-200);
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--white);
    border-radius: var(--radius-xl);
    border: 2px dashed var(--gray-300);
}

.empty-icon {
    font-size: 4rem;
    color: var(--gray-400);
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.75rem;
}

.empty-description {
    color: var(--gray-500);
    margin-bottom: 2rem;
    max-width: 24rem;
    margin-left: auto;
    margin-right: auto;
}

/* Alert Messages */
.alert-custom {
    padding: 1rem 1.25rem;
    border-radius: var(--radius-md);
    margin-bottom: 1.5rem;
    border: 1px solid transparent;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-danger {
    background: #fef2f2;
    border-color: #fecaca;
    color: #991b1b;
}

.alert-success {
    background: #f0fdf4;
    border-color: #bbf7d0;
    color: #166534;
}

/* Utility Classes */
.d-flex { display: flex; }
.align-items-center { align-items: center; }
.justify-content-between { justify-content: space-between; }
.gap-1 { gap: 0.25rem; }
.gap-2 { gap: 0.5rem; }
.gap-3 { gap: 0.75rem; }
.gap-4 { gap: 1rem; }
.mb-0 { margin-bottom: 0; }
.mb-2 { margin-bottom: 0.5rem; }
.mb-3 { margin-bottom: 0.75rem; }
.mb-4 { margin-bottom: 1rem; }
.me-2 { margin-right: 0.5rem; }
.me-3 { margin-right: 0.75rem; }
.text-muted { color: var(--gray-500); }
.fw-bold { font-weight: 700; }

/* Grid System */
.row {
    display: flex;
    flex-wrap: wrap;
    margin-left: -0.75rem;
    margin-right: -0.75rem;
}

.col, .col-12, .col-md-4, .col-md-6, .col-md-8 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    width: 100%;
}

@media (min-width: 768px) {
    .col-md-4 { width: 33.333333%; }
    .col-md-6 { width: 50%; }
    .col-md-8 { width: 66.666667%; }
}

/* Sortable */
.sortable-placeholder {
    background: var(--gray-100);
    border: 2px dashed var(--primary-color);
    border-radius: var(--radius-xl);
    height: 4rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-500);
    font-style: italic;
    margin-bottom: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem;
    }

    .page-header-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .page-actions {
        width: 100%;
        justify-content: flex-start;
    }

    .btn-custom {
        flex: 1;
        justify-content: center;
    }

    .question-actions {
        flex-direction: column;
    }

    .form-section {
        padding: 1.5rem;
    }
}

</style>
@endpush

@section('content')
<div class="quiz-question-manager">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="quiz-id" content="{{ $quiz->quiz_id }}">
    <meta name="quiz-title" content="{{ $quiz->judul_quiz }}">

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.courses.index') }}">
                            <i class="fas fa-graduation-cap me-2"></i>Courses
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.courses.show', $quiz->course->course_id) }}">
                            {{ Str::limit($quiz->course->nama_course, 25) }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.courses.quizzes', $quiz->course->course_id) }}">
                            Quiz Management
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Question Manager</li>
                </ol>
            </nav>

            <div class="page-header-content">
                <div class="page-title-section">
                    <div class="page-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div>
                        <h1 class="page-title">Quiz Question Manager</h1>
                        <p class="page-subtitle">
                            Kelola pertanyaan untuk: <strong>{{ $quiz->judul_quiz }}</strong>
                        </p>
                    </div>
                </div>

                <div class="page-actions">
                    <button type="button" class="btn-custom btn-info" onclick="previewQuiz()">
                        <i class="fas fa-eye"></i>
                        <span>Preview Quiz</span>
                    </button>
                    <a href="{{ route('admin.courses.quizzes', $quiz->course->course_id) }}" class="btn-custom btn-warning">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert-custom alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div id="error-messages" class="alert-custom alert-danger" style="display: none;">
            <i class="fas fa-exclamation-circle"></i>
            <span id="error-text"></span>
        </div>

        <!-- Add Question Button -->
        <div class="mb-4">
            <button type="button" class="btn-custom btn-primary btn-lg" onclick="showQuestionBuilder()">
                <i class="fas fa-plus"></i>
                <span>Tambah Pertanyaan Baru</span>
            </button>
        </div>

        <!-- Question Builder Form -->
        <div id="questionBuilderCard" class="question-builder" style="display: none;">
            <div class="form-section">
                <h2 class="form-section-title">
                    <i class="fas fa-edit text-primary"></i>
                    Form Pertanyaan
                </h2>

                <form id="questionForm">
                    <input type="hidden" id="questionId" name="question_id">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label">Pertanyaan</label>
                                <textarea id="questionText" name="pertanyaan" class="form-control-custom" rows="3" placeholder="Masukkan pertanyaan quiz..." required></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Bobot Nilai</label>
                                <input type="number" id="questionWeight" name="bobot_nilai" class="form-control-custom" min="1" max="100" value="10" placeholder="10" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-list text-success"></i>
                    Pilihan Jawaban
                </h3>

                <div id="answerOptions" class="answer-options-container">
                    <!-- Answer options will be added here dynamically -->
                </div>

                <div class="d-flex justify-content-center">
                    <button type="button" class="btn-custom btn-success btn-sm" onclick="addAnswerOption()">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Pilihan</span>
                    </button>
                </div>
            </div>

            <div class="form-section">
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn-custom btn-primary" onclick="saveQuestion()">
                        <i class="fas fa-save"></i>
                        <span>Simpan Pertanyaan</span>
                    </button>
                    <button type="button" class="btn-custom btn-secondary" onclick="cancelEditQuestion()">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Questions List -->
        @if($questions->count() > 0)
            <div class="card-custom">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            <i class="fas fa-list text-primary"></i>
                            Daftar Pertanyaan ({{ $questions->count() }})
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted">
                                <i class="fas fa-arrows-alt me-2"></i>Drag untuk mengubah urutan
                            </span>
                            <span class="btn-custom btn-primary btn-sm">
                                Total Bobot: {{ $questions->sum('bobot_nilai') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body-custom" style="padding: 0;">
                    <div id="sortable-questions">
                        @foreach($questions as $index => $question)
                            <div class="question-card" data-question-id="{{ $question->question_id }}">
                                <div class="question-header">
                                    <div class="question-meta">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="drag-handle handle">
                                                <i class="fas fa-grip-vertical"></i>
                                            </div>
                                            <div>
                                                <div class="question-number-badge">
                                                    Pertanyaan {{ $index + 1 }}
                                                </div>
                                                <div class="question-info">
                                                    <span class="question-badge">
                                                        <i class="fas fa-weight-hanging me-1"></i>{{ $question->bobot_nilai }} poin
                                                    </span>
                                                    <span class="question-badge">
                                                        <i class="fas fa-list me-1"></i>{{ count(json_decode($question->pilihan_jawaban, true) ?? []) }} pilihan
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn-custom btn-warning btn-sm" onclick="editQuestion({{ $question->question_id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn-custom btn-danger btn-sm" onclick="deleteQuestion({{ $question->question_id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="question-content">
                                    <div class="question-text">
                                        {{ $question->pertanyaan }}
                                    </div>

                                    <div class="answer-list">
                                        @php
                                            $options = is_array($question->pilihan_jawaban)
                                                ? $question->pilihan_jawaban
                                                : json_decode($question->pilihan_jawaban, true) ?? [];
                                            $correctAnswer = $question->jawaban_benar;
                                        @endphp

                                        @foreach($options as $key => $option)
                                            @php
                                                $optionLabel = is_numeric($key) ? chr(65 + $key) : $key;
                                                $isCorrect = $optionLabel === $correctAnswer;
                                            @endphp
                                            <div class="answer-item {{ $isCorrect ? 'correct' : '' }}">
                                                <div class="answer-indicator {{ $isCorrect ? 'correct' : '' }}">
                                                    {{ $optionLabel }}
                                                </div>
                                                <div class="answer-text">{{ $option }}</div>
                                                @if($isCorrect)
                                                    <i class="fas fa-check-circle text-success"></i>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h3 class="empty-title">Belum Ada Pertanyaan</h3>
                <p class="empty-description">
                    Mulai dengan menambahkan pertanyaan pertama untuk quiz ini. Buat pembelajaran yang lebih interaktif dan menarik!
                </p>
                <button type="button" class="btn-custom btn-primary btn-lg" onclick="showQuestionBuilder()">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Pertanyaan Pertama</span>
                </button>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/admin/quiz-questions.js') }}?v={{ time() }}"></script>
@endpush
