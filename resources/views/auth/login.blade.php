{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - Gestion de Crédit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@tailwindcss/forms"></script>
    <script src="https://unpkg.com/framer-motion/dist/framer-motion.umd.js"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-900 bg-cover bg-center" 
      style="background-image: url('{{ asset('images/company-bg.png') }}');">
    <div class="bg-black bg-opacity-50 absolute inset-0"></div> {{-- طبقة شفافة فوق الخلفية --}}

    <div class="relative max-w-md w-full space-y-8 z-10">
        <!-- Logo + Title -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 flex items-center justify-center bg-blue-600 rounded-full shadow-lg animate-bounce">
                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 
                          00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 
                          4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="mt-6 text-3xl font-extrabold text-white drop-shadow-lg tracking-wide">
                Gestion de crédits pneumatique Aqali
            </h1>
            <p class="mt-2 text-sm text-gray-200 animate-pulse">
                Connectez-vous à votre compte administrateur
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/90 backdrop-blur-md py-8 px-6 shadow-xl rounded-2xl transform transition duration-500 hover:scale-[1.02]">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded animate-fade-in">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded animate-fade-in">
                    {{ session('error') }}
                </div>
            @endif

            <form class="space-y-6 animate-fade-in" action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Adresse email
                    </label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           value="{{ old('email') }}"
                           class="mt-1 block w-full px-3 py-2 border 
                           @error('email') border-red-300 @else border-gray-300 @enderror 
                           rounded-md shadow-sm placeholder-gray-400 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 
                           focus:border-blue-500 sm:text-sm transition duration-300"
                           placeholder="admin@example.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Mot de passe
                    </label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="current-password" 
                           required 
                           class="mt-1 block w-full px-3 py-2 border 
                           @error('password') border-red-300 @else border-gray-300 @enderror 
                           rounded-md shadow-sm placeholder-gray-400 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 
                           focus:border-blue-500 sm:text-sm transition duration-300"
                           placeholder="Votre mot de passe">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 
                            border border-transparent text-sm font-medium rounded-md 
                            text-white bg-gradient-to-r from-blue-600 to-blue-800 
                            hover:from-blue-700 hover:to-blue-900 
                            focus:outline-none focus:ring-2 focus:ring-offset-2 
                            focus:ring-blue-500 shadow-lg transition duration-300 transform hover:scale-105">
                        🚀 Se connecter
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center">
            <p class="text-xs text-gray-300">
                Système de gestion de crédit - Version 1.0
            </p>
        </div>
    </div>

    <!-- Animations -->
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.7s ease-out;
        }
    </style>
</body>
</html>
