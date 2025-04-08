@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between">
        <h3 class="fw-bold">List Data Siswa</h3>
        <a href="{{ route('admin.create') }}" class="btn btn-success mb-3">Tambah Pengguna</a>
    </div>
    <table class="table table-bordered mb-4 text-center table-hover">
        <thead class="text-center fw-bold table-dark ">
            <tr>
                <td>No</td>
                <td>Nama</td>
                <td>Email</td>
                <td>Role</td>
                <td>Aksi</td>
            </tr>
        </thead>
        <tbody>
            @forelse($siswa as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    <!-- Aksi bisa tambahkan edit/delete -->
                    <a href="{{ route('admin.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('admin.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                    <a href="{{ route('bank.transaction', $user->id)}}" class="btn btn-sm btn-warning">Lakukan Transaksi</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data siswa</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h3 class="fw-bold">Konfirmasi Transaksi Siswa</h3>
    <table class="table table-bordered">
        <thead class="table-dark text-center">
            <tr>
                <th>Nama Pengguna</th>
                <th>Deskripsi</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        @forelse ($pendingTransactions as $transaction)
            <tbody class="text-center">
                <tr>
                    <td>{{ $transaction->user->name }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->transaction_date }}</td>
                    <td class="{{ $transaction->income > 0 ? 'text-success' : 'text-danger' }}">
                        Rp. {{ number_format($transaction->income - $transaction->outcome, 0, ',', '.') }}
                    </td>
                    <td>
                        <span class="badge bg-warning text-dark">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('confirm.transaction', $transaction->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-success">Konfirmasi</button>
                        </form>

                        <form action="{{ route('reject.transaction', $transaction->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak transaksi ini?')">Tolak</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        @empty
            <tbody><tr><td colspan="6" class="text-center">Tidak ada transaksi pending</td></tr></tbody>
        @endforelse
    </table>

    <h3 class="fw-bold">Riwayat Transaksi Siswa</h3>
    <table class="table table-bordered"  id ="lookup">
        <thead class="table-dark text-center">
            <tr>
                <th>Tanggal</th>
                <th>Nama Pengguna</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody class="text-center" >
                @foreach ($processedTransactions as $transaction)
                <tr>
                    <td>{{ $transaction->created_at }}</td>
                    <td>{{ $transaction->user->name }}</td>
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
@endsection
