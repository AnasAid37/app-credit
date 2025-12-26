@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <div class="error-page">
        <div class="error-icon mb-4">
            <i class="fas fa-lock fa-5x text-danger"></i>
        </div>
        <h1 class="display-4">403</h1>
        <h2>Accès refusé</h2>
        <p class="lead">Vous n'avez pas la permission d'accéder à cette page.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">
            Retour au Dashboard
        </a>
    </div>
</div>
@endsection