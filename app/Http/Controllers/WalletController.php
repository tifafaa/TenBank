<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function history()
    {
        // Ambil semua transaksi dan relasi ke user
        $transactions = Wallet::with('user')->orderBy('created_at', 'desc')->get();

        return view('dashboard.admin', compact('transactions'));
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
        ]);

        Wallet::create([
            'user_id' => Auth::id(),
            'income' => $request->amount,
            'outcome' => 0,
            'description' => 'Menabung',
            'type' => 'deposit',
            'status' => 'pending',
            'transaction_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Deposit dalam proses konfirmasi bank.');
    }


    public function withdraw(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1000',
        ]);

        $userBalance = Wallet::where('user_id', $request->user_id)->where('status', 'success')->sum('income') -
                       Wallet::where('user_id', $request->user_id)->where('status', 'success')->sum('outcome');

        if ($request->amount > $userBalance) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi');
        }

        Wallet::create([
            'user_id' => $request->user_id,
            'income' => 0,
            'outcome' => $request->amount,
            'description' => 'Tarik Tunai',
            'type' => 'withdraw',
            'status' => 'pending',
            'transaction_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Tarik Tunai dalam proses konfirmasi');
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id|different:' . Auth::id(),
            'amount' => 'required|numeric|min:1000',
        ]);

        $sender = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);
        $amount = $request->amount;

        $senderBalance = Wallet::where('user_id', $sender->id)
            ->where('status', 'success')
            ->sum('income') -
            Wallet::where('user_id', $sender->id)
            ->where('status', 'success')
            ->sum('outcome');

        if ($amount > $senderBalance) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi');
        }

        Wallet::create([
            'user_id' => $sender->id,
            'income' => 0,
            'outcome' => $amount,
            'description' => 'Transfer ke ' . $receiver->name,
            'type' => 'transfer',
            'status' => 'success',
            'transaction_date' => now(),
        ]);

        Wallet::create([
            'user_id' => $receiver->id,
            'income' => $amount,
            'outcome' => 0,
            'description' => 'Transfer dari ' . $sender->name,
            'type' => 'transfer',
            'status' => 'success',
            'transaction_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Transfer Berhasil');
    }


    public function confirmTransaction($id)
    {
        $transaction = Wallet::find($id);

        if (!$transaction || $transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan atau sudah diproses.');
        }

        $transaction->update(['status' => 'success']);

        $receiverTransaction = Wallet::where([
            ['user_id', '!=', $transaction->user_id],
            ['income', $transaction->outcome],
            ['description', 'LIKE', "%User ID: {$transaction->user_id}%"],
            ['status', 'pending']
        ])->first();

        if ($receiverTransaction) {
            $receiverTransaction->update(['status' => 'success']);
        }

        return redirect()->back()->with('success', 'Transaksi berhasil dikonfirmasi.');
    }

    public function rejectTransaction($id)
    {
        $transaction = Wallet::find($id);

        if (!$transaction || $transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan atau sudah diproses.');
        }

        $transaction->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Transaksi berhasil ditolak.');
    }

    // Bank Tarik / Simpan Uang Siswa
    public function transaction($user_id)
    {
        $user = User::findOrFail($user_id);

        // Hitung saldo user
        $balance = Wallet::where('user_id', $user->id)
            ->where('status', 'success')
            ->sum('income') -
            Wallet::where('user_id', $user->id)
            ->where('status', 'success')
            ->sum('outcome');

        return view('bank.transaction', compact('user', 'balance'));
    }

    public function processTransaction(Request $request, $user_id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'type' => 'required|in:deposit,withdraw',
        ]);

        $user = User::findOrFail($user_id);
        $amount = $request->amount;
        $type = $request->type;

        if ($type == 'withdraw') {
            $balance = Wallet::where('user_id', $user->id)
                ->where('status', 'success')
                ->sum('income') -
                Wallet::where('user_id', $user->id)
                ->where('status', 'success')
                ->sum('outcome');

            if ($amount > $balance) {
                return redirect()->back()->with('error', 'Saldo tidak mencukupi.');
            }
        }

        Wallet::create([
            'user_id' => $user->id,
            'income' => $type == 'deposit' ? $amount : 0,
            'outcome' => $type == 'withdraw' ? $amount : 0,
            'description' => ucfirst($type) . ' saldo oleh Bank',
            'type' => $type,
            'status' => 'success',
            'transaction_date' => now(),
        ]);

        return redirect()->route('dashboard', $user->id)->with('success', 'Transaksi berhasil.');
    }
}
