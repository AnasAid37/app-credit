<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('is_admin', false)
            ->when($request->search, fn($q) => $q->where('nom', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(20);

        return view('admin.index', compact('users'));
    }

    public function activate(Request $request, User $user)
    {
        $request->validate([
            'subscription_type' => 'required|in:monthly,lifetime',
            'months' => 'required_if:subscription_type,monthly|integer|min:1',
        ]);

        $user->is_active = true;
        $user->subscription_type = $request->subscription_type;

        if ($request->subscription_type === 'monthly') {
            $user->subscription_expires_at = now()->addMonths($request->months);
        } else {
            $user->subscription_expires_at = null;
        }

        $user->save();

        return back()->with('success', "Compte de {$user->nom} activé avec succès");
    }

    public function deactivate(User $user)
    {
        $user->update(['is_active' => false]);
        return back()->with('success', "Compte de {$user->nom} désactivé");
    }

    public function extend(Request $request, User $user)
    {
        $request->validate(['months' => 'required|integer|min:1|max:12']);

        if ($user->subscription_type !== 'monthly') {
            return back()->with('error', 'Seuls les abonnements mensuels peuvent être prolongés');
        }

        if (!$user->subscription_expires_at || now()->greaterThan($user->subscription_expires_at)) {
            $user->subscription_expires_at = now()->addMonths($request->months);
        } else {
            $user->subscription_expires_at = $user->subscription_expires_at->addMonths($request->months);
        }

        $user->save();

        return back()->with('success', "Abonnement prolongé de {$request->months} mois");
    }
}