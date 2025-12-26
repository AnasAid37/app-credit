<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Credit;
use App\Models\Sortie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        // إحصائيات المستخدم الحقيقية
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $stats = [
            'sorties_this_month' => Sortie::where('user_id', $user->id)
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),
            
            'products_in_stock' => Product::where('quantite', '>', 0)->count(),
            
            'active_credits' => Credit::where('user_id', $user->id)
                ->whereColumn('paid_amount', '<', 'amount')
                ->count(),
            
            'active_alerts' => Product::where('quantite', '<=', 'seuil_alerte')->count(),
        ];

        // آخر الأنشطة
        $activities = $user->activities()->take(10)->get();

        // حساب قوة كلمة المرور
        $passwordStrength = $this->calculatePasswordStrength($user);

        return view('profile.show', compact('user', 'profile', 'stats', 'activities', 'passwordStrength'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'departement' => 'nullable|string|max:100',
            'date_embauche' => 'nullable|date',
        ]);

        // تحديث User
        $user->update([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
        ]);

        // تحديث Profile
        $profile->update([
            'adresse' => $validated['adresse'] ?? $profile->adresse,
            'departement' => $validated['departement'] ?? $profile->departement,
            'date_embauche' => $validated['date_embauche'] ?? $profile->date_embauche,
        ]);

        // تسجيل النشاط
        $this->logActivity('profile_updated', 'Mise à jour des informations du profil');

        return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
            ],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        $user->profile->update([
            'password_changed_at' => now()
        ]);

        // تسجيل النشاط
        $this->logActivity('password_changed', 'Mot de passe modifié');

        return redirect()->route('profile.show')->with('success', 'Mot de passe changé avec succès!');
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $validated = $request->validate([
            'langue' => 'required|in:Français,Arabe,Anglais',
            'fuseau_horaire' => 'required|string',
            'notif_stock_faible' => 'boolean',
            'notif_commandes' => 'boolean',
            'notif_rapports' => 'boolean',
            'notif_promotions' => 'boolean',
        ]);

        $profile->update($validated);

        // تسجيل النشاط
        $this->logActivity('settings_updated', 'Paramètres du compte mis à jour');

        return redirect()->route('profile.show')->with('success', 'Paramètres mis à jour avec succès!');
    }

    private function logActivity($action, $description)
    {
        Auth::user()->activities()->create([
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    private function calculatePasswordStrength($user)
    {
        $profile = $user->profile;
        
        if (!$profile->password_changed_at) {
            return ['level' => 'medium', 'text' => 'Moyen', 'weeks' => 'Jamais'];
        }

        $weeksSinceChange = now()->diffInWeeks($profile->password_changed_at);
        
        if ($weeksSinceChange < 4) {
            return ['level' => 'strong', 'text' => 'Fort', 'weeks' => $weeksSinceChange];
        } elseif ($weeksSinceChange < 12) {
            return ['level' => 'medium', 'text' => 'Moyen', 'weeks' => $weeksSinceChange];
        } else {
            return ['level' => 'weak', 'text' => 'Faible', 'weeks' => $weeksSinceChange];
        }
    }
}
