@extends('layouts.app')

@section('content')

<div class="container">
    <h2>Transaksi untuk {{ $user->name }}</h2>
    <div class="card p-4 alert alert-success">
        <h3>Saldo: {{ number_format($balance, 0, ',', '.') }}</h3>
    </div>

    @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('transaction.process', $user->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="amount" class="form-label">Jumlah Transaksi</label>
            <input type="number" name="amount" class="form-control" min="1000" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Jenis Transaksi</label>
            <select name="type" class="form-control" required>
                <option value="deposit">Simpan Uang (Deposit)</option>
                <option value="withdraw">Tarik Uang (Withdraw)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Proses Transaksi</button>
    </form>
</div>
@endsection
