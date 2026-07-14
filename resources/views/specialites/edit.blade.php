@extends('layouts.app')

@section('title', 'Modifier une spécialité')
@section('page-title', 'Modifier une spécialité / un département')

@section('content')
<div class="row justify-content-center"><div class="col-lg-7"><div class="card">
    <div class="card-header"><i class="fas fa-sitemap me-2"></i>Modifier la spécialité / le département</div>
    <div class="card-body"><form method="POST" action="{{ route('specialites.update', $specialite) }}">@csrf @method('PATCH') @include('specialites._form')</form></div>
</div></div></div>
@endsection
