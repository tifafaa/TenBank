@extends('layouts.app')

@section('content')
<div class="container">
        <div class="alert alert-primary text-center py-4 shadow">
            @if(auth()->check())
                <h2>Selamat Datang Di TenBank, {{auth()->user()->name}} !</h2>
                <h5>Layanan Transaksi SMK Negeri 10 Jakarta</h5>
            @endif
            <div class="text-center d-flex flex-row gap-2 justify-content-center mt-4">
                <div class="card p-3  shadow-sm">
                    <h5 class="card-title">Total Saldo</h5>
                    <h1 class="card-text text-primary">Rp. {{ number_format($balance, 0, ',', '.') }}</h1>
                </div>
                <div class="card p-3 shadow-sm">
                    <div class="d-flex gap-2 justify-content-center">
                        <h5 class="card-title">Pemasukan</h5>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-in-down" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1z"/>
                            <path fill-rule="evenodd" d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                        </svg>
                    </div>
                    <h1 class="card-text text-success">Rp. {{ number_format($income, 0, ',', '.') }}</h1>
                </div>
                <div class="card p-3  shadow-sm">
                    <div class="d-flex gap-2 justify-content-center">
                        <h5 class="card-title">Pengeluaran</h5>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-up" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1z"/>
                            <path fill-rule="evenodd" d="M7.646.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 1.707V10.5a.5.5 0 0 1-1 0V1.707L5.354 3.854a.5.5 0 1 1-.708-.708z"/>
                        </svg>
                    </div>
                    <h1 class="card-text text-danger">Rp. {{ number_format($outcome, 0, ',', '.') }}</h1>
                </div>
            </div>
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

    <div class="d-flex gap-3 my-4">
        <div class="card w-50 p-3 shadow-sm">
            <h5 class="fw-bold">Tarik Tunai</h5>
            <form action="{{ route('wallet.withdraw') }}" method="POST">
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                @csrf
                <div class="mb-3">
                    <label for="withdrawAmount" class="form-label">Jumlah</label>
                    <input type="number" class="form-control" name="amount" id="withdrawAmount" required min="1000">
                </div>
                <button type="submit" class="btn btn-lg btn-outline-primary w-100">
                    Tarik Tunai
                </button>
            </form>
        </div>
        <div class="card w-50 p-3  shadow-sm">
            <h5 class="fw-bold">Menabung</h5>
            <form action="{{ route('wallet.deposit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="depositAmount" class="form-label">Jumlah</label>
                    <input type="number" class="form-control" name="amount" id="depositAmount" required min="1000">
                </div>
                <button type="submit" class="btn btn-lg btn-outline-success w-100">
                    Ajukan Deposit
                </button>
            </form>
        </div>
        <div class="card w-50 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold">Transfer</h5>
                <form action="{{ route('wallet.transfer') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="receiverId" class="form-label">Nama Penerima</label>
                        <select class="form-select" name="receiver_id" id="receiverId" required>
                            <option value="" selected disabled>Pilih Penerima</option>
                            @foreach ($siswa as $user)
                            @if ($user->id != Auth::id())
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="transferAmount" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" name="amount" id="transferAmount" required min="1000">
                    </div>
                    <button type="submit" class="btn btn-lg btn-outline-warning w-100">
                        Transfer
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card shadow-sm p-4">
        <h2 class="fw-bold">Riwayat Transaksi</h2>
        <table class="table table-bordered text-center table-hover"  id ="lookup">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $transaction->created_at }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td class="text-end {{ $transaction->income > 0 ? 'text-success' : 'text-danger' }}">
                        Rp. {{ number_format($transaction->income - $transaction->outcome, 0, ',', '.') }}
                    </td>
                    <td>
                        <span class="badge {{ $transaction->status === 'success' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</div>
@endsection
