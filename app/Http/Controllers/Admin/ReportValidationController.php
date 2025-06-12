<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Notifications\ReportValidated;
use Illuminate\Http\Request;

class ReportValidationController extends Controller
{
    public function index()
    {
        $reports = Report::with(['project', 'submittedBy'])
            ->where('status', 'Menunggu Persetujuan')
            ->latest()
            ->paginate(15);

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load(['project', 'submittedBy']);
        return view('admin.reports.show', compact('report'));
    }

    public function validate(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'validation_notes' => 'nullable|string|max:1000'
        ]);

        $report->update([
            'status' => $request->status,
            'validation_notes' => $request->validation_notes
        ]);

        // Kirim notifikasi ke PM yang mengirim laporan
        $report->submittedBy->notify(new ReportValidated($report));

        $message = $request->status === 'Disetujui' 
            ? 'Laporan berhasil disetujui.' 
            : 'Laporan berhasil ditolak.';

        return redirect()->route('admin.reports.index')
            ->with('success', $message);
    }
}
