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
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data siswa</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h3 class="fw-bold">List Data Bank</h3>
    <table class="table table-bordered mb-4 text-center table-hover">
        <thead class="text-center fw-bold table-dark">
            <tr>
                <td>No</td>
                <td>Nama</td>
                <td>Email</td>
                <td>Role</td>
                <td>Aksi</td>
            </tr>
        </thead>
        <tbody>
            @forelse($banks as $index => $bank)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $bank->name }}</td>
                <td>{{ $bank->email }}</td>
                <td>{{ $bank->role }}</td>
                <td>
                    <a href="{{ route('admin.edit', $bank->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('admin.destroy', $bank->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data bank</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="card p-3 shadow-sm">
        <h2 class="text-center fw-bold">Riwayat Transaksi Users</h2>
        <table class="table table-bordered text-center table-hover" id ="lookup">
            <thead class="text-center fw-bold table-light">
                <tr>
                    <td>Tanggal dan Waktu</td>
                    <td>Nama</td>
                    <td>Deskripsi</td>
                    <td>Jumlah</td>
                    <td>Status</td>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
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
                @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
