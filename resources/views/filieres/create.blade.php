@extends('layouts.app')

@section('title', 'Ajouter une filière')
@section('page-title', 'Ajouter une filière')

@section('content')
<div class="row justify-content-center"><div class="col-lg-7"><div class="card">
    <div class="card-header"><i class="fas fa-graduation-cap me-2"></i>Nouvelle filière</div>
    <div class="card-body"><form method="POST" action="{{ route('filieres.store') }}">@csrf @include('filieres._form')</form></div>
</div></div></div>
@endsection
