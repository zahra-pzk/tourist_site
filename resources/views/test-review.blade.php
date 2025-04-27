<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@extends('layouts.app')

@section('title', 'تست مودال ثبت نظر')

@section('content')
  <div class="container mt-5">
    <h1 class="mb-4">صفحه تست مودال ثبت نظر و تجربه</h1>
    <button id="openReviewModal" class="btn btn-primary mb-3">
      ثبت نظر و تجربه
    </button>
    @include('components.review-modal')
  </div>
@endsection
