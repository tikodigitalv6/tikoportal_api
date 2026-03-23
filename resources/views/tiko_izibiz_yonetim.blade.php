<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>TİKO - İzibiz Yönetim</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --bg-main: #0f172a;
            --bg-card: #020617;
            --bg-soft: #020617;
            --border-subtle: #1e293b;
            --accent: #22c55e;
            --accent-soft: rgba(34,197,94,0.08);
            --accent-strong: #16a34a;
            --text-main: #e5e7eb;
            --text-muted: #9ca3af;
            --danger: #ef4444;
            --danger-soft: rgba(239,68,68,0.08);
            --warning: #f59e0b;
            --warning-soft: rgba(245,158,11,0.08);
            --pill-bg: #020617;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "SF Pro Text", sans-serif;
            background: var(--bg-main);
            color: var(--text-main);
        }

        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .shell {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px 16px 32px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }

        .tabs {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 2px;
            border-radius: 999px;
            border: 1px solid var(--border-subtle);
            background: var(--bg-card);
        }

        .tab-button {
            border-radius: 999px;
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-size: 11px;
            padding: 4px 10px;
            cursor: pointer;
        }

        .tab-button.active {
            background: #020617;
            color: var(--text-main);
        }

        .title-group h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .title-group p {
            margin: 4px 0 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid var(--border-subtle);
            background: var(--bg-card);
            font-size: 11px;
            color: var(--text-muted);
        }

        .tag-dot {
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: var(--accent);
        }

        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr);
            gap: 16px;
        }

        @media (max-width: 960px) {
            .layout {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        .panel {
            background: var(--bg-card);
            border-radius: 18px;
            border: 1px solid var(--border-subtle);
            padding: 14px 14px 10px;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .panel-title {
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .panel-title span {
            padding: 2px 7px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 11px;
        }

        .search-box {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--bg-soft);
            border-radius: 999px;
            border: 1px solid var(--border-subtle);
            padding: 4px 9px;
        }

        .search-box input {
            border: none;
            outline: none;
            background: transparent;
            color: var(--text-main);
            font-size: 12px;
            width: 140px;
        }

        .search-box input::placeholder {
            color: var(--text-muted);
        }

        .search-icon {
            width: 13px;
            height: 13px;
            border-radius: 999px;
            border: 1px solid var(--border-subtle);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: var(--text-muted);
        }

        .table-wrapper {
            flex: 1;
            min-height: 0;
            overflow: auto;
            border-radius: 12px;
            border: 1px solid var(--border-subtle);
            background: #020617;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        thead {
            background: #020617;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        th, td {
            padding: 7px 10px;
            text-align: left;
            border-bottom: 1px solid rgba(30,41,59,0.8);
            white-space: nowrap;
        }

        th {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-muted);
        }

        tbody tr {
            cursor: pointer;
            transition: background-color 0.08s ease, transform 0.06s ease;
        }

        tbody tr:hover {
            background: #020617;
        }

        tbody tr.selected {
            background: rgba(15,118,110,0.18);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 2px 8px;
            border-radius: 999px;
            background: var(--pill-bg);
            border: 1px solid rgba(148,163,184,0.35);
            font-size: 11px;
        }

        .pill-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
        }

        .pill-dot.green {
            background: var(--accent);
        }

        .pill-dot.red {
            background: var(--danger);
        }

        .pill-dot.gray {
            background: #6b7280;
        }

        .pill-label {
            color: var(--text-muted);
        }

        .meta {
            font-size: 11px;
            color: var(--text-muted);
        }

        .meta strong {
            color: var(--text-main);
            font-weight: 500;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            border: 1px solid rgba(148,163,184,0.35);
        }

        .status-badge.green {
            background: var(--accent-soft);
            color: var(--accent-strong);
            border-color: rgba(34,197,94,0.4);
        }

        .status-badge.yellow {
            background: var(--warning-soft);
            color: var(--warning);
            border-color: rgba(245,158,11,0.4);
        }

        .status-badge.gray {
            background: rgba(55,65,81,0.35);
            color: #d1d5db;
            border-color: rgba(75,85,99,0.8);
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            margin-right: 6px;
            background: currentColor;
        }

        .empty-state {
            padding: 18px 14px;
            font-size: 12px;
            color: var(--text-muted);
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: 8px;
            font-size: 11px;
            color: var(--text-muted);
        }

        .pagination-buttons {
            display: inline-flex;
            gap: 6px;
        }

        .btn-page {
            border-radius: 999px;
            border: 1px solid var(--border-subtle);
            background: #020617;
            color: var(--text-main);
            font-size: 11px;
            padding: 3px 9px;
            cursor: pointer;
        }

        .btn-page:disabled {
            opacity: 0.4;
            cursor: default;
        }

        .footer {
            margin-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            font-size: 11px;
            color: var(--text-muted);
        }

        .console {
            flex: 1;
            min-height: 40px;
            max-height: 110px;
            overflow: auto;
            border-radius: 8px;
            border: 1px solid var(--border-subtle);
            background: #020617;
            padding: 6px 8px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            line-height: 1.45;
        }

        .console-line {
            margin: 0;
            white-space: nowrap;
        }

        .console-line span {
            display: inline-block;
        }

        .console-prefix {
            color: #6b7280;
            margin-right: 6px;
        }

        .console-ok {
            color: var(--accent);
        }

        .console-info {
            color: #60a5fa;
        }

        .console-error {
            color: var(--danger);
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 40;
        }

        .modal-backdrop.open {
            display: flex;
        }

        .modal {
            background: #020617;
            border-radius: 18px;
            border: 1px solid var(--border-subtle);
            padding: 18px 18px 14px;
            width: 420px;
            max-width: 94%;
            box-shadow: 0 18px 45px rgba(0,0,0,0.7);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .modal-title {
            font-size: 14px;
            font-weight: 500;
        }

        .modal-close {
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-size: 16px;
            cursor: pointer;
        }

        .modal-body {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .field-label {
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 2px;
        }

        .field-input, .field-select, .field-textarea {
            width: 100%;
            border-radius: 8px;
            border: 1px solid var(--border-subtle);
            background: #020617;
            color: var(--text-main);
            font-size: 12px;
            padding: 5px 8px;
        }

        .field-textarea {
            resize: vertical;
            min-height: 48px;
        }

        .field-row {
            display: flex;
            gap: 8px;
        }

        .field-row > div {
            flex: 1;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn-secondary {
            border-radius: 999px;
            border: 1px solid var(--border-subtle);
            background: #020617;
            color: var(--text-main);
            font-size: 11px;
            padding: 4px 10px;
            cursor: pointer;
        }

        .btn-primary {
            border-radius: 999px;
            border: 1px solid var(--accent);
            background: var(--accent);
            color: #020617;
            font-size: 11px;
            padding: 4px 12px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="shell">
        <header class="header">
            <div class="title-group">
                <h1>İzibiz Müşteri &amp; Paket Yönetimi</h1>
                <p>Müşterileri ve paket geçmişlerini tek ekrandan takip et.</p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="tabs">
                    <button type="button" class="tab-button active" id="tabCustomers">Müşteriler</button>
                    <button type="button" class="tab-button" id="tabPackages">Paketler</button>
                </div>
                <div class="tag">
                    <span class="tag-dot"></span>
                    TİKO &middot; İzibiz entegrasyonu
                </div>
            </div>
        </header>

        <main class="layout" id="customersView">
            <section class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        Müşteriler
                        <span id="customersCount">0</span>
                    </div>
                    <div class="search-box">
                        <div class="search-icon">/</div>
                        <input
                            type="text"
                            id="customerSearch"
                            placeholder="VKN/TCKN, ad, unvan"
                            autocomplete="off"
                        >
                    </div>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                        <tr>
                            <th>Müşteri</th>
                            <th>VKN/TCKN</th>
                            <th>Durum</th>
                            <th>Tip</th>
                            <th>Şehir</th>
                            <th>Son Güncelleme</th>
                        </tr>
                        </thead>
                        <tbody id="customersTableBody">
                        <tr>
                            <td colspan="6" class="empty-state">Müşteriler yükleniyor...</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    <div>
                        <span id="customersPageInfo">Sayfa 1 / 1</span>
                    </div>
                    <div class="pagination-buttons">
                        <button type="button" class="btn-page" id="btnRefreshCache">Cache Yenile</button>
                        <button type="button" class="btn-page" id="btnPrevPage">&lt; Önceki</button>
                        <button type="button" class="btn-page" id="btnNextPage">Sonraki &gt;</button>
                    </div>
                </div>
            </section>

            <section class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        Paket / Tarife Geçmişi
                        <span id="tariffsCount">0</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <button type="button" class="btn-page" id="btnNewCredit" disabled>Yeni Kontör Tanımla</button>
                        <div class="meta" id="selectedCustomerMeta">
                            Bir müşteri seçerek paketlerini görüntüleyin.
                        </div>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                        <tr>
                            <th>Paket</th>
                            <th>Miktar</th>
                            <th>Kalan</th>
                            <th>Durum</th>
                            <th>Dönem</th>
                        </tr>
                        </thead>
                        <tbody id="tariffsTableBody">
                        <tr>
                            <td colspan="5" class="empty-state">Henüz müşteri seçilmedi.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <main class="layout" id="packagesView" style="display:none;margin-top:12px;">
            <section class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        Ön Ödemeli Tarifeler
                        <span id="plansCount">0</span>
                    </div>
                    <button type="button" class="btn-page" id="btnNewPackage">Yeni Paket Tanımla</button>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                        <tr>
                            <th>Plan Adı</th>
                            <th>Tip</th>
                            <th>Miktar</th>
                            <th>Fiyat</th>
                            <th>Süre (ay)</th>
                            <th>Durum</th>
                        </tr>
                        </thead>
                        <tbody id="plansTableBody">
                        <tr>
                            <td colspan="6" class="empty-state">Tarife planları yükleniyor...</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        Onay Bekleyen Tarifeler
                        <span id="waitingCount">0</span>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                        <tr>
                            <th>Müşteri</th>
                            <th>Paket</th>
                            <th>Miktar</th>
                            <th>Dönem</th>
                            <th>Durum</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="waitingTableBody">
                        <tr>
                            <td colspan="6" class="empty-state">Onay bekleyen tarife bulunamadı.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <div class="footer">
            <div class="console" id="consoleBox">
                <p class="console-line"><span class="console-prefix">tiko@izibiz</span><span class="console-info">Hazır. Müşteri verileri bekleniyor…</span></p>
            </div>
            <span id="statusText"></span>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="packageModalBackdrop">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title" id="pkgModalTitle">Yeni Paket Tanımla</div>
            <button type="button" class="modal-close" id="btnPkgClose">&times;</button>
        </div>
        <div class="modal-body">
            <div>
                <div class="field-label">Müşteri ID</div>
                <input type="number" class="field-input" id="pkgCustomerId" placeholder="Örn: 149154" min="1">
            </div>
            <div>
                <div class="field-label">Tarife Planı</div>
                <select class="field-select" id="pkgPlanSelect">
                    <option value="">Plan seçin</option>
                </select>
            </div>
            <div class="field-row">
                <div>
                    <div class="field-label">Başlangıç Tarihi</div>
                    <input type="date" class="field-input" id="pkgStartDate">
                </div>
                <div>
                    <div class="field-label">Süre (ay)</div>
                    <input type="number" class="field-input" id="pkgDuration" min="1">
                </div>
            </div>
            <div class="field-row">
                <div>
                    <div class="field-label">Miktar</div>
                    <input type="number" class="field-input" id="pkgAmount" min="1">
                </div>
                <div>
                    <div class="field-label">Ödeme Tipi</div>
                    <select class="field-select" id="pkgPaymentType">
                        <option value="CREDIT_CARD">Kredi Kartı</option>
                        <option value="OTHER">Diğer</option>
                    </select>
                </div>
            </div>
            <div>
                <label style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--text-muted);">
                    <input type="checkbox" id="pkgBilled" checked>
                    Faturalansın (billed)
                </label>
            </div>
            <div>
                <div class="field-label">Not</div>
                <textarea class="field-textarea" id="pkgNote" placeholder="İsteğe bağlı açıklama"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="btnPkgCancel">Vazgeç</button>
            <button type="button" class="btn-primary" id="btnPkgSave">Paket Ata</button>
        </div>
    </div>
</div>

<script>
    const apiBase = '{{ url('/') }}';

    const customersTbody = document.getElementById('customersTableBody');
    const tariffsTbody = document.getElementById('tariffsTableBody');
    const customersCount = document.getElementById('customersCount');
    const tariffsCount = document.getElementById('tariffsCount');
    const searchInput = document.getElementById('customerSearch');
    const selectedCustomerMeta = document.getElementById('selectedCustomerMeta');
    const statusText = document.getElementById('statusText');
    const consoleBox = document.getElementById('consoleBox');
    const customersPageInfo = document.getElementById('customersPageInfo');
    const btnPrevPage = document.getElementById('btnPrevPage');
    const btnNextPage = document.getElementById('btnNextPage');
    const btnRefreshCache = document.getElementById('btnRefreshCache');
    const btnNewCredit = document.getElementById('btnNewCredit');
    const tabCustomers = document.getElementById('tabCustomers');
    const tabPackages = document.getElementById('tabPackages');
    const customersView = document.getElementById('customersView');
    const packagesView = document.getElementById('packagesView');
    const plansTableBody = document.getElementById('plansTableBody');
    const waitingTableBody = document.getElementById('waitingTableBody');
    const plansCount = document.getElementById('plansCount');
    const waitingCount = document.getElementById('waitingCount');
    const btnNewPackage = document.getElementById('btnNewPackage');
    const packageModalBackdrop = document.getElementById('packageModalBackdrop');
    const pkgCustomerId = document.getElementById('pkgCustomerId');
    const pkgPlanSelect = document.getElementById('pkgPlanSelect');
    const pkgStartDate = document.getElementById('pkgStartDate');
    const pkgDuration = document.getElementById('pkgDuration');
    const pkgAmount = document.getElementById('pkgAmount');
    const pkgPaymentType = document.getElementById('pkgPaymentType');
    const pkgBilled = document.getElementById('pkgBilled');
    const pkgNote = document.getElementById('pkgNote');
    const btnPkgClose = document.getElementById('btnPkgClose');
    const btnPkgCancel = document.getElementById('btnPkgCancel');
    const btnPkgSave = document.getElementById('btnPkgSave');
    const pkgModalTitle = document.getElementById('pkgModalTitle');

    let customers = [];
    let filteredCustomers = [];
    let selectedCustomerId = null;
    let selectedCustomer = null;
    let currentPage = 0;
    let totalPages = 1;
    let totalElements = 0;
    const pageSize = 20;
    let currentQuery = '';
    let plansLoaded = false;
    let prepaidPlans = [];
    let packageModalContext = 'generic'; // 'customer' | 'packages'

    function setStatus(text) {
        statusText.textContent = text;
    }

    function pushConsoleLine(text, type = 'info') {
        if (!consoleBox) return;
        const p = document.createElement('p');
        p.className = 'console-line';
        const prefix = document.createElement('span');
        prefix.className = 'console-prefix';
        prefix.textContent = 'tiko@izibiz';
        const msg = document.createElement('span');
        if (type === 'ok') msg.className = 'console-ok';
        else if (type === 'error') msg.className = 'console-error';
        else msg.className = 'console-info';
        msg.textContent = ' ' + text;
        p.appendChild(prefix);
        p.appendChild(msg);
        consoleBox.appendChild(p);
        consoleBox.scrollTop = consoleBox.scrollHeight;
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const d = new Date(dateStr);
        if (Number.isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('tr-TR');
    }

    function renderCustomers() {
        customersTbody.innerHTML = '';

        if (!filteredCustomers.length) {
            customersTbody.innerHTML = '<tr><td colspan="6" class="empty-state">Eşleşen müşteri bulunamadı.</td></tr>';
            customersCount.textContent = '0';
            return;
        }

        customersCount.textContent = String(filteredCustomers.length);

        filteredCustomers.forEach(customer => {
            const tr = document.createElement('tr');
            tr.dataset.id = customer.id;

            const fullName = customer.commercialName
                || [customer.firstName, customer.lastName].filter(Boolean).join(' ')
                || '-';

            const address = (customer.addressList && customer.addressList[0]) || null;
            const city = address && address.city ? address.city : '-';

            const statusVal = customer.status;
            let statusLabel = 'Bilinmiyor';
            let statusClass = 'gray';
            if (statusVal === 'A') {
                statusLabel = 'Aktif';
                statusClass = 'green';
            } else if (statusVal === 'C') {
                statusLabel = 'İptal';
                statusClass = 'red';
            } else if (statusVal === 'N') {
                statusLabel = 'Yeni';
                statusClass = 'yellow';
            }

            const companyType = customer.companyTypeDesc || '-';

            tr.innerHTML = `
                <td>
                    <div style="font-size:12px;font-weight:500;">${fullName}</div>
                    <div class="meta">ID: <strong>${customer.id}</strong> &middot; Hesap: ${customer.accountRefName || '-'}</div>
                </td>
                <td>${customer.vknTckno || '-'}</td>
                <td>
                    <span class="pill">
                        <span class="pill-dot ${statusClass}"></span>
                        <span class="pill-label">${statusLabel}</span>
                    </span>
                </td>
                <td>${companyType}</td>
                <td>${city}</td>
                <td>${formatDate(customer.statusDate)}</td>
            `;

            if (customer.id === selectedCustomerId) {
                tr.classList.add('selected');
            }

            tr.addEventListener('click', () => {
                document.querySelectorAll('#customersTableBody tr').forEach(row => row.classList.remove('selected'));
                tr.classList.add('selected');
                selectedCustomerId = customer.id;
                selectedCustomer = customer;
                btnNewCredit.disabled = false;
                loadTariffsForCustomer(customer);
            });

            customersTbody.appendChild(tr);
        });
    }

    function updatePaginationUI() {
        const pageDisplay = currentPage + 1;
        const totalDisplay = totalPages || 1;
        customersPageInfo.textContent = 'Sayfa ' + pageDisplay + ' / ' + totalDisplay + ' · Toplam ' + totalElements + ' müşteri';
        btnPrevPage.disabled = currentPage <= 0;
        btnNextPage.disabled = currentPage >= totalPages - 1;
    }

    async function loadCustomers(page = 0, options = {}) {
        currentPage = page;
        const q = typeof options.q === 'string' ? options.q : (searchInput.value || '');
        currentQuery = q;
        const refresh = options.refresh === true;

        customersTbody.innerHTML = '<tr><td colspan="6" class="empty-state">Müşteriler yükleniyor...</td></tr>';
        pushConsoleLine('auth: İzibiz token isteği gönderiliyor... (sayfa ' + (page + 1) + (q ? ', arama: "' + q + '"' : '') + ')', 'info');

        try {
            let url = apiBase + '/api/izibiz/customers?page=' + page + '&size=' + pageSize;
            if (q) {
                url += '&q=' + encodeURIComponent(q);
            }
            if (refresh) {
                url += '&refresh=1';
            }

            const res = await fetch(url);
            if (!res.ok) {
                throw new Error('HTTP ' + res.status);
            }
            const json = await res.json();
            customers = Array.isArray(json.data) ? json.data : [];
            filteredCustomers = [...customers];
            const pageable = json.pageable || {};
            totalPages = typeof pageable.totalPages === 'number' ? pageable.totalPages : 1;
            totalElements = typeof pageable.totalElements === 'number' ? pageable.totalElements : customers.length;
            renderCustomers();
            updatePaginationUI();
            pushConsoleLine('auth: Token alındı, müşteri listesi isteniyor...', 'ok');
            pushConsoleLine('customers: Sayfa ' + (currentPage + 1) + '/' + totalPages + ', bu sayfada ' + customers.length + ' kayıt.', 'ok');
        } catch (e) {
            console.error(e);
            customersTbody.innerHTML = '<tr><td colspan="6" class="empty-state">Müşteriler yüklenirken hata oluştu.</td></tr>';
            pushConsoleLine('hata: Müşteri listesi alınamadı. ' + (e.message || ''), 'error');
        }
    }

    function renderTariffs(tariffs, customer) {
        tariffsTbody.innerHTML = '';

        if (!tariffs.length) {
            tariffsTbody.innerHTML = '<tr><td colspan="5" class="empty-state">Bu müşteriye ait paket bulunamadı.</td></tr>';
            tariffsCount.textContent = '0';
            selectedCustomerMeta.innerHTML = `
                <span class="meta">Seçili müşteri: <strong>${customer.commercialName || (customer.firstName || '') + ' ' + (customer.lastName || '')}</strong></span>
            `;
            return;
        }

        tariffsCount.textContent = String(tariffs.length);

        const name = customer.commercialName || [customer.firstName, customer.lastName].filter(Boolean).join(' ');
        selectedCustomerMeta.innerHTML = `
            <span class="meta">
                Seçili müşteri: <strong>${name}</strong> &middot;
                VKN/TCKN: <strong>${customer.vknTckno || '-'}</strong>
            </span>
        `;

        tariffs.forEach(t => {
            const tr = document.createElement('tr');

            const status = t.status || {};
            let cls = 'gray';
            if (status.value === 'APPROVED') cls = 'green';
            if (status.value === 'NO_CREDIT') cls = 'gray';

            const period = `${formatDate(t.startDate)} &mdash; ${formatDate(t.expireDate)}`;

            tr.innerHTML = `
                <td>
                    <div style="font-size:12px;font-weight:500;">${t.name || '-'}</div>
                    <div class="meta">ID: <strong>${t.id}</strong> &middot; Bundle: ${t.bundleId || '-'}</div>
                </td>
                <td>${t.amount ?? '-'} AD</td>
                <td>${t.remaining ?? '-'} AD</td>
                <td>
                    <span class="status-badge ${cls}">
                        <span class="status-dot"></span>
                        ${status.label || status.value || 'Bilinmiyor'}
                    </span>
                </td>
                <td>${period}</td>
            `;

            tariffsTbody.appendChild(tr);
        });
    }

    async function loadTariffsForCustomer(customer) {
        tariffsTbody.innerHTML = '<tr><td colspan="5" class="empty-state">Paketler yükleniyor...</td></tr>';
        tariffsCount.textContent = '0';
       // setStatus('Paketler yükleniyor...');
        pushConsoleLine('tariffs: Müşteri #' + customer.id + ' için paket isteği gönderiliyor...', 'info');

        try {
            const res = await fetch(apiBase + '/api/izibiz/customers/' + customer.id + '/tariffs');
            if (!res.ok) {
                throw new Error('HTTP ' + res.status);
            }
            const json = await res.json();
            const tariffs = Array.isArray(json.data) ? json.data : [];
            renderTariffs(tariffs, customer);
            //setStatus('Paketler yüklendi');
            pushConsoleLine('tariffs: ' + tariffs.length + ' adet paket kaydı alındı.', 'ok');
        } catch (e) {
            console.error(e);
            tariffsTbody.innerHTML = '<tr><td colspan="5" class="empty-state">Paketler yüklenirken hata oluştu.</td></tr>';
            tariffsCount.textContent = '0';
           // setStatus('Paketler yüklenirken hata oluştu');
            pushConsoleLine('hata: Paket listesi alınamadı. ' + (e.message || ''), 'error');
        }
    }

    searchInput.addEventListener('input', () => {
        // Arama yapıldığında API üzerinden filtrele, ilk sayfaya dön
        loadCustomers(0, { q: searchInput.value || '' });
    });

    btnPrevPage.addEventListener('click', () => {
        if (currentPage > 0) {
            loadCustomers(currentPage - 1, { q: currentQuery });
        }
    });

    btnNextPage.addEventListener('click', () => {
        if (currentPage < totalPages - 1) {
            loadCustomers(currentPage + 1, { q: currentQuery });
        }
    });

    btnRefreshCache.addEventListener('click', () => {
        pushConsoleLine('cache: müşteri cache yenileme isteği gönderiliyor...', 'info');
        loadCustomers(0, { q: currentQuery, refresh: true });
    });

    async function loadPrepaidPlans() {
        plansTableBody.innerHTML = '<tr><td colspan="6" class="empty-state">Tarife planları yükleniyor...</td></tr>';
        try {
            const res = await fetch(apiBase + '/api/izibiz/tariff-plans/prepaid');
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const json = await res.json();
            const items = Array.isArray(json.data || json) ? (json.data || json) : [];
            prepaidPlans = items;
            plansTableBody.innerHTML = '';
            plansCount.textContent = String(items.length);
            if (!items.length) {
                plansTableBody.innerHTML = '<tr><td colspan="6" class="empty-state">Plan bulunamadı.</td></tr>';
                return;
            }
            items.forEach(p => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${p.name || '-'}</td>
                    <td>${p.type || '-'}</td>
                    <td>${p.amount ?? '-'}</td>
                    <td>${p.price ?? '-'}</td>
                    <td>${p.duration ?? '-'}</td>
                    <td>${p.status || '-'}</td>
                `;
                plansTableBody.appendChild(tr);
            });

            // Modal plan select doldur
            pkgPlanSelect.innerHTML = '<option value="">Plan seçin</option>';
            prepaidPlans.forEach(p => {
                const opt = document.createElement('option');
                opt.value = String(p.id);
                opt.textContent = (p.name || '-') + ' (' + (p.amount ?? '-') + ' AD)';
                pkgPlanSelect.appendChild(opt);
            });
        } catch (e) {
            console.error(e);
            plansTableBody.innerHTML = '<tr><td colspan="6" class="empty-state">Planlar yüklenirken hata oluştu.</td></tr>';
        }
    }

    async function loadWaitingTariffs() {
        waitingTableBody.innerHTML = '<tr><td colspan="6" class="empty-state">Onay bekleyen tarifeler yükleniyor...</td></tr>';
        try {
            const res = await fetch(apiBase + '/api/izibiz/tariffs/waiting-approval');
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const json = await res.json();
            const data = json.data || json;
            const contents = Array.isArray(data.contents) ? data.contents : (Array.isArray(data) ? data : []);
            waitingTableBody.innerHTML = '';
            waitingCount.textContent = String(contents.length);
            if (!contents.length) {
                waitingTableBody.innerHTML = '<tr><td colspan="6" class="empty-state">Onay bekleyen tarife bulunamadı.</td></tr>';
                return;
            }
            contents.forEach(t => {
                const tr = document.createElement('tr');
                const period = `${formatDate(t.startDate)} - ${formatDate(t.expireDate)}`;
                tr.innerHTML = `
                    <td>${t.commercialName || t.identifier || '-'}</td>
                    <td>${t.name || '-'}</td>
                    <td>${t.amount ?? '-'}</td>
                    <td>${period}</td>
                    <td>${(t.status && (t.status.label || t.status.value)) || '-'}</td>
                    <td><button type="button" class="btn-page btn-approve-tariff" data-id="${t.id}">Onayla</button></td>
                `;
                waitingTableBody.appendChild(tr);
            });

            document.querySelectorAll('.btn-approve-tariff').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.getAttribute('data-id');
                    pushConsoleLine('tariffs: #' + id + ' için onay isteği gönderiliyor...', 'info');
                    try {
                        const approveRes = await fetch(apiBase + '/api/izibiz/tariffs/approval', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: Number(id), type: 'PREPAID', approved: true }),
                        });
                        if (!approveRes.ok) throw new Error('HTTP ' + approveRes.status);
                        pushConsoleLine('tariffs: #' + id + ' onaylandı.', 'ok');
                        loadWaitingTariffs();
                    } catch (e) {
                        console.error(e);
                        pushConsoleLine('hata: Tarife onaylanamadı. ' + (e.message || ''), 'error');
                    }
                });
            });
        } catch (e) {
            console.error(e);
            waitingTableBody.innerHTML = '<tr><td colspan="6" class="empty-state">Onay bekleyen tarifeler yüklenirken hata oluştu.</td></tr>';
        }
    }

    tabCustomers.addEventListener('click', () => {
        tabCustomers.classList.add('active');
        tabPackages.classList.remove('active');
        customersView.style.display = 'grid';
        packagesView.style.display = 'none';
    });

    tabPackages.addEventListener('click', () => {
        tabPackages.classList.add('active');
        tabCustomers.classList.remove('active');
        customersView.style.display = 'none';
        packagesView.style.display = 'grid';
        if (!plansLoaded) {
            loadPrepaidPlans();
            loadWaitingTariffs();
            plansLoaded = true;
        }
    });

    btnNewPackage.addEventListener('click', () => {
        openAssignCreditModal(selectedCustomerId);
    });

    function closePackageModal() {
        packageModalBackdrop.classList.remove('open');
    }

    async function ensurePrepaidPlansLoaded() {
        if (prepaidPlans.length) return true;
        pushConsoleLine('plans: Kontör planları yükleniyor...', 'info');
        try {
            const res = await fetch(apiBase + '/api/izibiz/tariff-plans/prepaid');
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const json = await res.json();
            const items = Array.isArray(json.data) ? json.data : [];
            prepaidPlans = items;
            // select doldur
            pkgPlanSelect.innerHTML = '<option value="">Plan seçin</option>';
            prepaidPlans.forEach(p => {
                const opt = document.createElement('option');
                opt.value = String(p.id);
                opt.textContent = (p.name || '-') + ' (' + (p.amount ?? '-') + ' AD)';
                pkgPlanSelect.appendChild(opt);
            });
            return prepaidPlans.length > 0;
        } catch (e) {
            console.error(e);
            pushConsoleLine('hata: Kontör planları yüklenemedi. ' + (e.message || ''), 'error');
            return false;
        }
    }

    async function openAssignCreditModal(customerId) {
        const ok = await ensurePrepaidPlansLoaded();
        if (!ok) {
            pushConsoleLine('ui: Kontör planları gelmedi, modal açılamadı.', 'error');
            return;
        }

        packageModalContext = customerId ? 'customer' : 'packages';
        if (packageModalContext === 'customer') {
            pkgModalTitle.textContent = 'Yeni Kontör Tanımla';
        } else {
            pkgModalTitle.textContent = 'Yeni Paket Tanımla';
        }

        packageModalBackdrop.classList.add('open');
        const today = new Date().toISOString().slice(0, 10);
        pkgStartDate.value = today;
        pkgPlanSelect.value = prepaidPlans[0]?.id ? String(prepaidPlans[0].id) : '';
        pkgDuration.value = prepaidPlans[0]?.duration ?? '';
        pkgAmount.value = prepaidPlans[0]?.amount ?? '';

        if (customerId) {
            pkgCustomerId.value = String(customerId);
            pkgCustomerId.setAttribute('readonly', 'readonly');
            pkgCustomerId.style.opacity = '0.85';
        } else {
            pkgCustomerId.value = '';
            pkgCustomerId.removeAttribute('readonly');
            pkgCustomerId.style.opacity = '1';
        }
    }

    btnNewCredit.addEventListener('click', () => {
        if (!selectedCustomerId) {
            pushConsoleLine('ui: Önce bir müşteri seçmelisin.', 'info');
            return;
        }
        openAssignCreditModal(selectedCustomerId);
    });

    btnPkgClose.addEventListener('click', closePackageModal);
    btnPkgCancel.addEventListener('click', closePackageModal);
    packageModalBackdrop.addEventListener('click', (e) => {
        if (e.target === packageModalBackdrop) {
            closePackageModal();
        }
    });

    pkgPlanSelect.addEventListener('change', () => {
        const planId = Number(pkgPlanSelect.value || '0');
        const plan = prepaidPlans.find(p => Number(p.id) === planId);
        if (plan) {
            if (plan.duration != null) pkgDuration.value = plan.duration;
            if (plan.amount != null) pkgAmount.value = plan.amount;
        }
    });

    btnPkgSave.addEventListener('click', async () => {
        const customerId = Number(pkgCustomerId.value || '0');
        const planId = Number(pkgPlanSelect.value || '0');
        const startDate = pkgStartDate.value;
        const amount = Number(pkgAmount.value || '0');
        const duration = Number(pkgDuration.value || '0');
        const paymentType = pkgPaymentType.value || 'CREDIT_CARD';
        const billed = !!pkgBilled.checked;
        const note = pkgNote.value || null;

        if (!customerId || !planId || !startDate || !amount || !duration) {
            pushConsoleLine('ui: Müşteri, plan, başlangıç tarihi, miktar ve süre zorunludur.', 'error');
            return;
        }

        const plan = prepaidPlans.find(p => Number(p.id) === planId);
        const type = plan?.type || 'PREPAID';

        const payload = {
            id: planId,
            type,
            name: plan?.name || undefined,
            amount,
            price: plan?.price ?? 0,
            startDate,
            duration,
            note,
            billed,
            paymentType,
        };

        pushConsoleLine('tariffs: Müşteri #' + customerId + ' için paket atama isteği gönderiliyor...', 'info');

        try {
            const res = await fetch(apiBase + '/api/izibiz/customers/' + customerId + '/tariffs/assign', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            });
            if (!res.ok) {
                throw new Error('HTTP ' + res.status);
            }
            closePackageModal();
            pushConsoleLine('tariffs: Müşteri #' + customerId + ' için yeni paket atandı.', 'ok');
        } catch (e) {
            console.error(e);
            pushConsoleLine('hata: Paket atanamadı. ' + (e.message || ''), 'error');
        }
    });

    window.addEventListener('DOMContentLoaded', () => {
        btnNewCredit.disabled = true;
        loadCustomers(0, { q: '' });
    });
</script>
</body>
</html>

