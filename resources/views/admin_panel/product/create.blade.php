@extends('admin_panel.layout.app')

@section('content')
    {{--
        SUCCESS: Horizontal 2-Column Compact Layout Redesign
        Features:
        - Left Column: Product Identity & Spec inputs
        - Right Column: live preview stats panel and submit actions
        - Compact size & low scroll footprint.
    --}}

    {{-- External Resources --}}
     <link href="{{ asset('assets/vendors/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
    {{-- line-awesome replaced by Font Awesome 6 (local) --}}
    <link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --bg-body: #f1f5f9;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --radius-md: 10px;
            --radius-lg: 16px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
        }

        .page-container {
            max-width: 1350px;
            margin: 0 auto;
            padding: 15px;
        }

        .section-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .card-header-pro {
            padding: 12px 20px;
            border-bottom: 1px solid var(--border-color);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title-pro {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }

        .card-body-pro {
            padding: 20px;
        }

        /* --- Form Styling --- */
        .form-label-pro {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 4px;
            letter-spacing: 0.02em;
        }

        .form-control-pro {
            display: block;
            width: 100%;
            padding: 8px 12px;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text-main);
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control-pro:focus {
            border-color: var(--primary);
            outline: 0;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-select-pro {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        /* --- Image Uploader --- */
        .img-uploader {
            width: 100%;
            border: 2px dashed #cbd5e1;
            border-radius: var(--radius-md);
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.2s;
        }

        .img-uploader:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .img-uploader img {
            max-width: 100%;
            object-fit: contain;
            padding: 5px;
        }

        /* Stats Box */
        .stats-summary-box {
            background: #f8fafc;
            border-radius: var(--radius-md);
            padding: 16px;
            border: 1px solid var(--border-color);
        }
        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stat-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 600; }
        .stat-value { font-size: 1.4rem; font-weight: 800; color: var(--text-main); }

        .total-value-display {
            background: #0f172a;
            color: #fff;
            padding: 20px;
            border-radius: var(--radius-md);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Clean modern styling for variants table */
        #variantsTable input[type=number]::-webkit-inner-spin-button, 
        #variantsTable input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        #variantsTable input[type=number] {
            -moz-appearance: textfield;
        }
        #variantsTable .form-control-pro, 
        #variantsTable .form-select {
            height: 30px;
            padding: 2px 6px;
            font-size: 12px;
            border-radius: 4px;
            border: 1px solid #ced4da;
            background-color: #ffffff;
            box-shadow: none;
        }
        #variantsTable .form-control-pro:focus, 
        #variantsTable .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.15);
        }
        #variantsTable .input-group-sm > .form-control-pro {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        #variantsTable .input-group-sm > .input-group-text {
            height: 30px;
            font-size: 11px;
            padding: 0 5px;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-color: #ced4da;
            background-color: #f8fafc;
            color: #64748b;
        }
        #variantsTable th {
            background-color: #f1f5f9 !important;
            color: #334155;
            font-weight: 600;
            font-size: 11px !important;
            padding: 6px 4px !important;
            vertical-align: middle;
            white-space: nowrap;
        }
        #variantsTable td {
            padding: 4px 3px !important;
            vertical-align: middle;
        }
    </style>

    <div class="page-container">

        {{-- Page Title --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('product') }}" class="btn btn-white border shadow-sm rounded-circle p-0" style="width: 36px; height: 36px; display: grid; place-items: center;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Create Product</h5>
                    <small class="text-muted" style="font-size:0.8rem;">Add carton or piece based items to inventory</small>
                </div>
            </div>
        </div>

        <form id="productForm" action="{{ route('store-product') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">

                {{-- MAIN COLUMN: Full Width --}}
                <div class="col-12">

                    {{-- CARD 1: Identity & Categorization --}}
                    <div class="section-card">
                        <div class="card-header-pro">
                            <h5 class="card-title-pro"><i class="fas fa-tag text-primary"></i> Product Identity</h5>
                        </div>
                        <div class="card-body-pro">
                            <div class="row g-3">

                                {{-- Sub-grid for left content, image on the right --}}
                                <div class="col-md-9">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label-pro">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control-pro fs-6 fw-bold" name="product_name" required placeholder="e.g. Ceramic Floor Tile 60x60">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label-pro">Category <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-1">
                                                <select class="form-select form-control-pro form-select-pro" id="category-dropdown" name="category_id" required>
                                                    <option value="">Select...</option>
                                                    @foreach ($categories as $cat)
                                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn btn-light border px-2 shadow-sm" data-toggle="modal" data-target="#categoryModal">+</button>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label-pro">Sub Category</label>
                                            <div class="d-flex gap-1">
                                                <select class="form-select form-control-pro form-select-pro" id="subcategory-dropdown" name="sub_category_id">
                                                    <option value="">Select...</option>
                                                </select>
                                                <button type="button" class="btn btn-light border px-2 shadow-sm" data-toggle="modal" data-target="#subcategoryModal">+</button>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label-pro">Brand</label>
                                            <div class="d-flex gap-1">
                                                <select class="form-select form-control-pro form-select-pro" name="brand_id" required>
                                                    <option value="">Select...</option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn btn-light border px-2 shadow-sm" data-toggle="modal" data-target="#brandModal">+</button>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label-pro">Unit</label>
                                            <select class="form-select form-control-pro form-select-pro fw-bold" name="size_mode" id="unit-dropdown">
                                                <option value="by_pieces">Pcs</option>
                                                <option value="by_cartons">Carton</option>
                                                <option value="by_meter">Meter</option>
                                                <option value="by_feet">Ft (Feet)</option>
                                                <option value="by_kg">Kg</option>
                                                <option value="by_gm">Gm</option>
                                                <option value="by_ton">Ton</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Image Uploader side-by-side (Right) --}}
                                <div class="col-md-3">
                                    <label class="form-label-pro">Product Image</label>
                                    <input type="file" id="imageInput" name="image" class="d-none" accept="image/*">
                                    <div class="img-uploader" style="height: 110px;" onclick="document.getElementById('imageInput').click()">
                                        <button type="button" id="clearImageBtn" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 d-none rounded-circle" style="width:20px;height:20px;padding:0;z-index:10; font-size:12px; line-height:1;">&times;</button>
                                        <img id="preview" class="d-none" style="max-height: 100px;">
                                        <div id="uploadPlaceholder" class="text-center p-2">
                                            <i class="fas fa-camera fs-3 text-primary"></i>
                                            <div class="fw-bold" style="font-size: 11px;">Upload</div>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-12 mt-3 pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="form-label-pro text-primary mb-0"><i class="fas fa-cubes me-1"></i>Product Variants & Units</h6>
                                        <button type="button" class="btn btn-sm btn-primary" id="enableVariantsBtn"><i class="fas fa-plus me-1"></i>Add Variant Row</button>
                                    </div>
                                    <div id="variantsContainer">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm align-middle mb-1" id="variantsTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="text-uppercase text-muted p-1" style="min-width: 140px; font-size: 10px;">Variant Name</th>
                                                        <th class="text-uppercase text-muted p-1" style="width: 80px; font-size: 10px;">Size</th>
                                                        <th class="text-uppercase text-muted p-1" style="width: 80px; font-size: 10px;">Color</th>
                                                        <th class="text-uppercase text-muted p-1" style="width: 75px; font-size: 10px;">Unit</th>
                                                        <th class="text-uppercase text-muted p-1 text-center" style="width: 90px; font-size: 10px;">Initial Stock</th>
                                                        <th class="text-uppercase text-muted p-1 text-center piece-wt-col" style="width: 105px; font-size: 10px;">Piece Wt (g)</th>
                                                        <th class="text-uppercase text-muted p-1" style="width: 90px; font-size: 10px;">Sale Price</th>
                                                        <th class="text-uppercase text-muted p-1" style="width: 90px; font-size: 10px;">Wholesale</th>
                                                        <th class="text-uppercase text-muted p-1" style="width: 90px; font-size: 10px;">Purch Price</th>
                                                        <th class="text-uppercase text-muted p-1" style="width: 55px; font-size: 10px;">Alert</th>
                                                        <th class="text-uppercase text-muted p-1" style="width: 100px; font-size: 10px;">Barcode</th>
                                                        <th class="text-uppercase text-muted p-1 text-center" style="width: 50px; font-size: 10px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="variantsBody">
                                                    <!-- rows injected by JS -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>



                    {{-- INLINE ACTIONS ROW --}}
                    <div class="d-flex justify-content-end align-items-center bg-white p-3 rounded shadow-sm border mb-4 gap-2">
                        <a href="{{ route('product') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius: var(--radius-md); font-size: 0.9rem;">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold" style="background: var(--primary); border: none; border-radius: var(--radius-md); font-size: 0.9rem;">
                            <i class="fas fa-check-circle me-1"></i> SAVE PRODUCT
                        </button>
                    </div>



            {{-- HIDDEN FORM CONTROLS FOR BACKEND VALIDATION COMPATIBILITY --}}
            <div style="display:none !important;">
                <input type="number" name="height" id="height" step="0.01" value="0">
                <input type="number" name="width" id="width" step="0.01" value="0">
                <input type="number" name="price_per_m2" id="price_per_m2" step="0.01" value="0">
                <input type="number" name="purchase_price_per_m2" id="purchase_price_per_m2" step="0.01" value="0">

                <input type="number" name="piece_quantity" id="piece_quantity" value="0">
                <input type="number" name="pieces_per_box" id="pieces_per_box" value="1">
                <input type="number" name="boxes_quantity" id="boxes_quantity" value="0">
                <input type="number" name="loose_pieces" id="loose_pieces" value="0">
                <input type="number" name="sale_price_per_box" id="sale_price_per_box" step="0.01" value="0">
                <input type="number" name="wholesale_price" id="wholesale_price" step="0.01" value="0">
                <input type="number" name="weight_per_piece" id="weight_per_piece" step="0.0001" value="0">
                <input type="number" name="purchase_price_per_piece" id="purchase_price_per_piece" step="0.01" value="0">
                <input type="number" name="alert_carton_quantity" id="alert_carton_quantity" value="0">
            </div>

        </form>

        {{-- Modals --}}
        <div id="categoryModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-md);">
                    <form action="{{ route('store.category') }}" method="POST">
                        @csrf
                        <div class="modal-header border-0 pb-0">
                            <h6 class="modal-title fw-bold">New Category</h6>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="page" value="product_page">
                            <div class="mb-3">
                                <label class="form-label-pro">Category Name</label>
                                <input type="text" name="name" class="form-control-pro" required placeholder="e.g. Ceramics">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill">Create Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="subcategoryModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-md);">
                    <form action="{{ route('store.subcategory') }}" method="POST">
                        @csrf
                        <div class="modal-header border-0 pb-0">
                            <h6 class="modal-title fw-bold">New Subcategory</h6>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="page" value="product_page">
                            <div class="mb-3">
                                <label class="form-label-pro">Parent Category</label>
                                <select name="category_id" class="form-select form-control-pro">
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-pro">Name</label>
                                <input type="text" name="name" class="form-control-pro" required placeholder="e.g. Floor Tiles">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill">Create Subcategory</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Brand Modal --}}
        <div id="brandModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-md);">
                    <form action="{{ route('store.Brand') }}" method="POST">
                        @csrf
                        <div class="modal-header border-0 pb-0">
                            <h6 class="modal-title fw-bold">New Brand</h6>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="page" value="product_page">
                            <div class="mb-3">
                                <label class="form-label-pro">Brand Name</label>
                                <input type="text" name="name" class="form-control-pro" required placeholder="e.g. Johnson">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill">Create Brand</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('productForm');
            const unitDropdown = document.getElementById('unit-dropdown');

            // Image Handler
            const imgInput = document.getElementById('imageInput');
            const preview = document.getElementById('preview');
            const ph = document.getElementById('uploadPlaceholder');
            const clr = document.getElementById('clearImageBtn');

            imgInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const r = new FileReader();
                    r.onload = (e) => {
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                        ph.classList.add('d-none');
                        clr.classList.remove('d-none');
                    };
                    r.readAsDataURL(this.files[0]);
                }
            });

            clr.addEventListener('click', (e) => {
                e.stopPropagation();
                imgInput.value = '';
                preview.classList.add('d-none');
                ph.classList.remove('d-none');
                clr.classList.add('d-none');
            });

            // AJAX Submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // --- Sync Variants data to hidden main fields for backend compatibility ---
                const vStocks = document.querySelectorAll('input[name="variant_stock[]"]');
                const vSale = document.querySelectorAll('input[name="variant_sale_price[]"]');
                const vWholesale = document.querySelectorAll('input[name="variant_wholesale_price[]"]');
                const vWeight = document.querySelectorAll('input[name="variant_weight_per_piece[]"]');
                const vPurch = document.querySelectorAll('input[name="variant_purchase_price[]"]');
                const vAlert = document.querySelectorAll('input[name="variant_alert_qty[]"]');

                let totalStock = 0;
                vStocks.forEach(el => totalStock += (parseFloat(el.value) || 0));

                let firstSale = vSale.length > 0 ? (parseFloat(vSale[0].value) || 0) : 0;
                let firstWholesale = vWholesale.length > 0 ? (parseFloat(vWholesale[0].value) || 0) : 0;
                let firstWeight = vWeight.length > 0 ? (parseFloat(vWeight[0].value) || 0) : 0;
                let firstPurch = vPurch.length > 0 ? (parseFloat(vPurch[0].value) || 0) : 0;
                let firstAlert = vAlert.length > 0 ? (parseFloat(vAlert[0].value) || 0) : 0;

                const mode = unitDropdown ? unitDropdown.value : 'by_pieces';
                if(mode === 'by_cartons') {
                    document.getElementById('boxes_quantity').value = totalStock;
                    document.getElementById('pieces_per_box').value = 1;
                    document.getElementById('loose_pieces').value = 0;
                    document.getElementById('piece_quantity').value = 0;
                } else {
                    document.getElementById('piece_quantity').value = totalStock;
                    document.getElementById('boxes_quantity').value = 0;
                }
                document.getElementById('sale_price_per_box').value = firstSale;
                document.getElementById('wholesale_price').value = firstWholesale;
                document.getElementById('weight_per_piece').value = firstWeight;
                document.getElementById('purchase_price_per_piece').value = firstPurch;
                document.getElementById('alert_carton_quantity').value = firstAlert;
                // ------------------------------------------------------------------------

                const btn = document.querySelector('button[type="submit"]');
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                btn.disabled = true;

                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'},
                    body: formData
                })
                .then(r => r.json().then(data => ({status: r.status, body: data})))
                .then(({status, body}) => {
                    if (status === 200 || body.status === 'success') {
                         Swal.fire({
                            icon: 'success', title: 'Saved!',
                            text: 'Product created successfully', timer: 1500, showConfirmButton: false
                         }).then(() => window.location.href = "{{ route('product') }}");
                    } else {
                        const msg = body.errors ? Object.values(body.errors).flat().join('<br>') : (body.message || 'Error');
                        Swal.fire({icon: 'error', title: 'Error', html: msg});
                    }
                })
                .catch(err => Swal.fire({icon: 'error', title: 'Error', text: 'Server Error'}))
                .finally(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                });
            });

            $('#category-dropdown').on('change', function() {
                var cid = $(this).val();
                if (cid) {
                    $.get('/get-subcategories/' + cid, function(d) {
                        $('#subcategory-dropdown').empty().append('<option value="">Select...</option>');
                        $.each(d, function(_, v) {
                            $('#subcategory-dropdown').append('<option value="' + v.id + '">' + v.name + '</option>');
                        });
                    });
                }
            });

            function handleQuickAdd(modalId, selectSelector) {
                $('#' + modalId + ' form').on('submit', function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let btn = form.find('button[type="submit"]');
                    let originalText = btn.text();
                    btn.text('Saving...').prop('disabled', true);

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        success: function(res) {
                            if(res.success) {
                                $(selectSelector).append(new Option(res.name, res.id, true, true)).trigger('change');
                                $('#' + modalId).modal('hide');
                                form[0].reset();
                                Swal.fire({icon: 'success', title: 'Added successfully', toast: true, position: 'top-end', showConfirmButton: false, timer: 1500});
                            }
                        },
                        error: function() {
                            Swal.fire({icon: 'error', title: 'Error', text: 'Something went wrong!'});
                        },
                        complete: function() {
                            btn.text(originalText).prop('disabled', false);
                        }
                    });
                });
            }

            handleQuickAdd('categoryModal', '#category-dropdown, #subcategoryModal select[name="category_id"]');
            handleQuickAdd('subcategoryModal', '#subcategory-dropdown');
            handleQuickAdd('brandModal', 'select[name="brand_id"]');

            const enableVariantsBtn = document.getElementById('enableVariantsBtn');
            const variantsContainer = document.getElementById('variantsContainer');
            const variantsBody = document.getElementById('variantsBody');

            const weightUnits = ['by_kg', 'by_gm', 'by_ton'];
            let variantMode = 'standard';
            let manualPrices = {}; // Keep track of manually overridden prices
            let manualNames = {}; // Keep track of manually overridden names

            function isWeightUnit(unit) {
                return weightUnits.includes(unit);
            }

            function updateVariantMode() {
                const currentUnit = unitDropdown ? unitDropdown.value : 'by_pieces';
                const newMode = isWeightUnit(currentUnit) ? 'weight' : 'standard';
                
                if (variantMode !== newMode) {
                    if (variantsBody.children.length > 0) {
                        Swal.fire({
                            title: 'Change Unit?',
                            text: 'This product contains variants. Changing the unit will remove the current variant settings from this form. Do you want to continue?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Continue',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                variantMode = newMode;
                                variantsBody.innerHTML = '';
                                manualPrices = {};
                                manualNames = {};
                                applyVariantModeUI();
                                if (variantMode === 'weight' && !variantsContainer.classList.contains('d-none')) {
                                    addBaseVariantRow();
                                }
                            } else {
                                // Revert dropdown
                                unitDropdown.value = (variantMode === 'weight') ? 'by_kg' : 'by_pieces';
                            }
                        });
                    } else {
                        variantMode = newMode;
                        applyVariantModeUI();
                    }
                }
            }

            function applyVariantModeUI() {
                const stdCols = document.querySelectorAll('.std-col');
                const weightCols = document.querySelectorAll('.weight-col');
                
                if (variantMode === 'weight') {
                    stdCols.forEach(col => col.classList.add('d-none'));
                    weightCols.forEach(col => col.classList.remove('d-none'));
                } else {
                    stdCols.forEach(col => col.classList.remove('d-none'));
                    weightCols.forEach(col => col.classList.add('d-none'));
                }
                toggleFactorColumns(); // To handle the legacy factor cols if needed
            }

            function generateRandomBarcode() {
                return Math.floor(100000 + Math.random() * 900000).toString();
            }

            // Sync base variant name with product name
            const productNameInput = document.querySelector('input[name="product_name"]');
            if (productNameInput) {
                productNameInput.addEventListener('input', function() {
                    const baseInput = document.querySelector('input.base-name-input');
                    if (baseInput && !manualNames[baseInput.dataset.vid]) {
                        baseInput.value = this.value;
                    }
                });
            }

            function addBaseVariantRow() {
                const tr = document.createElement('tr');
                const productName = productNameInput.value || '';
                const baseUnitName = unitDropdown ? unitDropdown.options[unitDropdown.selectedIndex].text : 'Kg';
                const vid = 'base_' + Date.now();
                tr.innerHTML = `
                    <td class="p-1">
                        <input type="text" class="form-control-pro form-control-sm base-name-input fw-bold" name="variant_name[]" value="${productName}" placeholder="Name" data-vid="${vid}">
                        <input type="hidden" name="variant_is_base[]" value="1">
                        <input type="hidden" name="variant_conv_factor[]" class="conv-factor-input" value="1">
                    </td>
                    <td class="p-1"><input type="text" class="form-control-pro form-control-sm" name="variant_size[]" placeholder="Size"></td>
                    <td class="p-1"><input type="text" class="form-control-pro form-control-sm" name="variant_color[]" placeholder="Color"></td>
                    <td class="p-1">
                        <select class="form-select form-select-sm fw-bold text-primary px-1" name="variant_unit[]" style="font-size:11px;">
                            <option value="Kg" ${baseUnitName.includes('Kg')?'selected':''}>Kg</option>
                            <option value="Pcs" ${baseUnitName.includes('Pcs')||baseUnitName.includes('Pieces')?'selected':''}>Pcs</option>
                            <option value="Gm" ${baseUnitName.includes('Gm')?'selected':''}>Gm</option>
                            <option value="Ft" ${baseUnitName.includes('Ft')?'selected':''}>Ft</option>
                            <option value="Meter" ${baseUnitName.includes('Meter')?'selected':''}>Mtr</option>
                            <option value="Box">Box</option>
                            <option value="Dozen">Dzn</option>
                        </select>
                    </td>
                    <td class="p-1">
                        <input type="number" class="form-control-pro form-control-sm text-center fw-bold text-primary" name="variant_stock[]" step="any" value="0" placeholder="0" title="Initial Stock">
                    </td>
                    <td class="p-1 piece-wt-col">
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control-pro form-control-sm" name="variant_weight_per_piece[]" step="any" placeholder="0.00" value="1000" readonly>
                            <span class="input-group-text bg-light text-muted p-1" style="font-size:10px;">g</span>
                        </div>
                    </td>
                    <td class="p-1"><input type="number" class="form-control-pro form-control-sm base-sale-input" name="variant_sale_price[]" step="any" placeholder="0.00" required></td>
                    <td class="p-1"><input type="number" class="form-control-pro form-control-sm" name="variant_wholesale_price[]" step="any" placeholder="0.00" value="0"></td>
                    <td class="p-1"><input type="number" class="form-control-pro form-control-sm base-purch-input" name="variant_purchase_price[]" step="any" placeholder="0.00" required></td>
                    <td class="p-1"><input type="number" class="form-control-pro form-control-sm" name="variant_alert_qty[]" value="0" placeholder="0"></td>
                    <td class="p-1"><input type="text" class="form-control-pro form-control-sm" name="variant_barcode[]" value="${generateRandomBarcode()}"></td>
                    <td class="p-1 text-center">
                        <span class="badge bg-primary px-2 py-1">Base</span>
                    </td>
                `;
                variantsBody.appendChild(tr);

                const baseSaleInp = tr.querySelector('.base-sale-input');
                const basePurchInp = tr.querySelector('.base-purch-input');
                const baseWholesaleInp = tr.querySelector('input[name="variant_wholesale_price[]"]');

                if (baseSaleInp) baseSaleInp.addEventListener('input', updatePriceSuggestions);
                if (basePurchInp) basePurchInp.addEventListener('input', updatePriceSuggestions);
                if (baseWholesaleInp) baseWholesaleInp.addEventListener('input', updatePriceSuggestions);
                
                tr.querySelector('.base-name-input').addEventListener('input', function() {
                    manualNames[vid] = true;
                });
                toggleFactorColumns();
            }

            function updatePriceSuggestions() {
                if (variantMode === 'pcs') return; // Do not auto-overwrite prices in Pcs mode

                const baseRow = variantsBody.querySelector('tr');
                if (!baseRow) return;

                const baseSale = parseFloat(baseRow.querySelector('.base-sale-input')?.value || 0);
                const basePurch = parseFloat(baseRow.querySelector('.base-purch-input')?.value || 0);
                const baseWholesale = parseFloat(baseRow.querySelector('input[name="variant_wholesale_price[]"]')?.value || 0);

                const rows = variantsBody.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    if (index === 0) return;
                    
                    const vid = row.dataset.vid;
                    const factorInput = row.querySelector('.conv-factor-input');
                    const factor = parseFloat(factorInput?.value || 0);
                    
                    const saleInp = row.querySelector('.sale-price-input');
                    const purchInp = row.querySelector('.purch-price-input');
                    const wholesaleInp = row.querySelector('input[name="variant_wholesale_price[]"]');
                    const pieceWtInp = row.querySelector('input[name="variant_weight_per_piece[]"]');

                    if (factor > 0) {
                        if (saleInp && (!vid || !manualPrices[vid + '_sale'])) {
                            saleInp.value = (baseSale * factor).toFixed(2);
                        }
                        if (purchInp && (!vid || !manualPrices[vid + '_purch'])) {
                            purchInp.value = (basePurch * factor).toFixed(2);
                        }
                        if (wholesaleInp && (!vid || !manualPrices[vid + '_wholesale'])) {
                            wholesaleInp.value = (baseWholesale * factor).toFixed(2);
                        }
                        if (pieceWtInp && (!vid || !manualPrices[vid + '_weight'])) {
                            pieceWtInp.value = (factor < 10) ? (factor * 1000).toFixed(1).replace(/\.0$/, '') : factor;
                        }
                    }
                });
            }

            function addVariantRow(weightGrams = null) {
                const tr = document.createElement('tr');
                const vid = 'var_' + Date.now();
                tr.dataset.vid = vid;
                
                let factor = 1;
                let suggestedName = productNameInput.value || '';
                if (weightGrams) {
                    factor = parseFloat(weightGrams) > 10 ? parseFloat((weightGrams / 1000).toFixed(6)) : parseFloat(weightGrams);
                    suggestedName += ` - ${weightGrams}g`;
                }
                
                const baseRow = variantsBody.querySelector('tr');
                const baseSale = parseFloat(baseRow?.querySelector('.base-sale-input')?.value || 0);
                const basePurch = parseFloat(baseRow?.querySelector('.base-purch-input')?.value || 0);
                const baseWholesale = parseFloat(baseRow?.querySelector('input[name="variant_wholesale_price[]"]')?.value || 0);

                let suggSale = '';
                let suggPurch = '';
                let suggWholesale = '';

                if (variantMode === 'weight') {
                    suggSale = (baseSale * factor).toFixed(2);
                    suggPurch = (basePurch * factor).toFixed(2);
                    suggWholesale = (baseWholesale * factor).toFixed(2);
                }

                const initPieceWt = weightGrams || (factor < 10 ? (factor * 1000).toFixed(1).replace(/\.0$/, '') : factor);
                const randBarcode = generateRandomBarcode();

                tr.innerHTML = `
                    <td class="p-1">
                        <input type="text" class="form-control-pro form-control-sm var-name-input" name="variant_name[]" value="${suggestedName}" placeholder="Name">
                        <input type="hidden" name="variant_is_base[]" value="0">
                        <input type="hidden" name="variant_conv_factor[]" class="conv-factor-input" value="${factor}">
                    </td>
                    <td class="p-1"><input type="text" class="form-control-pro form-control-sm" name="variant_size[]" placeholder="Size (XL, 10x12)"></td>
                    <td class="p-1"><input type="text" class="form-control-pro form-control-sm" name="variant_color[]" placeholder="Color"></td>
                    <td class="p-1">
                        <select class="form-select form-select-sm px-1 fw-bold text-dark" name="variant_unit[]" style="font-size:11px;">
                            <option value="Pcs" selected>Pcs</option>
                            <option value="Kg">Kg</option>
                            <option value="Gm">Gm</option>
                            <option value="Ft">Ft</option>
                            <option value="Meter">Mtr</option>
                            <option value="Box">Box</option>
                            <option value="Dozen">Dzn</option>
                        </select>
                    </td>
                    <td class="p-1">
                        <input type="number" class="form-control-pro form-control-sm text-center fw-bold text-primary" name="variant_stock[]" step="any" value="0" placeholder="0" title="Initial Stock">
                    </td>
                    <td class="p-1 piece-wt-col">
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control-pro form-control-sm" name="variant_weight_per_piece[]" step="any" placeholder="0.00" value="${initPieceWt}" readonly>
                            <span class="input-group-text bg-light text-muted p-1" style="font-size:10px;">g</span>
                        </div>
                    </td>
                    <td class="p-1"><input type="number" class="form-control-pro form-control-sm sale-price-input" name="variant_sale_price[]" step="any" value="${suggSale}" placeholder="0.00" required></td>
                    <td class="p-1"><input type="number" class="form-control-pro form-control-sm" name="variant_wholesale_price[]" step="any" placeholder="0.00" value="${suggWholesale}"></td>
                    <td class="p-1"><input type="number" class="form-control-pro form-control-sm purch-price-input" name="variant_purchase_price[]" step="any" value="${suggPurch}" placeholder="0.00" required></td>
                    <td class="p-1"><input type="number" class="form-control-pro form-control-sm" name="variant_alert_qty[]" value="0" placeholder="0"></td>
                    <td class="p-1"><input type="text" class="form-control-pro form-control-sm" name="variant_barcode[]" value="${randBarcode}"></td>
                    <td class="p-1 text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-var-btn p-1 px-2" title="Remove"><i class="fas fa-trash"></i></button>
                    </td>
                `;
                variantsBody.appendChild(tr);

                const saleInput = tr.querySelector('.sale-price-input');
                const purchInput = tr.querySelector('.purch-price-input');
                const wholesaleInput = tr.querySelector('input[name="variant_wholesale_price[]"]');
                const convInput = tr.querySelector('.conv-factor-input');
                const pieceWtInput = tr.querySelector('input[name="variant_weight_per_piece[]"]');

                if (saleInput) saleInput.addEventListener('input', () => { manualPrices[vid + '_sale'] = true; });
                if (purchInput) purchInput.addEventListener('input', () => { manualPrices[vid + '_purch'] = true; });
                if (wholesaleInput) wholesaleInput.addEventListener('input', () => { manualPrices[vid + '_wholesale'] = true; });

                if (convInput) {
                    convInput.addEventListener('input', function() {
                        const f = parseFloat(this.value) || 0;
                        if (pieceWtInput && f > 0) {
                            pieceWtInput.value = (f < 10) ? (f * 1000).toFixed(1).replace(/\.0$/, '') : f;
                        }
                        updatePriceSuggestions();
                    });
                }
                if (pieceWtInput) {
                    pieceWtInput.addEventListener('input', function() {
                        const wt = parseFloat(this.value) || 0;
                        if (convInput && wt > 0) {
                            const f = (wt < 10) ? wt : (wt / 1000);
                            convInput.value = f;
                            updatePriceSuggestions();
                        }
                    });
                }
                toggleFactorColumns();
            }

            function toggleFactorColumns() {
                if (!unitDropdown) return;
                const mode = unitDropdown.value;
                const showPieceWt = (mode === 'by_kg');
                
                const cols = document.querySelectorAll('.piece-wt-col');
                cols.forEach(c => {
                    if (showPieceWt) {
                        c.classList.remove('d-none');
                    } else {
                        c.classList.add('d-none');
                    }
                });
            }

            if (unitDropdown) {
                $(unitDropdown).on('change', function() {
                    updateVariantMode();
                    toggleFactorColumns();
                });
                // Initialize mode on load
                updateVariantMode();
                toggleFactorColumns();
            }

            enableVariantsBtn.addEventListener('click', function() {
                if (variantsBody.children.length === 0) {
                    addBaseVariantRow();
                } else {
                    addVariantRow();
                }
            });

            // Always ensure the button reads + Add Variant Row
            enableVariantsBtn.innerHTML = '<i class="fas fa-plus me-1"></i>Add Variant Row';
            enableVariantsBtn.className = 'btn btn-sm btn-primary';

            variantsBody.addEventListener('click', function(e) {
                const addBtn = e.target.closest('.add-var-btn');
                const remBtn = e.target.closest('.remove-var-btn');
                const genBtn = e.target.closest('.gen-var-barcode');

                if (addBtn) {
                    addVariantRow();
                } else if (remBtn) {
                    const row = remBtn.closest('tr');
                    // In weight mode, base variant cannot be deleted
                    if (row.querySelector('.base-name-input')) {
                        Swal.fire({icon: 'error', title: 'Cannot Delete', text: 'The base variant cannot be deleted.'});
                        return;
                    }

                    if (variantsBody.children.length > 1) {
                        row.remove();
                    } else {
                        // If it's the last row, clear inputs instead of removing
                        row.querySelectorAll('input:not([type="hidden"])').forEach(inp => inp.value = '');
                        const bc = row.querySelector('input[name="variant_barcode[]"]');
                        if (bc) bc.value = generateRandomBarcode();
                    }
                } else if (genBtn) {
                    const input = genBtn.closest('td').querySelector('input');
                    input.value = generateRandomBarcode();
                }
            });

            // Prevent duplicate Conv Factors on form submit
            form.addEventListener('submit', function(e) {
                if (variantMode === 'weight' && !variantsContainer.classList.contains('d-none')) {
                    const factors = Array.from(document.querySelectorAll('input[name="variant_conv_factor[]"]')).map(el => parseFloat(el.value));
                    const uniqueFactors = new Set(factors);
                    if (factors.length !== uniqueFactors.size) {
                        e.preventDefault();
                        Swal.fire({icon: 'error', title: 'Validation Error', text: 'A variant with the same Conv Factor already exists.'});
                        return;
                    }
                    if (factors.some(f => f <= 0 || isNaN(f))) {
                        e.preventDefault();
                        Swal.fire({icon: 'error', title: 'Validation Error', text: 'Conv Factor must be greater than 0.'});
                        return;
                    }
                }
            });
        });
    </script>
@endsection
