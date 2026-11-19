<?php

namespace App\Http\Controllers;

use App\Models\Locataire;
use App\Models\User; // 👈 Importation du modèle Locataire
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UserController extends Controller
{
    /**
     * Affiche la liste de tous les utilisateurs (réservé à l'administrateur).
     */
    public function index()
    {
        $this->authorizeAdministrator();

        $users = User::orderByRaw(
            'CASE WHEN role = ? AND role_reviewed_at IS NULL THEN 0 ELSE 1 END',
            [User::ROLE_TENANT],
        )
            ->latest()
            ->get();
        $pendingRoleCount = User::where('role', User::ROLE_TENANT)
            ->whereNull('role_reviewed_at')
            ->count();

        return view('users.index', compact('users', 'pendingRoleCount'));
    }

    /**
     * Affiche le formulaire de création d'un utilisateur.
     */
    public function create()
    {
        $this->authorizeAdministrator();

        return view('users.create');
    }

    /**
     * Enregistre un nouvel utilisateur dans la base de données.
     */
    public function store(Request $request)
    {
        $this->authorizeAdministrator();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
        ]);

        DB::transaction(function () use ($request): void {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'phone' => $request->phone ?? null,
                'role_reviewed_at' => now(),
            ]);

            if (in_array($user->role, [User::ROLE_CLIENT, User::ROLE_TENANT], true)) {
                Locataire::syncFromUser($user);
            }
        });

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Affiche le formulaire de modification d'un utilisateur.
     */
    public function edit(User $user)
    {
        $this->authorizeAdministrator();

        return view('users.edit', compact('user'));
    }

    /**
     * Met à jour les informations d'un utilisateur.
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeAdministrator();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
        ]);

        if ($user->is(auth()->user()) && $request->role !== User::ROLE_ADMIN) {
            return back()
                ->withErrors(['role' => 'Vous ne pouvez pas retirer votre propre rôle administrateur.'])
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone ?? $user->phone,
            'role_reviewed_at' => now(),
        ]);

        if (in_array($user->role, [User::ROLE_CLIENT, User::ROLE_TENANT], true)) {
            Locataire::syncFromUser($user);
        }

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprime un utilisateur de la base de données.
     */
    public function destroy(User $user)
    {
        $this->authorizeAdministrator();

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Supprime le locataire correspondant (par exemple via l'email ou l'ID)
        Locataire::where('email', $user->email)->delete();

        // Supprime l'utilisateur
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilisateur et locataire associés supprimés avec succès.');
    }

    public function sendPasswordResetLink(User $user)
    {
        $this->authorizeAdministrator();

        if (! $user->login_locked_at) {
            return redirect()->route('users.index')->with('error', 'Ce compte n’est pas bloqué.');
        }

        try {
            $status = Password::sendResetLink(['email' => $user->email]);
        } catch (TransportExceptionInterface $exception) {
            Log::error('Password reset email delivery failed.', [
                'user_id' => $user->id,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return redirect()->route('users.index')->with(
                'error',
                'Le courriel n’a pas pu être envoyé. Vérifiez la configuration SMTP, puis réessayez dans une minute.'
            );
        }

        if ($status === Password::RESET_THROTTLED) {
            return redirect()->route('users.index')
                ->with('error', 'Un lien de réinitialisation a déjà été demandé récemment. Attendez une minute avant de réessayer ou utilisez le lien déjà reçu par courriel.');
        }

        return $status === Password::RESET_LINK_SENT
            ? redirect()->route('users.index')->with('success', 'Lien de réinitialisation envoyé à '.$user->email.'. Le compte sera débloqué après la création du nouveau mot de passe.')
            : redirect()->route('users.index')->with('error', __($status));
    }

    private function authorizeAdministrator(): void
    {
        abort_unless(auth()->user()->role === User::ROLE_ADMIN, 403);
    }
}
