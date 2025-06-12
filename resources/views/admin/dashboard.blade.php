@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Dashboard Administrator</h1>

    {{-- Statistik Utama --}}
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Pengguna</h5>
                    <p class="card-text fs-2 fw-bold">{{ $total_users }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Proyek</h5>
                    <p class="card-text fs-2 fw-bold">{{ $total_projects }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Laporan Menunggu Validasi --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h3>Laporan Menunggu Validasi</h3>
        </div>
        <div class="card-body">
            @if($pending_reports->isEmpty())
                <div class="alert alert-success">Tidak ada laporan yang menunggu validasi saat ini.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID Laporan</th>
                                <th>Nama Proyek</th>
                                <th>Dikirim Oleh</th>
                                <th>Tanggal Kirim</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pending_reports as $report)
                                <tr>
                                    <td>{{ $report->id }}</td>
                                    <td>{{ $report->project->name }}</td>
                                    <td>{{ $report->submittedBy->name }}</td>
                                    <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm">Lihat Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection