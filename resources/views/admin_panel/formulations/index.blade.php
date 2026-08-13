@extends('admin_panel.layout.app')
@section('content')

@if (session('success'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonColor: '#4f46e5'
        });
    });
    </script>
@endif

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .sup-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 40px;
    }

    .sup-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        border-radius: 16px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .sup-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.02em;
    }
    .sup-sub {
        font-size: 0.82rem;
        color: #64748b;
    }

    .btn-sup-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff !important;
        border: none;
        padding: 10px 20px;
        font-size: 0.86rem;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.22);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-sup-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
    }

    .sup-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }
    .sup-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    #default-datatable thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 20px;
    }
    #default-datatable tbody td {
        padding: 14px 20px;
        vertical-align: middle;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-active { background: #dcfce7; color: #166534; }
    .status-draft { background: #fef9c3; color: #854d0e; }
    .status-inactive { background: #fee2e2; color: #991b1b; }
</style>

<div class="sup-page container-fluid px-3 px-md-4 pt-3">
    <div class="sup-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h3 class="sup-title"><i class="fas fa-flask text-primary me-2" style="color: #2563eb !important;"></i>Formulations / Recipes</h3>
            <div class="sup-sub">Manage product formulations and bill of materials</div>
        </div>
        <div>
            <a href="{{ route('formulations.create') }}" class="btn-sup-primary">
                <i class="fas fa-plus"></i> New Formulation
            </a>
        </div>
    </div>

    <div class="sup-card">
        <div class="sup-card-header">
            <div class="fw-bold text-dark"><i class="fas fa-list me-1 text-muted"></i> All Formulations</div>
        </div>
        <div class="sup-table-wrap p-3">
            <table class="table align-middle datanew" id="default-datatable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 70px;">Code</th>
                        <th>Product Name</th>
                        <th>Department</th>
                        <th>Batch Size</th>
                        <th>Version</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($formulations as $f)
                        <tr>
                            <td class="text-center"><span class="badge bg-light text-dark fw-bold border">{{ $f->formulation_code }}</span></td>
                            <td class="fw-bold">{{ $f->product->item_name ?? 'N/A' }}</td>
                            <td>{{ $f->department->name ?? 'N/A' }}</td>
                            <td>{{ (float)$f->batch_size }} {{ $f->batchUnit->short_name ?? '' }}</td>
                            <td>v{{ $f->version }}</td>
                            <td>
                                <span class="status-badge status-{{ $f->status }}">
                                    @if($f->status == 'active') <i class="fas fa-check-circle me-1" style="font-size: 8px;"></i>
                                    @elseif($f->status == 'draft') <i class="fas fa-file me-1" style="font-size: 8px;"></i>
                                    @else <i class="fas fa-times-circle me-1" style="font-size: 8px;"></i> @endif
                                    {{ ucfirst($f->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('formulations.show', $f->id) }}" class="btn btn-sm btn-light border" title="View"><i class="fas fa-eye text-primary"></i></a>
                                    <a href="{{ route('formulations.edit', $f->id) }}" class="btn btn-sm btn-light border" title="Edit"><i class="fas fa-edit text-warning"></i></a>
                                    <form action="{{ route('formulations.destroy', $f->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this formulation?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border" title="Delete"><i class="fas fa-trash text-danger"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('.datanew')) {
            $('.datanew').DataTable().destroy();
        }
        $('.datanew').DataTable({
            "pageLength": 10,
            "order": [],
            "language": {
                "search": "",
                "searchPlaceholder": "Search formulations..."
            }
        });
    });
</script>
@endsection
