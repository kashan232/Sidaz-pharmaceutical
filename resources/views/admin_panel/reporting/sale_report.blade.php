@extends('admin_panel.layout.app')

@section('content')
<style>
    /* Compact Sale Report Terminal Styling */
    .sale-report-container {
        padding: 10px 14px;
        background: #f1f5f9;
        min-height: calc(100vh - 75px);
    }

    /* Filter Form Spacing for Bootstrap 4 */
    .sale-filter-form {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px !important;
    }
    .sale-filter-group {
        display: flex;
        align-items: center;
        margin-right: 12px;
    }
    .sale-filter-label {
        margin-right: 8px !important;
        margin-bottom: 0 !important;
        white-space: nowrap;
        font-weight: 700;
        font-size: .78rem;
        color: #475569;
    }
    
    /* Compact Summary Stat Cards */
    .summary-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 10px;
        margin-bottom: 10px;
    }
    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .stat-card .stat-label {
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 3px;
    }
    .stat-card .stat-value {
        font-size: 1.08rem;
        font-weight: 800;
        line-height: 1.2;
    }

    /* Scrollable Table Wrapper with Sticky Header */
    .sale-table-wrap {
        height: calc(100vh - 245px);
        max-height: calc(100vh - 245px);
        min-height: 380px;
        overflow-y: auto;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #ffffff;
    }
    
    .sale-table-wrap::-webkit-scrollbar { width: 10px; height: 10px; }
    .sale-table-wrap::-webkit-scrollbar-track { background: #f1f5f9; }
    .sale-table-wrap::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 5px; }
    .sale-table-wrap::-webkit-scrollbar-thumb:hover { background: #64748b; }

    #saleReport {
        font-size: .78rem;
        margin-bottom: 0;
    }

    #saleReport thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #1e293b !important;
        color: #ffffff !important;
        font-size: .75rem;
        font-weight: 700;
        padding: 9px 10px;
        border-bottom: 2px solid #334155;
        white-space: nowrap;
    }

    /* Compact Returns Column */
    #saleReport th:nth-child(11),
    #saleReport td:nth-child(11) {
        max-width: 135px !important;
        width: 135px !important;
        font-size: .72rem;
        word-break: break-word;
        white-space: normal;
    }
</style>

<div class="sale-report-container">
    
    {{-- Filter Header Bar with Explicit Margins & Gaps --}}
    <div class="card border-0 shadow-sm mb-2" style="border-radius: 10px;">
        <div class="card-body py-2 px-3">
            <form id="SaleFilterForm" class="sale-filter-form">
                
                {{-- Title Badge --}}
                <div class="sale-filter-group" style="margin-right: 18px;">
                    <span class="fw-bold text-dark fs-6" style="letter-spacing: -0.2px; font-weight:700; white-space:nowrap;">
                        <i class="fas fa-chart-line text-primary" style="margin-right: 8px;"></i>Sale Report
                    </span>
                </div>

                {{-- Start Date --}}
                <div class="sale-filter-group" style="margin-right: 18px;">
                    <label for="start_date" class="sale-filter-label">Start:</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-control form-control-sm fw-bold" style="height: 36px; width: 185px; font-size: .78rem; border-radius: 6px;">
                </div>

                {{-- End Date --}}
                <div class="sale-filter-group" style="margin-right: 18px;">
                    <label for="end_date" class="sale-filter-label">End:</label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-control form-control-sm fw-bold" style="height: 36px; width: 185px; font-size: .78rem; border-radius: 6px;">
                </div>

                {{-- Search Input --}}
                <div class="flex-grow-1" style="min-width: 210px; margin-right: 18px;">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 12px; pointer-events: none;"></i>
                        <input type="text" id="search_product" class="form-control form-control-sm" placeholder="Search Product / Size / Invoice / Customer…" style="height: 36px; font-size: .80rem; border-radius: 6px; padding-left: 34px;">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex align-items-center">
                    <button type="button" id="btnSearch" class="btn btn-primary btn-sm px-3 fw-bold d-inline-flex align-items-center" style="height: 36px; border-radius: 6px; font-size: .80rem; margin-right: 10px;">
                        <i class="fas fa-filter" style="margin-right: 6px;"></i> Search
                    </button>
                    <button type="button" id="btnExportCsv" class="btn btn-outline-danger btn-sm px-3 fw-bold d-inline-flex align-items-center" style="height: 36px; border-radius: 6px; font-size: .80rem;">
                        <i class="fas fa-file-csv" style="margin-right: 6px;"></i> CSV
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Top Summary Stats Cards Bar (Header Metrics) --}}
    <div class="summary-card-grid">
        <div class="stat-card" style="border-left: 4px solid #4f46e5;">
            <div class="stat-label">Total Invoices</div>
            <div class="stat-value text-dark" id="cardTotalInvoices">0</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #0284c7;">
            <div class="stat-label">Total Quantity</div>
            <div class="stat-value text-info" id="cardTotalQty">0 Pcs</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #64748b;">
            <div class="stat-label">Gross Total</div>
            <div class="stat-value text-secondary" id="cardTotalAmount">Rs 0.00</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #059669;">
            <div class="stat-label">Net Sales</div>
            <div class="stat-value text-success" id="cardNetSales">Rs 0.00</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #dc2626;">
            <div class="stat-label">Total Returns</div>
            <div class="stat-value text-danger" id="cardTotalReturns">Rs 0.00</div>
        </div>
    </div>

    {{-- Table Container with Internal Vertical Scroll & Sticky Header --}}
    <div class="card border-0 shadow-sm" style="border-radius: 8px;">
        <div class="card-body p-0">
            <div id="loader" style="display:none; text-align:center; padding: 20px;">
                <div class="spinner-border text-primary" role="status"></div>
                <div class="small text-muted mt-2">Loading sales data…</div>
            </div>

            <div class="sale-table-wrap">
                <table class="table table-bordered table-hover align-middle" id="saleReport">
                    <thead>
                        <tr>
                            <th style="width:40px;">#</th>
                            <th style="width:130px;">Date &amp; Time</th>
                            <th style="width:110px;">Invoice</th>
                            <th style="width:110px;">Customer</th>
                            <th style="width:90px;">Ref</th>
                            <th>Products</th>
                            <th style="width:75px;">Qty</th>
                            <th style="width:90px;">Price</th>
                            <th style="width:95px;">Total</th>
                            <th style="width:95px;">Net</th>
                            <th style="width:135px;">Returns</th>
                        </tr>
                    </thead>
                    <tbody id="saleBody"></tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {

        // Auto Search Trigger
        $(document).on('click', '#btnSearch', function() {
            let start = $('#start_date').val();
            let end   = $('#end_date').val();
            $('#search_product').val(''); // Clear search input on new range search

            $("#loader").show();
            $.ajax({
                url: "{{ route('report.sale.fetch') }}",
                type: "GET",
                data: {
                    start_date: start,
                    end_date: end
                },
                success: function(res) {
                    $("#loader").hide();
                    let html = "";
                    let grandQty = 0,
                        grandTotal = 0,
                        grandNet = 0,
                        grandReturn = 0;

                    res.forEach((s, i) => {
                        let products = s.product.split(',').join('<br>');
                        let qtyArr = s.qty.split(',');
                        let qtyPiecesArr = s.total_pieces ? s.total_pieces.split(',') : (s.qty_decimal ? s.qty_decimal.split(',') : qtyArr);
                        let price = s.per_price.split(',').join('<br>');
                        let total = s.per_total.split(',').join('<br>');

                        // qty total per row (calculation only using pieces)
                        let rowQty = qtyPiecesArr.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
                        grandQty += rowQty;

                        // calculate totals
                        let rowTotal = s.per_total.split(',').reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
                        grandTotal += parseFloat(rowTotal);
                        grandNet += parseFloat(s.total_net);

                        // returns
                        let returnHtml = "";
                        let returnTotal = 0;
                        if (s.returns && s.returns.length > 0) {
                            s.returns.forEach(r => {
                                returnHtml += `<span class="text-danger fw-semibold">${r.product} (${r.qty}) - ${r.per_total}</span><br>`;
                                returnTotal += parseFloat(r.per_total);
                            });
                        }
                        grandReturn += returnTotal;

                        html += `<tr data-qty="${rowQty}" data-total="${rowTotal}" data-net="${s.total_net}" data-return="${returnTotal}">
                        <td>${i+1}</td>
                        <td class="small text-nowrap">${s.created_at}</td>
                        <td class="font-monospace fw-bold text-primary">INVSLE-${s.id}</td>
                        <td>${s.customer_name ?? '-'}</td>
                        <td>${s.reference ?? '-'}</td>
                        <td>${products}</td>
                        <td class="fw-semibold">${qtyArr.join('<br>')}</td>
                        <td>${price}</td>
                        <td>${total}</td>
                        <td class="fw-bold text-dark">${parseFloat(s.total_net).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                        <td>${returnHtml || '-'}</td>
                    </tr>`;
                    });

                    // Grand total row inside table
                    html += `<tr class="fw-bold bg-light" id="grandTotalRow">
                    <td colspan="6" class="text-end">Grand Total:</td>
                    <td id="grandQty">${grandQty.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                    <td>-</td>
                    <td id="grandTotal">${grandTotal.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                    <td id="grandNet">${grandNet.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                    <td id="grandReturn">${grandReturn.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                </tr>`;

                    $('#saleBody').html(html);

                    // Update Top Header Summary Metric Cards
                    updateHeaderCards(res.length, grandQty, grandTotal, grandNet, grandReturn);
                }
            });
        });

        // Function to update Header Cards
        function updateHeaderCards(count, qty, gross, net, returns) {
            $('#cardTotalInvoices').text(count.toLocaleString());
            $('#cardTotalQty').text(qty.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}) + ' Pcs');
            $('#cardTotalAmount').text('Rs ' + gross.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));
            $('#cardNetSales').text('Rs ' + net.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));
            $('#cardTotalReturns').text('Rs ' + returns.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));
        }

        // Real-time Search Filter Handler
        $(document).on('input', '#search_product', function() {
            let val = $(this).val().toLowerCase();
            let visibleInvoices = 0;

            $('#saleBody tr').each(function() {
                if ($(this).attr('id') === 'grandTotalRow') return;

                let productText  = $(this).find('td:eq(5)').text().toLowerCase();
                let invoiceText  = $(this).find('td:eq(2)').text().toLowerCase();
                let customerText = $(this).find('td:eq(3)').text().toLowerCase();
                let refText      = $(this).find('td:eq(4)').text().toLowerCase();

                if (productText.indexOf(val) > -1 || invoiceText.indexOf(val) > -1 || customerText.indexOf(val) > -1 || refText.indexOf(val) > -1) {
                    $(this).show();
                    visibleInvoices++;
                } else {
                    $(this).hide();
                }
            });

            // Recalculate grand totals from visible rows
            let newQty = 0, newTotal = 0, newNet = 0, newReturn = 0;
            $('#saleBody tr').each(function() {
                if ($(this).attr('id') === 'grandTotalRow') return;
                if ($(this).is(':visible')) {
                    newQty += parseFloat($(this).data('qty')) || 0;
                    newTotal += parseFloat($(this).data('total')) || 0;
                    newNet += parseFloat($(this).data('net')) || 0;
                    newReturn += parseFloat($(this).data('return')) || 0;
                }
            });

            // Update grand total table elements
            $('#grandQty').text(newQty.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));
            $('#grandTotal').text(newTotal.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));
            $('#grandNet').text(newNet.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));
            $('#grandReturn').text(newReturn.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));

            // Update Header Cards dynamically based on filtered search
            updateHeaderCards(visibleInvoices, newQty, newTotal, newNet, newReturn);
        });

        // Initialize default dates based on shop shift (2:00 PM to 12:00 PM next day)
        let now = new Date();
        let startDt = new Date();
        let endDt = new Date();

        if (now.getHours() < 12) {
            startDt.setDate(now.getDate() - 1);
            startDt.setHours(14, 0, 0, 0);
            endDt.setHours(12, 0, 0, 0);
        } else {
            startDt.setHours(14, 0, 0, 0);
            endDt.setDate(now.getDate() + 1);
            endDt.setHours(12, 0, 0, 0);
        }

        function formatDateTimeLocal(date) {
            let year = date.getFullYear();
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let day = String(date.getDate()).padStart(2, '0');
            let hours = String(date.getHours()).padStart(2, '0');
            let minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        $('#start_date').val(formatDateTimeLocal(startDt));
        $('#end_date').val(formatDateTimeLocal(endDt));

        // Auto trigger initial search
        $('#btnSearch').trigger('click');

        // CSV export
        $(document).on('click', '#btnExportCsv', function() {
            let csv = [];
            $("#saleReport tr").each(function() {
                let row = [];
                $(this).find('th,td').each(function() {
                    let cellHtml = $(this).html();

                    let cellText = cellHtml
                        .replace(/<br\s*\/?>/gi, " | ")
                        .replace(/&nbsp;/gi, " ")
                        .replace(/<[^>]*>/g, "")
                        .trim();

                    row.push('"' + cellText.replace(/"/g, '""') + '"');
                });
                csv.push(row.join(","));
            });

            let csvString = csv.join("\n");
            let blob = new Blob([csvString], {
                type: 'text/csv;charset=utf-8;'
            });

            let link = document.createElement("a");
            if (link.download !== undefined) {
                let url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", "sale_report.csv");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        });
    });
</script>
@endsection