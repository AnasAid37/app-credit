@extends('layouts.app')

@section('title', 'Importer des Produits')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3><i class="fas fa-file-import me-2"></i>Importer des Produits</h3>
                    <p class="text-muted mb-0">Importer depuis un fichier CSV</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Instructions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Instructions</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2"><strong>Téléchargez le modèle</strong> CSV ci-dessous</li>
                        <li class="mb-2"><strong>Remplissez</strong> les données des produits</li>
                        <li class="mb-2"><strong>Enregistrez</strong> au format CSV</li>
                        <li class="mb-0"><strong>Importez</strong> le fichier</li>
                    </ol>

                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Les produits existants (même prix + taille) seront mis à jour automatiquement.
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('products.import.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="upload-area mb-4" id="uploadArea">
                            <input type="file" name="file" id="fileInput" accept=".csv" hidden required>
                            
                            <div class="text-center py-5" id="uploadPrompt">
                                <i class="fas fa-cloud-upload-alt fa-4x text-primary mb-3"></i>
                                <h5>Glissez-déposez votre fichier CSV</h5>
                                <p class="text-muted mb-3">ou</p>
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                    <i class="fas fa-folder-open me-2"></i>
                                    Choisir un fichier
                                </button>
                                <p class="text-muted small mt-3 mb-0">
                                    Format: CSV - Taille max: 5 Mo
                                </p>
                            </div>

                            <div class="text-center py-4 d-none" id="fileInfo">
                                <i class="fas fa-file-csv fa-3x text-success mb-3"></i>
                                <h6 class="mb-1" id="fileName"></h6>
                                <p class="text-muted small mb-3" id="fileSize"></p>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFile()">
                                    <i class="fas fa-times me-1"></i>
                                    Supprimer
                                </button>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-fill" id="submitBtn" disabled>
                                <i class="fas fa-upload me-2"></i>
                                Importer
                            </button>
                            <a href="{{ route('products.template') }}" class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i>
                                Télécharger Modèle
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.upload-area {
    border: 3px dashed #dee2e6;
    border-radius: 10px;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s;
}
.upload-area:hover {
    border-color: #0d6efd;
    background: #e7f1ff;
}
.upload-area.dragover {
    border-color: #0d6efd;
    background: #cfe2ff;
}
</style>

<script>
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('fileInput');
const uploadPrompt = document.getElementById('uploadPrompt');
const fileInfo = document.getElementById('fileInfo');
const submitBtn = document.getElementById('submitBtn');

uploadArea.addEventListener('click', () => {
    if (!fileInput.files.length) fileInput.click();
});

fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) showFileInfo(e.target.files[0]);
});

['dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, e => e.preventDefault());
});

uploadArea.addEventListener('dragover', () => uploadArea.classList.add('dragover'));
uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
uploadArea.addEventListener('drop', (e) => {
    uploadArea.classList.remove('dragover');
    if (e.dataTransfer.files.length > 0) {
        fileInput.files = e.dataTransfer.files;
        showFileInfo(e.dataTransfer.files[0]);
    }
});

function showFileInfo(file) {
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = formatFileSize(file.size);
    uploadPrompt.classList.add('d-none');
    fileInfo.classList.remove('d-none');
    submitBtn.disabled = false;
}

function clearFile() {
    fileInput.value = '';
    uploadPrompt.classList.remove('d-none');
    fileInfo.classList.add('d-none');
    submitBtn.disabled = true;
}

function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}
</script>
@endsection