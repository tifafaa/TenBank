<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user(); // Ambil user yang login

        if ($user) { // Pastikan ada user login
            if ($user->role === 'admin') {
                $siswa = User::where('role', 'user')->get();
                $banks = User::where('role', 'bank')->get();
                $transactions = Wallet::with('user')->orderBy('created_at', 'desc')->get();

                return view('dashboard.admin', compact('siswa', 'banks', 'transactions'));

            } elseif ($user->role === 'bank') {
                $siswa = User::where('role', 'user')->get();
                $pendingTransactions = Wallet::with('user')
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();

                $processedTransactions = Wallet::with('user')
                    ->whereIn('status', ['success', 'rejected'])
                    ->orderBy('created_at', 'desc')
                    ->get();

                return view('dashboard.bank', compact('siswa', 'pendingTransactions', 'processedTransactions'));

            } else {
                $siswa = User::where('role', 'user')->get();
                $transactions = Wallet::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

                $income = Wallet::where('user_id', $user->id)
                            ->where('status', 'success')
                            ->sum('income');
                $outcome = Wallet::where('user_id', $user->id)
                                ->where('status', 'success')
                                ->sum('outcome');
                $balance = $income - $outcome;

                return view('dashboard.user', compact('transactions', 'siswa', 'balance', 'income', 'outcome'));
            }
        } else {
            return redirect('/login');
        }
    }

    // Admin
    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:user,bank,admin',
            'password' => 'required|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('dashboard')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit', compact('user')); // Bisa pakai modal juga
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:user,bank,admin',
            'password' => 'nullable|min:6',
        ]);

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('dashboard')->with('success', 'User berhasil diupdate!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('dashboard')->with('success', 'User berhasil dihapus!');
    }
}
