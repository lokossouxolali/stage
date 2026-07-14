@extends('layouts.app')

@section('title', 'Modifier une filière')
@section('page-title', 'Modifier une filière')

@section('content')
<div class="row justify-content-center"><div class="col-lg-7"><div class="card">
    <div class="card-header"><i class="fas fa-graduation-cap me-2"></i>Modifier la filière</div>
    <div class="card-body"><form method="POST" action="{{ route('filieres.update', $filiere) }}">@csrf @method('PATCH') @include('filieres._form')</form></div>
</div></div></div>
@endsection
