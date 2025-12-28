@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3><i class="fas fa-file-import me-2"></i>Importer des Crédits</h3>
                        <p class="text-muted mb-0">Importer depuis un fichier CSV</p>
                    </div>
                    <a href="{{ route('credits.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6 class="mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Erreurs:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Instructions --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Instructions d'importation</h5>
                    </div>
                    <div class="card-body">
                        <ol class="mb-0">
                            <li class="mb-2">
                                <strong>Remplissez les données:</strong> Ouvrez le fichier et saisissez vos crédits
                            </li>
                            <li class="mb-2">
                                <strong>Enregistrez le fichier:</strong> Format CSV uniquement
                            </li>
                            <li class="mb-0">
                                <strong>Importez:</strong> Utilisez le formulaire ci-dessous
                            </li>
                        </ol>

                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Note:</strong> Colonnes requises: Nom Client, Montant Total.
                            Les autres colonnes sont optionnelles.
                        </div>
                    </div>
                </div>

                {{-- Formulaire d'import --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('credits.import') }}" method="POST" enctype="multipart/form-data"
                            id="importForm">
                            @csrf

                            {{-- Zone de dépôt --}}
                            <div class="upload-area mb-4" id="uploadArea">
                                <input type="file" name="file" id="fileInput" accept=".csv,.txt" hidden required>

                                <div class="text-center py-5" id="uploadPrompt">
                                    <i class="fas fa-cloud-upload-alt fa-4x text-primary mb-3"></i>
                                    <h5>Glissez-déposez votre fichier ici</h5>
                                    <p class="text-muted mb-3">ou</p>
                                    <button type="button" class="btn btn-primary"
                                        onclick="document.getElementById('fileInput').click()">
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

                            {{-- Actions --}}
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill" id="submitBtn" disabled>
                                    <i class="fas fa-upload me-2"></i>
                                    Importer les données
                                </button>
                            </div>
                            <a href="{{ route('credits.template') }}" class="btn btn-secondary"
                                title="Télécharger le modèle CSV">
                                <i class="fas fa-download"></i>
                                Modèle
                            </a>
                        </form>
                    </div>
                </div>

                {{-- Conseils --}}
                <div class="card border-0 bg-light mt-4">
                    <div class="card-body">
                        <h6 class="mb-3"><i class="fas fa-lightbulb me-2 text-warning"></i>Conseils importants:</h6>
                        <ul class="small mb-0">
                            <li>Respectez l'ordre des colonnes du modèle</li>
                            <li>Les montants doivent être des nombres (sans devise)</li>
                            <li>Si le client existe déjà, un nouveau crédit sera ajouté</li>
                            <li>Vous pouvez laisser vides les champs optionnels</li>
                        </ul>
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
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const submitBtn = document.getElementById('submitBtn');

        // Click sur zone
        uploadArea.addEventListener('click', () => {
            if (!fileInput.files.length) {
                fileInput.click();
            }
        });

        // Sélection de fichier
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                showFileInfo(e.target.files[0]);
            }
        });

        // Drag & Drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');

            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                showFileInfo(e.dataTransfer.files[0]);
            }
        });

        function showFileInfo(file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);

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

        // Confirmation
        document.getElementById('importForm').addEventListener('submit', (e) => {
            if (!confirm('Êtes-vous sûr de vouloir importer ce fichier?')) {
                e.preventDefault();
            } else {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Importation en cours...';
                submitBtn.disabled = true;
            }
        });
    </script>
@endsection
