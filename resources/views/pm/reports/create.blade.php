@extends('layouts.app')

@section('title', 'Buat Laporan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Buat Laporan Proyek</h1>
                <a href="{{ route('pm.reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('pm.reports.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required
                                           placeholder="Contoh: Laporan Progress Mingguan - Proyek ABC">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3"
                                              placeholder="Ringkasan singkat tentang laporan ini...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="content" class="form-label">Konten Laporan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" name="content" rows="10" required
                                              placeholder="Tuliskan konten laporan secara detail...">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="project_id" class="form-label">Proyek <span class="text-danger">*</span></label>
                                    <select class="form-select @error('project_id') is-invalid @enderror" 
                                            id="project_id" name="project_id" required>
                                        <option value="">Pilih Proyek</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipe Laporan</label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type">
                                        <option value="">Pilih Tipe</option>
                                        <option value="progress" {{ old('type') === 'progress' ? 'selected' : '' }}>Progress</option>
                                        <option value="weekly" {{ old('type') === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                                        <option value="monthly" {{ old('type') === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                        <option value="final" {{ old('type') === 'final' ? 'selected' : '' }}>Final</option>
                                        <option value="issue" {{ old('type') === 'issue' ? 'selected' : '' }}>Masalah</option>
                                        <option value="milestone" {{ old('type') === 'milestone' ? 'selected' : '' }}>Milestone</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status">
                                        <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Laporan akan dikirim untuk validasi admin jika status "Pending"</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="attachments" class="form-label">Lampiran</label>
                                    <input type="file" class="form-control @error('attachments') is-invalid @enderror" 
                                           id="attachments" name="attachments[]" multiple>
                                    @error('attachments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Upload file pendukung laporan. Maksimal 10MB per file.</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Templates -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6>Template Laporan:</h6>
                                <div class="btn-group-sm mb-3" role="group">
                                    <button type="button" class="btn btn-outline-secondary me-2" onclick="useTemplate('progress')">Progress</button>
                                    <button type="button" class="btn btn-outline-secondary me-2" onclick="useTemplate('weekly')">Mingguan</button>
                                    <button type="button" class="btn btn-outline-secondary me-2" onclick="useTemplate('issue')">Masalah</button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="useTemplate('milestone')">Milestone</button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('pm.reports.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" name="save_as" value="draft" class="btn btn-outline-primary">
                                <i class="fas fa-save"></i> Simpan sebagai Draft
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Kirim Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Template functions
function useTemplate(type) {
    const contentField = document.getElementById('content');
    const typeField = document.getElementById('type');
    
    let template = '';
    
    switch(type) {
        case 'progress':
            template = `## Ringkasan Progress

### Pencapaian Minggu Ini
- [Tuliskan pencapaian utama]

### Tugas yang Diselesaikan
- [List tugas yang sudah selesai]

### Tugas dalam Progress
- [List tugas yang sedang dikerjakan]

### Kendala/Masalah
- [Tuliskan kendala yang dihadapi]

### Rencana Minggu Depan
- [Tuliskan rencana untuk minggu depan]

### Catatan Tambahan
- [Catatan lainnya]`;
            break;
            
        case 'weekly':
            template = `## Laporan Mingguan
Periode: [Tanggal] - [Tanggal]

### Pencapaian Utama
- [Pencapaian minggu ini]

### Statistik
- Total tugas diselesaikan: [jumlah]
- Tugas dalam progress: [jumlah]
- Persentase completion: [%]

### Tim Performance
- [Evaluasi performa tim]

### Issues & Risks
- [Masalah dan risiko]

### Action Items
- [Item yang perlu ditindaklanjuti]`;
            break;
            
        case 'issue':
            template = `## Laporan Masalah

### Deskripsi Masalah
- [Jelaskan masalah secara detail]

### Dampak
- [Dampak terhadap proyek]

### Root Cause
- [Akar penyebab masalah]

### Solusi yang Diusulkan
- [Solusi yang diusulkan]

### Timeline Penyelesaian
- [Estimasi waktu penyelesaian]

### Resources Needed
- [Sumber daya yang dibutuhkan]`;
            break;
            
        case 'milestone':
            template = `## Milestone Report

### Milestone Achieved
- [Milestone yang dicapai]

### Key Deliverables
- [Deliverable utama]

### Quality Metrics
- [Metrik kualitas]

### Lessons Learned
- [Pelajaran yang dipetik]

### Next Milestone
- [Milestone selanjutnya]

### Recommendations
- [Rekomendasi untuk ke depan]`;
            break;
    }
    
    contentField.value = template;
    typeField.value = type;
}

document.getElementById('attachments').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const maxSize = 10 * 1024 * 1024; // 10MB
    
    let hasError = false;
    files.forEach(file => {
        if (file.size > maxSize) {
            alert(`File ${file.name} terlalu besar. Maksimal 10MB.`);
            hasError = true;
        }
    });
    
    if (hasError) {
        e.target.value = '';
    }
});

// Auto-generate title based on project and type
document.getElementById('project_id').addEventListener('change', generateTitle);
document.getElementById('type').addEventListener('change', generateTitle);

function generateTitle() {
    const projectSelect = document.getElementById('project_id');
    const typeSelect = document.getElementById('type');
    const titleField = document.getElementById('title');
    
    if (projectSelect.value && typeSelect.value && !titleField.value) {
        const projectName = projectSelect.options[projectSelect.selectedIndex].text;
        const typeText = typeSelect.options[typeSelect.selectedIndex].text;
        const date = new Date().toLocaleDateString('id-ID');
        
        titleField.value = `Laporan ${typeText} - ${projectName} (${date})`;
    }
}
</script>
@endpush
