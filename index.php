<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Thidasna Craft | Aurora Dashboard</title>
    <!-- Google Fonts + Font Awesome + Tailwind + GSAP + Chart.js + SweetAlert -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            background: radial-gradient(ellipse at 20% 30%, #0a0f2a, #030617);
            min-height: 100vh;
            padding: 1.5rem;
            position: relative;
        }
        
        /* animated ambient orbs */
        .orb-1, .orb-2, .orb-3 {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.5;
        }
        .orb-1 { width: 60vw; height: 60vw; background: #4f46e5; top: -20%; right: -10%; animation: floatOrb 22s infinite alternate ease-in-out; }
        .orb-2 { width: 50vw; height: 50vw; background: #ec4899; bottom: -15%; left: -10%; animation: floatOrb 26s infinite alternate-reverse; }
        .orb-3 { width: 40vw; height: 40vw; background: #06b6d4; top: 40%; left: 30%; animation: floatOrb 30s infinite alternate; opacity: 0.3; }
        
        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(5%, 8%) scale(1.3); }
        }
        
        /* modern glassmorphic card */
        .neo-glass {
            background: rgba(15, 25, 45, 0.65);
            backdrop-filter: blur(16px);
            border-radius: 2rem;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 25px 40px -12px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.03);
            transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }
        .neo-glass:hover {
            border-color: rgba(99,102,241,0.5);
            box-shadow: 0 30px 50px -15px rgba(0,0,0,0.6);
        }
        
        /* stat tiles vibrant */
        .stat-tile {
            background: linear-gradient(145deg, rgba(30,41,59,0.85), rgba(15,23,42,0.95));
            backdrop-filter: blur(12px);
            border-radius: 1.8rem;
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .stat-tile:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: rgba(139,92,246,0.6);
            box-shadow: 0 25px 35px -12px rgba(79,70,229,0.3);
        }
        
        .gradient-text {
            background: linear-gradient(120deg, #c084fc, #60a5fa, #f472b6);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
        
        .nav-pill {
            transition: all 0.2s ease;
            cursor: pointer;
            border-radius: 2rem;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
        }
        .nav-pill.active {
            background: linear-gradient(95deg, rgba(99,102,241,0.25), rgba(139,92,246,0.2));
            backdrop-filter: blur(8px);
            border: 1px solid rgba(99,102,241,0.4);
            color: #a5b4fc;
        }
        .nav-pill:not(.active):hover {
            background: rgba(255,255,255,0.05);
        }
        
        /* custom scroll */
        .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: #1e293b; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 10px; }
        
        .btn-gradient {
            background: linear-gradient(95deg, #4f46e5, #7c3aed);
            transition: all 0.2s;
        }
        .btn-gradient:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px -5px #4f46e5;
        }
        
        .footer-aura {
            background: rgba(2,6,23,0.6);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        
        input, select, .swal2-input {
            background: #0f172a !important;
            border: 1px solid #334155 !important;
            border-radius: 1rem !important;
            padding: 0.75rem 1rem !important;
            color: #e2e8f0 !important;
            transition: all 0.2s;
        }
        input:focus, select:focus {
            border-color: #818cf8 !important;
            outline: none;
            box-shadow: 0 0 0 2px rgba(99,102,241,0.3);
        }
        
        .table-modern th {
            background: rgba(30,41,59,0.6);
            backdrop-filter: blur(8px);
            color: #cbd5e1;
            font-weight: 600;
        }
        .invoice-row, .item-row {
            transition: all 0.2s;
            cursor: pointer;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .invoice-row:hover, .item-row:hover {
            background: rgba(99,102,241,0.1);
        }
        
        button, .stat-tile, .nav-pill {
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="relative z-10 max-w-7xl mx-auto">
    <!-- Header with holographic effect -->
    <div class="neo-glass p-6 mb-6 transform transition-all duration-700" id="headerCard">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-crown text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight"><span class="gradient-text">THIDASNA CRAFT</span></h1>
                    <p class="text-slate-300 text-sm">✦ Premium Handicraft Management Suite ✦</p>
                </div>
            </div>
            <div class="text-right bg-slate-800/40 px-5 py-2 rounded-2xl backdrop-blur-sm">
                <p class="text-slate-200 text-sm"><i class="far fa-calendar-alt text-indigo-300 mr-2"></i><span id="currentDate"></span></p>
                <p class="text-slate-300 text-xs mt-1"><i class="far fa-clock text-indigo-300 mr-2"></i><span id="currentTime"></span></p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs - modern pill style -->
    <div class="neo-glass p-3 mb-8 flex flex-wrap gap-3 justify-center md:justify-start" id="navContainer">
        <div class="nav-pill active" data-tab="dashboard">
            <i class="fas fa-chart-line mr-2"></i> Insights Hub
        </div>
        <div class="nav-pill" data-tab="items">
            <i class="fas fa-cubes mr-2"></i> Inventory
        </div>
        <div class="nav-pill" data-tab="invoices">
            <i class="fas fa-file-invoice-dollar mr-2"></i> Billing Archive
        </div>
    </div>

    <!-- DASHBOARD SECTION -->
    <div id="dashboardSection" class="tab-section">
        <!-- Metric Cards with glassmorph -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="stat-tile p-6 text-white" data-stat="items">
                <div class="flex justify-between items-start">
                    <div><p class="text-indigo-200 text-sm uppercase tracking-wide">Total Items</p><p class="text-4xl font-black mt-2" id="totalItems">0</p></div>
                    <div class="w-12 h-12 bg-indigo-500/30 rounded-2xl flex items-center justify-center backdrop-blur"><i class="fas fa-boxes text-2xl"></i></div>
                </div>
                <div class="mt-4 text-indigo-200 text-xs"><i class="fas fa-chart-simple"></i> Live inventory</div>
            </div>
            <div class="stat-tile p-6 text-white" data-stat="bills">
                <div class="flex justify-between items-start">
                    <div><p class="text-sky-200 text-sm uppercase">Total Bills</p><p class="text-4xl font-black mt-2" id="totalBills">0</p></div>
                    <div class="w-12 h-12 bg-sky-500/30 rounded-2xl flex items-center justify-center"><i class="fas fa-receipt text-2xl"></i></div>
                </div>
                <div class="mt-4 text-sky-200 text-xs"><i class="fas fa-chart-line"></i> All time invoices</div>
            </div>
            <div class="stat-tile p-6 text-white" data-stat="sales">
                <div class="flex justify-between items-start">
                    <div><p class="text-amber-200 text-sm uppercase">Today's Revenue</p><p class="text-4xl font-black mt-2">Rs <span id="todaySales">0</span></p></div>
                    <div class="w-12 h-12 bg-amber-500/30 rounded-2xl flex items-center justify-center"><i class="fas fa-shopping-cart text-2xl"></i></div>
                </div>
                <div class="mt-4 text-amber-200 text-xs"><i class="fas fa-calendar-day"></i> Today's performance</div>
            </div>
            <div class="stat-tile p-6 text-white btn-gradient" id="quickBillBtn">
                <div class="flex justify-between items-start">
                    <div><p class="text-pink-200 text-sm uppercase">Quick Action</p><p class="text-xl font-bold mt-2">⚡ New Billing</p></div>
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center"><i class="fas fa-bolt text-2xl"></i></div>
                </div>
                <div class="mt-4 text-pink-200 text-xs"><i class="fas fa-arrow-right"></i> Instant invoice creator</div>
            </div>
        </div>

        <!-- Charts row enhanced -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
            <div class="neo-glass p-6">
                <div class="flex items-center gap-3 mb-5 border-b border-white/10 pb-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center"><i class="fas fa-chart-simple text-indigo-300 text-xl"></i></div>
                    <div><h3 class="text-xl font-bold text-white">Sales Overview</h3><p class="text-slate-400 text-xs">Last 7 days trend</p></div>
                </div>
                <canvas id="salesChart" height="240"></canvas>
            </div>
            <div class="neo-glass p-6">
                <div class="flex items-center gap-3 mb-5 border-b border-white/10 pb-3">
                    <div class="w-10 h-10 rounded-xl bg-fuchsia-500/20 flex items-center justify-center"><i class="fas fa-chart-pie text-fuchsia-300 text-xl"></i></div>
                    <div><h3 class="text-xl font-bold text-white">Top Performers</h3><p class="text-slate-400 text-xs">Revenue distribution by item</p></div>
                </div>
                <canvas id="pieChart" height="240"></canvas>
            </div>
        </div>

        <!-- Recent Invoices Table with glow -->
        <div class="neo-glass p-6">
            <div class="flex flex-wrap justify-between items-center mb-5 gap-4">
                <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center"><i class="fas fa-file-invoice text-emerald-300 text-xl"></i></div><div><h3 class="text-xl font-bold text-white">Recent Transactions</h3><p class="text-slate-400 text-xs">Last 10 invoices</p></div></div>
                <div class="flex gap-3"><input type="text" id="searchInvoice" placeholder="🔍 Invoice number..." class="px-4 py-2 w-56 text-sm"><button onclick="searchInvoices()" class="bg-indigo-600 hover:bg-indigo-500 px-4 py-2 rounded-xl text-sm transition"><i class="fas fa-search"></i> Search</button><button onclick="refreshInvoices()" class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-xl text-sm"><i class="fas fa-sync-alt"></i></button></div>
            </div>
            <div class="overflow-x-auto custom-scroll max-h-[400px] overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-slate-800/80 backdrop-blur"><tr class="text-left text-slate-300"><th class="p-3">Invoice No</th><th class="p-3">Date & Time</th><th class="p-3 text-right">Amount (Rs)</th><th class="p-3 text-center">Action</th></tr></thead>
                    <tbody id="invoicesTableBody"><tr><td colspan="4" class="text-center py-10 text-slate-400"><i class="fas fa-spinner fa-pulse text-2xl"></i><p class="mt-2">Fetching invoices...</p></td></tr></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ITEMS MANAGEMENT SECTION -->
    <div id="itemsSection" class="tab-section hidden">
        <div class="neo-glass p-6">
            <div class="flex flex-wrap justify-between items-center mb-6">
                <div class="flex gap-3 items-center"><div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center"><i class="fas fa-cubes text-indigo-300 text-xl"></i></div><div><h2 class="text-2xl font-bold text-white">Inventory Manager</h2><p class="text-slate-400">Add, edit or remove products</p></div></div>
                <button onclick="openAddItemModal()" class="btn-gradient px-6 py-2 rounded-xl text-white font-semibold shadow-lg"><i class="fas fa-plus-circle mr-2"></i> New Item</button>
            </div>
            <div class="mb-6"><div class="relative"><i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i><input type="text" id="searchItem" placeholder="Search by code or description..." class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white" onkeyup="filterItems()"></div></div>
            <div class="overflow-x-auto custom-scroll max-h-[500px] overflow-y-auto"><table class="w-full"><thead class="sticky top-0 bg-slate-800/90"><tr class="text-slate-300"><th class="p-3 text-left">Item Code</th><th class="p-3 text-left">Description</th><th class="p-3 text-right">Price (Rs)</th><th class="p-3 text-center">Actions</th></tr></thead><tbody id="itemsTableBody"></tbody></table></div>
        </div>
    </div>

    <!-- INVOICES MANAGEMENT SECTION -->
    <div id="invoicesSection" class="tab-section hidden">
        <div class="neo-glass p-6">
            <div class="flex gap-3 items-center mb-6"><div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center"><i class="fas fa-file-invoice-dollar text-emerald-300 text-xl"></i></div><div><h2 class="text-2xl font-bold text-white">Invoice Vault</h2><p class="text-slate-400">Search, filter & reprint bills</p></div></div>
            <div class="grid md:grid-cols-3 gap-5 mb-6"><div><label class="text-slate-300 text-sm block mb-1">Invoice No</label><input type="text" id="searchInvoiceManage" placeholder="e.g., INV-001" class="w-full"></div><div><label class="text-slate-300 text-sm block mb-1">Start Date</label><input type="date" id="startDateInvoice" class="w-full"></div><div><label class="text-slate-300 text-sm block mb-1">End Date</label><input type="date" id="endDateInvoice" class="w-full"></div></div>
            <div class="flex gap-3 mb-7"><button onclick="searchInvoicesManage()" class="btn-gradient px-6 py-2 rounded-xl"><i class="fas fa-filter mr-2"></i> Apply Filters</button><button onclick="resetInvoiceSearch()" class="bg-slate-700 hover:bg-slate-600 px-6 py-2 rounded-xl"><i class="fas fa-undo-alt mr-2"></i> Reset</button></div>
            <div class="overflow-x-auto custom-scroll max-h-[450px] overflow-y-auto"><table class="w-full"><thead class="sticky top-0 bg-slate-800/90"><tr class="text-slate-300"><th class="p-3 text-left">Invoice No</th><th class="p-3 text-left">Date & Time</th><th class="p-3 text-right">Amount (Rs)</th><th class="p-3 text-center">Reprint</th></tr></thead><tbody id="allInvoicesTableBody"><tr><td colspan="4" class="text-center py-10 text-slate-400">Loading invoices...</td></tr></tbody></table></div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-aura rounded-2xl p-6 mt-12 text-center text-slate-300">
        <div class="flex flex-wrap justify-between items-center gap-4"><div><p class="text-sm">© 2026-2027 Thidasna Craft - All Rights Reserved</p><p class="text-xs opacity-70">Galle Road, Balapitiya, Sri Lanka</p></div><div><i class="fas fa-gem text-indigo-400 text-xl"></i><p class="text-xs font-mono mt-1">Vexel IT by Kavizz | 📞 +94 74 089 0730</p></div><div><i class="fas fa-certificate text-indigo-400"></i><p class="text-xs">Version 4.0 · Aurora Suite</p></div></div>
    </div>
</div>

<script>
    let salesChart, pieChart, allItems = [];
    // Helper: fetch JSON endpoints (mocked but works with backend)
    async function apiGet(url) { let res = await fetch(url); return res.json(); }
    
    // Date & time update
    function updateDateTime() { const n=new Date(); document.getElementById('currentDate').innerText=n.toLocaleDateString('en-LK',{weekday:'long',year:'numeric',month:'long',day:'numeric'}); document.getElementById('currentTime').innerText=n.toLocaleTimeString('en-LK'); }
    updateDateTime(); setInterval(updateDateTime,1000);
    
    // Tab switching with GSAP fade
    function switchTab(tabId) {
        document.querySelectorAll('.tab-section').forEach(sec => sec.classList.add('hidden'));
        document.getElementById(`${tabId}Section`).classList.remove('hidden');
        document.querySelectorAll('.nav-pill').forEach(p => p.classList.remove('active'));
        document.querySelector(`.nav-pill[data-tab="${tabId}"]`).classList.add('active');
        if(tabId === 'dashboard') { loadDashboardData(); gsap.fromTo("#dashboardSection", {opacity:0,y:20},{opacity:1,y:0,duration:0.5}); }
        if(tabId === 'items') { loadItems(); gsap.fromTo("#itemsSection", {opacity:0,y:20},{opacity:1,y:0,duration:0.5}); }
        if(tabId === 'invoices') { loadAllInvoices(); gsap.fromTo("#invoicesSection", {opacity:0,y:20},{opacity:1,y:0,duration:0.5}); }
    }
    document.querySelectorAll('.nav-pill').forEach(btn => { btn.addEventListener('click',()=>switchTab(btn.dataset.tab)); });
    document.querySelectorAll('.stat-tile').forEach(tile => { tile.addEventListener('click',()=>{ if(tile.dataset.stat==='items') switchTab('items'); else if(tile.dataset.stat==='bills') switchTab('invoices'); else if(tile.id==='quickBillBtn') redirectToBilling(); }); });
    document.getElementById('quickBillBtn')?.addEventListener('click',()=>redirectToBilling());
    
    async function loadDashboardData() {
        try {
            let data = await apiGet('dashboard_data.php');
            if(data.success) {
                document.getElementById('totalItems').innerText = data.total_items||0;
                document.getElementById('totalBills').innerText = data.total_bills;
                document.getElementById('todaySales').innerText = parseFloat(data.today_sales).toFixed(2);
                updateSalesChart(data.last_7_days);
                updatePieChart(data.top_items);
                updateInvoicesTable(data.recent_invoices);
                gsap.fromTo(".stat-tile", {scale:0.95},{scale:1,duration:0.4,stagger:0.05});
            }
        } catch(e) { console.warn(e); }
    }
    
    function updateSalesChart(salesData) { const ctx=document.getElementById('salesChart').getContext('2d'); if(salesChart) salesChart.destroy(); salesChart = new Chart(ctx,{type:'line',data:{labels:salesData.map(i=>i.date),datasets:[{label:'Sales (Rs)',data:salesData.map(i=>parseFloat(i.total)),borderColor:'#818cf8',backgroundColor:'rgba(99,102,241,0.2)',borderWidth:3,fill:true,tension:0.3}]},options:{responsive:true,plugins:{legend:{labels:{color:'#cbd5e1'}}},scales:{y:{beginAtZero:true,ticks:{callback:v=>'Rs '+v,color:'#94a3b8'},grid:{color:'#334155'}},x:{ticks:{color:'#94a3b8'},grid:{display:false}}}}});
    }
    function updatePieChart(topItems) { const ctx=document.getElementById('pieChart').getContext('2d'); if(pieChart) pieChart.destroy(); pieChart = new Chart(ctx,{type:'doughnut',data:{labels:topItems.map(i=>i.description),datasets:[{data:topItems.map(i=>parseFloat(i.total_sales)),backgroundColor:['#6366f1','#f59e0b','#10b981','#ec4899','#06b6d4'],borderWidth:0}]},options:{responsive:true,plugins:{legend:{position:'bottom',labels:{color:'#cbd5e1'}},tooltip:{callbacks:{label:(ctx)=>`${ctx.label}: Rs ${ctx.raw.toFixed(2)}`}}}}});
    }
    function updateInvoicesTable(invoices) { let tbody=document.getElementById('invoicesTableBody'); if(!invoices?.length){ tbody.innerHTML='<tr><td colspan="4" class="text-center py-8 text-slate-400">✨ No invoices found</td></tr>'; return; } tbody.innerHTML=invoices.map(inv=>`<tr class="invoice-row border-b border-white/5" onclick="viewInvoiceDetails('${inv.invoice_no}')"><td class="p-3 font-mono text-indigo-300">${inv.invoice_no}</td><td class="p-3 text-slate-300">${new Date(inv.created_at).toLocaleString()}</td><td class="p-3 text-right font-bold text-emerald-300">Rs ${parseFloat(inv.final_total).toFixed(2)}</td><td class="p-3 text-center"><button onclick="event.stopPropagation(); reprintInvoice('${inv.invoice_no}')" class="bg-blue-600/70 hover:bg-blue-500 px-3 py-1 rounded-lg text-xs"><i class="fas fa-print"></i> Reprint</button></td></tr>`).join(''); }
    
    async function loadItems(){ try{ allItems=await apiGet('get_items.php'); displayItems(allItems); }catch(e){} }
    function displayItems(items){ let tbody=document.getElementById('itemsTableBody'); if(!items.length){ tbody.innerHTML='<tr><td colspan="4" class="text-center py-10 text-slate-400">📦 No items yet</td></tr>'; return; } tbody.innerHTML=items.map(item=>`<tr class="item-row border-b border-white/5"><td class="p-3 font-mono text-indigo-300">${escapeHtml(item.item_code)}</td><td class="p-3 text-slate-200">${escapeHtml(item.description)}</td><td class="p-3 text-right text-emerald-300 font-bold">Rs ${parseFloat(item.price).toFixed(2)}</td><td class="p-3 text-center"><button onclick="editItem(${item.id})" class="bg-amber-600/70 hover:bg-amber-500 px-3 py-1 rounded-lg text-xs mr-2"><i class="fas fa-edit"></i> Edit</button><button onclick="deleteItem(${item.id})" class="bg-rose-600/70 hover:bg-rose-500 px-3 py-1 rounded-lg text-xs"><i class="fas fa-trash-alt"></i> Del</button></td></tr>`).join(''); }
    function filterItems(){ let s=document.getElementById('searchItem').value.toLowerCase(); displayItems(allItems.filter(i=>i.item_code.toLowerCase().includes(s)||i.description.toLowerCase().includes(s))); }
    function generateItemCode(){ return `ITEM${new Date().getFullYear().toString().slice(-2)}${(new Date().getMonth()+1).toString().padStart(2,'0')}${new Date().getDate().toString().padStart(2,'0')}${Math.floor(Math.random()*1000)}`; }
    function openAddItemModal(){ let auto=generateItemCode(); Swal.fire({title:'<span class="text-white">✨ Create New Item</span>',background:'#0f172a',html:`<div class="text-left"><label class="text-slate-300">Item Code</label><input id="itemCode" class="swal2-input" value="${auto}"><p class="text-xs text-slate-500">Auto if empty</p><label class="text-slate-300 mt-2">Description</label><input id="itemDesc" class="swal2-input" placeholder="Product name"><label class="text-slate-300 mt-2">Price (Rs)</label><input id="itemPrice" type="number" step="0.01" class="swal2-input" placeholder="0.00"></div>`,showCancelButton:true,confirmButtonText:'➕ Add Item',preConfirm:()=>{ let desc=document.getElementById('itemDesc').value,price=document.getElementById('itemPrice').value; if(!desc||!price||price<=0) Swal.showValidationMessage('Valid description & price required'); return {code:document.getElementById('itemCode').value||generateItemCode(),desc,price}; }}).then(async(res)=>{ if(res.isConfirmed){ let fd=new URLSearchParams(); fd.append('item_code',res.value.code); fd.append('description',res.value.desc); fd.append('price',res.value.price); let data=await(await fetch('add_item.php',{method:'POST',body:fd})).json(); if(data.success){ Swal.fire('Success','Item added','success'); loadItems(); loadDashboardData(); } else Swal.fire('Error',data.error||'Failed','error'); } }); }
    async function editItem(id){ let item=await apiGet(`get_item_by_id.php?id=${id}`); Swal.fire({title:'✏️ Edit Item',background:'#0f172a',html:`<div class="text-left"><label class="text-slate-300">Code</label><input id="editCode" class="swal2-input" value="${item.item_code}" disabled><label class="text-slate-300">Description</label><input id="editDesc" class="swal2-input" value="${escapeHtml(item.description)}"><label class="text-slate-300">Price</label><input id="editPrice" type="number" step="0.01" class="swal2-input" value="${item.price}"></div>`,showCancelButton:true,confirmButtonText:'Update',preConfirm:()=>{ let desc=document.getElementById('editDesc').value,price=document.getElementById('editPrice').value; if(!desc||price<=0) Swal.showValidationMessage('Invalid'); return {desc,price}; }}).then(async(res)=>{ if(res.isConfirmed){ let fd=new URLSearchParams(); fd.append('id',id); fd.append('description',res.value.desc); fd.append('price',res.value.price); let data=await(await fetch('update_item.php',{method:'POST',body:fd})).json(); if(data.success){ Swal.fire('Updated!','','success'); loadItems(); loadDashboardData(); } else Swal.fire('Error','Update failed','error'); } }); }
    async function deleteItem(id){ let confirm=await Swal.fire({title:'Delete item?',text:'Irreversible',icon:'warning',showCancelButton:true}); if(confirm.isConfirmed){ let data=await apiGet(`delete_item.php?id=${id}`); if(data.success){ Swal.fire('Deleted','','success'); loadItems(); loadDashboardData(); } else Swal.fire('Error','Cannot delete','error'); } }
    async function loadAllInvoices(){ let data=await apiGet('search_invoices.php?limit=100'); displayAllInvoices(data.invoices); }
    function displayAllInvoices(invoices){ let tbody=document.getElementById('allInvoicesTableBody'); if(!invoices?.length){ tbody.innerHTML='<tr><td colspan="4" class="text-center py-8 text-slate-400">No records</td></tr>'; return; } tbody.innerHTML=invoices.map(inv=>`<tr class="invoice-row border-b border-white/5" onclick="viewInvoiceDetails('${inv.invoice_no}')"><td class="p-3 font-mono text-indigo-300">${inv.invoice_no}</td><td class="p-3 text-slate-300">${new Date(inv.created_at).toLocaleString()}</td><td class="p-3 text-right font-bold text-emerald-300">Rs ${parseFloat(inv.final_total).toFixed(2)}</td><td class="p-3 text-center"><button onclick="event.stopPropagation(); reprintInvoice('${inv.invoice_no}')" class="bg-blue-600/70 hover:bg-blue-500 px-3 py-1 rounded-lg text-xs"><i class="fas fa-print"></i> Print</button></td></tr>`).join(''); }
    async function searchInvoices(){ let s=document.getElementById('searchInvoice').value; let data=await apiGet(`search_invoices.php?search=${encodeURIComponent(s)}`); updateInvoicesTable(data.invoices); }
    async function searchInvoicesManage(){ let search=document.getElementById('searchInvoiceManage').value,start=document.getElementById('startDateInvoice').value,end=document.getElementById('endDateInvoice').value; let url=`search_invoices.php?${search?`search=${encodeURIComponent(search)}&`:''}${start&&end?`start_date=${start}&end_date=${end}`:''}`; let data=await apiGet(url); displayAllInvoices(data.invoices); }
    function resetInvoiceSearch(){ document.getElementById('searchInvoiceManage').value=''; document.getElementById('startDateInvoice').value=''; document.getElementById('endDateInvoice').value=''; loadAllInvoices(); }
    function refreshInvoices(){ document.getElementById('searchInvoice').value=''; loadDashboardData(); }
    async function viewInvoiceDetails(invNo){ let data=await apiGet(`get_invoice.php?invoice_no=${invNo}`); if(data.success){ let inv=data.invoice,items=data.items; let itemsHtml=`<div class="max-h-80 overflow-auto"><table class="w-full text-sm"><thead><tr class="border-b border-slate-600"><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody>${items.map(i=>`<tr><td>${escapeHtml(i.description)}</td><td class="text-center">${i.quantity}</td><td class="text-right">Rs ${parseFloat(i.price).toFixed(2)}</td><td class="text-right">Rs ${parseFloat(i.total).toFixed(2)}</td></tr>`).join('')}</tbody></table></div>`; Swal.fire({title:`🧾 ${inv.invoice_no}`,background:'#0f172a',color:'#e2e8f0',html:`<div class="text-left"><p><strong>Date:</strong> ${new Date(inv.created_at).toLocaleString()}</p><p>Subtotal: Rs ${parseFloat(inv.subtotal).toFixed(2)}</p><p>Discount: Rs ${parseFloat(inv.bill_discount_amount).toFixed(2)}</p><p>Tax: Rs ${parseFloat(inv.tax_amount).toFixed(2)}</p><p class="font-bold text-lg">Total: Rs ${parseFloat(inv.final_total).toFixed(2)}</p><hr class="my-2 border-slate-600">${itemsHtml}</div>`,width:'750px',confirmButtonText:'🖨️ Print Bill',showCancelButton:true}).then(res=>{if(res.isConfirmed) reprintInvoice(invNo);}); } }
    async function reprintInvoice(invNo){ let data=await apiGet(`get_invoice.php?invoice_no=${invNo}`); if(data.success){ let inv=data.invoice,items=data.items; let w=window.open('','_blank'); w.document.write(`<!DOCTYPE html><html><head><title>Invoice ${inv.invoice_no}</title><style>body{font-family:Inter;padding:30px;max-width:800px;margin:0 auto;background:#fff}.header{text-align:center}table{width:100%;border-collapse:collapse}th,td{padding:8px;border-bottom:1px solid #ddd}.totals{text-align:right}</style></head><body><div class="header"><h2>THIDASNA CRAFT</h2><p>Galle Road, Balapitiya</p><h3>Invoice: ${inv.invoice_no}</h3><p>${new Date(inv.created_at).toLocaleString()}</p></div><table><thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody>${items.map(i=>`<tr><td>${escapeHtml(i.description)}</td><td class="text-center">${i.quantity}</td><td class="text-right">Rs ${parseFloat(i.price).toFixed(2)}</td><td class="text-right">Rs ${parseFloat(i.total).toFixed(2)}</td></tr>`).join('')}</tbody></table><div class="totals"><p>Subtotal: Rs ${parseFloat(inv.subtotal).toFixed(2)}</p><p>Discount: -${parseFloat(inv.bill_discount_amount).toFixed(2)}</p><p>Total: Rs ${parseFloat(inv.final_total).toFixed(2)}</p></div><div class="footer"><p>Thank you! | Vexel IT</p></div><script>window.onload=()=>{window.print();setTimeout(()=>window.close(),800)}<\/script></body></html>`); w.document.close(); } }
    function redirectToBilling(){ window.location.href='index1.php'; }
    function escapeHtml(str){ if(!str) return ''; return str.replace(/[&<>]/g, m=>({'&':'&amp;','<':'&lt;','>':'&gt;'}[m])); }
    window.onload=()=>{ loadDashboardData(); setInterval(loadDashboardData,30000); gsap.from("#headerCard",{duration:0.8,y:-30,opacity:0}); };
</script>
</body>
</html>