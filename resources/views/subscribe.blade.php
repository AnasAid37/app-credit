@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-lock fa-4x text-warning"></i>
                    </div>
                    
                    <h3 class="mb-3">🚫 Compte non activé</h3>
                    
                    <p class="text-muted mb-4">
                        Votre compte est en cours de vérification. Pour l'activation, veuillez nous contacter via :
                    </p>

                    <div class="alert alert-info">
                        <i class="fab fa-whatsapp fa-2x text-success"></i>
                        <h5 class="mt-2">06XXXXXXXX</h5>
                    </div>

                    <a href="https://wa.me/212605816821" 
                       class="btn btn-success btn-lg mb-3" target="_blank">
                        <i class="fab fa-whatsapp me-2"></i>
                        Contacter via WhatsApp
                    </a>

                    <hr>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection