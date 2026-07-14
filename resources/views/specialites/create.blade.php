@extends('layouts.app')

@section('title', 'Ajouter une spécialité')
@section('page-title', 'Ajouter une spécialité / un département')

@section('content')
<div class="row justify-content-center"><div class="col-lg-7"><div class="card">
    <div class="card-header"><i class="fas fa-sitemap me-2"></i>Nouvelle spécialité / Nouveau département</div>
    <div class="card-body"><form method="POST" action="{{ route('specialites.store') }}">@csrf @include('specialites._form')</form></div>
</div></div></div>
@endsection
