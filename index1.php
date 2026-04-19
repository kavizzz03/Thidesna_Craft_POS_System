<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Thidasna Craft | Aurora Billing Suite</title>
    <!-- Fonts + Icons + Tailwind + GSAP + SweetAlert -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            background: radial-gradient(ellipse at 20% 30%, #0a0f2a, #030617);
            min-height: 100vh;
            padding: 1.5rem;
            position: relative;
        }
        
        /* floating orbs */
        .orb-1, .orb-2 {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.4;
        }
        .orb-1 { width: 60vw; height: 60vw; background: #4f46e5; top: -20%; right: -10%; animation: floatOrb 22s infinite alternate ease-in-out; }
        .orb-2 { width: 50vw; height: 50vw; background: #ec4899; bottom: -15%; left: -10%; animation: floatOrb 26s infinite alternate-reverse; }
        
        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(5%, 8%) scale(1.3); }
        }
        
        .neo-glass {
            background: rgba(15, 25, 45, 0.75);
            backdrop-filter: blur(16px);
            border-radius: 2rem;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 25px 40px -12px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.03);
            transition: all 0.3s ease;
        }
        
        .btn-gradient {
            background: linear-gradient(95deg, #4f46e5, #7c3aed);
            transition: all 0.2s;
        }
        .btn-gradient:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px -5px #4f46e5;
        }
        
        .btn-warning {
            background: linear-gradient(95deg, #f59e0b, #d97706);
        }
        .btn-danger {
            background: linear-gradient(95deg, #ef4444, #dc2626);
        }
        .btn-success {
            background: linear-gradient(95deg, #10b981, #059669);
        }
        .btn-info {
            background: linear-gradient(95deg, #3b82f6, #2563eb);
        }
        
        .autocomplete-items {
            position: absolute;
            border: 1px solid #334155;
            border-top: none;
            z-index: 99;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 300px;
            overflow-y: auto;
            background: #1e293b;
            border-radius: 0 0 1rem 1rem;
            backdrop-filter: blur(12px);
        }
        .autocomplete-items div {
            padding: 12px;
            cursor: pointer;
            border-bottom: 1px solid #334155;
            color: #e2e8f0;
            transition: all 0.2s;
        }
        .autocomplete-items div:hover {
            background: #4f46e5;
        }
        
        .invoice-card, .hold-card {
            transition: all 0.2s;
            cursor: pointer;
            background: #0f172a;
            border: 1px solid #334155;
        }
        .invoice-card:hover, .hold-card:hover {
            transform: translateY(-3px);
            border-color: #6366f1;
            background: #1e293b;
        }
        .invoice-card.selected {
            border: 2px solid #6366f1;
            background: linear-gradient(135deg, #1e293b, #2d3a5e);
            box-shadow: 0 0 15px rgba(99,102,241,0.3);
        }
        
        .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: #1e293b; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 10px; }
        
        input, select {
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
        
        .footer-aura {
            background: rgba(2,6,23,0.6);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        
        @media print {
            body * { visibility: hidden; }
            #printableBill, #printableBill * { visibility: visible; }
            #printableBill {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                margin: 0;
                padding: 20px;
                background: white;
                z-index: 9999;
            }
            .no-print { display: none !important; }
        }
        
        .table-header {
            background: rgba(30,41,59,0.8);
            backdrop-filter: blur(8px);
        }
        
        .badge-glow {
            box-shadow: 0 0 8px rgba(99,102,241,0.5);
        }
    </style>
</head>
<body class="relative z-10">

<div class="max-w-7xl mx-auto">
    <!-- Header - Modern Glass -->
    <div class="neo-glass p-6 mb-6 transition-all duration-500">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-crown text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight"><span class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 bg-clip-text text-transparent">THIDASNA CRAFT</span></h1>
                    <p class="text-slate-300 text-sm">✦ Premium Billing Terminal ✦</p>
                </div>
            </div>
            <div class="bg-slate-800/50 px-5 py-2 rounded-2xl backdrop-blur-sm text-right">
                <p class="text-slate-200 text-sm"><i class="far fa-calendar-alt text-indigo-300 mr-2"></i><span id="currentDate"></span></p>
                <p class="text-slate-300 text-xs mt-1"><i class="far fa-clock text-indigo-300 mr-2"></i><span id="currentTime"></span></p>
            </div>
        </div>
    </div>
    
    <!-- Main Billing Card -->
    <div class="neo-glass overflow-hidden mb-8">
        <!-- Search & Controls -->
        <div class="p-6 border-b border-white/10">
            <div class="flex flex-wrap gap-3 mb-5">
                <div class="flex-1 search-container relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="searchInput" placeholder="Search by item code or description..." 
                           class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-800/50 border-slate-700 text-white"
                           autocomplete="off">
                    <div id="autocompleteList" class="autocomplete-items hidden"></div>
                </div>
                <button onclick="addSelectedItem()" class="btn-gradient px-6 rounded-xl text-white font-semibold">
                    <i class="fas fa-plus-circle mr-2"></i> Add
                </button>
            </div>
            <div class="flex flex-wrap gap-3">
                <button onclick="holdBill()" class="btn-warning text-white px-5 py-2 rounded-xl font-medium shadow-lg"><i class="fas fa-pause-circle mr-2"></i> Hold Bill</button>
                <button onclick="showHoldBills()" class="bg-purple-600 hover:bg-purple-500 text-white px-5 py-2 rounded-xl font-medium"><i class="fas fa-history mr-2"></i> View Hold Bills</button>
                <button onclick="clearBill()" class="btn-danger text-white px-5 py-2 rounded-xl font-medium"><i class="fas fa-trash-alt mr-2"></i> Clear</button>
                <button onclick="printCurrentBill()" class="btn-success text-white px-5 py-2 rounded-xl font-medium"><i class="fas fa-print mr-2"></i> Print & Save</button>
                <button onclick="showReprintModal()" class="btn-info text-white px-5 py-2 rounded-xl font-medium"><i class="fas fa-print mr-2"></i> Reprint Bill</button>
            </div>
        </div>
        
        <!-- Bill Items Table -->
        <div class="overflow-x-auto p-5 custom-scroll">
            <table class="w-full">
                <thead class="table-header sticky top-0">
                    <tr class="text-slate-300 text-sm">
                        <th class="p-3 text-left">Item Code</th>
                        <th class="p-3 text-left">Description</th>
                        <th class="p-3 text-center">Qty</th>
                        <th class="p-3 text-right">Price (Rs)</th>
                        <th class="p-3 text-center">Disc%</th>
                        <th class="p-3 text-right">Total (Rs)</th>
                        <th class="p-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="billItemsBody">
                    <tr><td colspan="7" class="text-center py-12 text-slate-400"><i class="fas fa-cart-plus text-4xl mb-3 opacity-50"></i><br>No items added. Search & add products.</td></tr>
                </tbody>
            </table>
        </div>
        
        <!-- Totals Section -->
        <div class="p-6 bg-slate-900/40 border-t border-white/10">
            <div class="flex flex-wrap justify-between gap-6">
                <div class="space-y-3">
                    <div class="flex items-center gap-3"><label class="w-32 font-semibold text-slate-200">Bill Discount %</label><input type="number" id="billDiscount" value="0" step="0.5" class="w-28 rounded-xl" oninput="calculateTotals()"></div>
                    <div class="flex items-center gap-3"><label class="w-32 font-semibold text-slate-200">Tax %</label><input type="number" id="taxPercent" value="0" step="0.5" class="w-28 rounded-xl" oninput="calculateTotals()"></div>
                </div>
                <div class="text-right space-y-2 text-slate-200">
                    <p>Subtotal: Rs <span id="subtotal" class="font-mono">0.00</span></p>
                    <p class="text-amber-400">Discount: -Rs <span id="discountAmount" class="font-mono">0.00</span></p>
                    <p class="text-emerald-400">Tax: +Rs <span id="taxAmount" class="font-mono">0.00</span></p>
                    <p class="text-3xl font-bold text-transparent bg-gradient-to-r from-indigo-300 to-purple-300 bg-clip-text">Total: Rs <span id="finalTotal">0.00</span></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer-aura rounded-2xl p-6 text-center text-slate-300 no-print">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div><p class="text-sm">© 2026-2027 Thidasna Craft — All Rights Reserved</p><p class="text-xs opacity-70">Galle Road, Balapitiya, Sri Lanka</p></div>
            <div><i class="fas fa-gem text-indigo-400 text-xl"></i><p class="text-xs font-mono mt-1">Vexel IT by Kavizz | 📞 +94 74 089 0730</p></div>
            <div><i class="fas fa-certificate text-indigo-400"></i><p class="text-xs">Version 4.0 · Aurora Suite</p></div>
        </div>
    </div>
</div>

<!-- Printable Bill Template (hidden) -->
<div id="printableBill" style="display: none; background: white; padding: 30px; max-width: 800px; margin: 0 auto;">
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="font-size: 24px; font-weight: bold;">THIDASNA CRAFT</h2>
        <p>Galle Road, Balapitiya, Sri Lanka</p>
        <p>Tel: 0741977324 | Email: info.thidasnacraft@gmail.com</p>
        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
        <p><strong>Invoice No: <span id="printInvoiceNo"></span></strong></p>
        <p>Date: <span id="printDate"></span> | Time: <span id="printTime"></span></p>
        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
    </div>
    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <thead><tr style="background: #f3f4f6;"><th style="padding: 10px; text-align: left;">Item</th><th style="padding: 10px; text-align: center;">Qty</th><th style="padding: 10px; text-align: right;">Price (Rs)</th><th style="padding: 10px; text-align: right;">Total (Rs)</th></tr></thead>
        <tbody id="printItemsBody"></tbody>
    </table>
    <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
    <div style="text-align: right; margin: 20px 0;">
        <p>Subtotal: Rs <span id="printSubtotal"></span></p>
        <p>Bill Discount (<span id="printDiscPercent"></span>%): -Rs <span id="printDiscAmount"></span></p>
        <p>Tax (<span id="printTaxPercent"></span>%): +Rs <span id="printTaxAmount"></span></p>
        <h3 style="font-size: 20px;"><strong>GRAND TOTAL: Rs <span id="printFinalTotal"></span></strong></h3>
    </div>
    <div style="border-top: 1px dashed #000; margin: 20px 0;"></div>
    <div style="display: flex; justify-content: space-between; margin-top: 40px;">
        <div style="text-align: center;"><hr style="width: 150px;"><p>Customer Signature</p></div>
        <div style="text-align: center;"><hr style="width: 150px;"><p>Cashier Signature</p></div>
    </div>
    <div style="text-align: center; margin-top: 30px; font-size: 12px;">
        <p>Thank you for shopping with us!</p>
        <p>Developed by Vexel IT by Kavizz | 📞 94740890730</p>
    </div>
</div>

<!-- Hold Bills Modal -->
<div id="holdBillsModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 no-print">
    <div class="bg-slate-800 rounded-2xl p-6 w-full max-w-4xl max-h-[80vh] overflow-y-auto border border-white/10">
        <div class="flex justify-between items-center mb-5"><h3 class="text-2xl font-bold text-white"><i class="fas fa-pause-circle text-amber-400 mr-2"></i>Held Bills</h3><button onclick="closeHoldBillsModal()" class="text-slate-400 hover:text-white"><i class="fas fa-times text-2xl"></i></button></div>
        <div id="holdBillsList" class="space-y-3"></div>
        <div class="mt-6 flex justify-end"><button onclick="closeHoldBillsModal()" class="bg-slate-600 px-6 py-2 rounded-xl text-white">Close</button></div>
    </div>
</div>

<!-- Reprint Modal -->
<div id="reprintModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 no-print">
    <div class="bg-slate-800 rounded-2xl p-6 w-full max-w-4xl max-h-[85vh] overflow-y-auto border border-white/10">
        <div class="flex justify-between items-center mb-5"><h3 class="text-2xl font-bold text-white"><i class="fas fa-print text-blue-400 mr-2"></i>Reprint Invoice</h3><button onclick="closeReprintModal()" class="text-slate-400 hover:text-white"><i class="fas fa-times text-2xl"></i></button></div>
        
        <div class="bg-slate-700/50 rounded-xl p-5 mb-5">
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div><label class="text-slate-300 text-sm block mb-1">🔍 Invoice Number</label><input type="text" id="searchInvoice" placeholder="INV-xxx" class="w-full" onkeyup="dynamicSearch()"></div>
                <div><label class="text-slate-300 text-sm block mb-1">📅 Quick Range</label><select id="quickSearch" onchange="quickDateSearch()" class="w-full"><option value="">Select</option><option value="today">Today</option><option value="yesterday">Yesterday</option><option value="last7days">Last 7 Days</option><option value="last30days">Last 30 Days</option><option value="thismonth">This Month</option></select></div>
            </div>
            <div class="border-t border-white/10 pt-4"><p class="text-sm text-slate-300 mb-2">📅 Custom Date Range</p><div class="grid md:grid-cols-3 gap-3"><div><input type="date" id="startDate" class="w-full"></div><div><input type="date" id="endDate" class="w-full"></div><div><button onclick="searchByDateRange()" class="btn-gradient w-full py-2 rounded-xl text-white"><i class="fas fa-search"></i> Search</button></div></div></div>
            <div class="flex gap-2 mt-4"><button onclick="loadAllInvoices()" class="bg-green-600 hover:bg-green-500 flex-1 py-2 rounded-xl text-white"><i class="fas fa-list"></i> All Invoices</button><button onclick="resetReprintSearch()" class="bg-slate-600 hover:bg-slate-500 flex-1 py-2 rounded-xl text-white"><i class="fas fa-sync-alt"></i> Reset</button></div>
        </div>
        
        <div class="flex justify-between items-center mb-3"><h4 class="font-semibold text-white"><i class="fas fa-file-invoice"></i> Invoice List</h4><span id="resultCount" class="text-sm text-slate-400"></span></div>
        <div id="invoiceList" class="max-h-80 overflow-y-auto space-y-2 custom-scroll"></div>
        <div id="selectedInvoicePreview" class="mt-4 hidden bg-indigo-900/50 border border-indigo-500 rounded-xl p-3"><div class="flex justify-between items-center"><div><i class="fas fa-check-circle text-indigo-400"></i><span class="text-white ml-2">Selected: <span id="selectedInvoiceNo" class="font-bold"></span></span></div><button onclick="printSelectedInvoice()" class="btn-success px-5 py-2 rounded-xl text-white"><i class="fas fa-print"></i> Print Now</button></div></div>
        <div class="mt-5 flex justify-end"><button onclick="closeReprintModal()" class="bg-slate-600 px-6 py-2 rounded-xl text-white">Close</button></div>
    </div>
</div>

<script>
    let billItems = [], nextId = 1, selectedItemData = null, selectedReprintInvoice = null;
    
    // Date/Time
    function updateDateTime(){ const n=new Date(); document.getElementById('currentDate').innerText=n.toLocaleDateString('en-LK'); document.getElementById('currentTime').innerText=n.toLocaleTimeString('en-LK'); }
    updateDateTime(); setInterval(updateDateTime,1000);
    
    window.onload = () => { loadLastHoldBill(); gsap.from(".neo-glass",{duration:0.6,y:30,opacity:0,stagger:0.1}); };
    
    // Search autocomplete
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function(){ clearTimeout(searchTimeout); let term=this.value.trim(); if(term.length>=2) searchTimeout=setTimeout(()=>searchItems(term),300); else hideAutocomplete(); });
    document.getElementById('searchInput').addEventListener('keypress', function(e){ if(e.key==='Enter' && selectedItemData){ selectItem(selectedItemData); } else if(e.key==='Enter') addItem(); });
    
    async function searchItems(term){ try{ let res=await fetch(`search_items.php?search=${encodeURIComponent(term)}`); let items=await res.json(); if(items?.length) showAutocomplete(items); else hideAutocomplete(); }catch(e){ hideAutocomplete(); } }
    function showAutocomplete(items){ let div=document.getElementById('autocompleteList'); div.innerHTML=''; div.classList.remove('hidden'); items.forEach(item=>{ let opt=document.createElement('div'); opt.innerHTML=`<div class="flex justify-between"><div><span class="font-semibold text-indigo-300">${escapeHtml(item.item_code)}</span> - ${escapeHtml(item.description)}</div><div class="text-emerald-300">Rs ${parseFloat(item.price).toFixed(2)}</div></div>`; opt.onclick=()=>selectItem(item); div.appendChild(opt); }); }
    function hideAutocomplete(){ let div=document.getElementById('autocompleteList'); div.classList.add('hidden'); div.innerHTML=''; }
    
    function selectItem(item){ selectedItemData=item; document.getElementById('searchInput').value=`${item.item_code} - ${item.description}`; hideAutocomplete(); Swal.fire({title:'Add Item',background:'#1e293b',color:'#fff',html:`<div class="text-left"><p><strong>${item.description}</strong> | ${item.item_code}</p><p>Price: Rs ${item.price.toFixed(2)}</p><hr class="my-2 border-slate-600"><label>Quantity:</label><input id="qtyInput" class="swal2-input" value="1" type="number" step="0.5"><label>Discount %:</label><input id="discInput" class="swal2-input" value="0" type="number" step="1"></div>`,showCancelButton:true,confirmButtonText:'Add to Bill',preConfirm:()=>{ let qty=parseFloat(document.getElementById('qtyInput').value),disc=parseFloat(document.getElementById('discInput').value); if(isNaN(qty)||qty<=0) Swal.showValidationMessage('Invalid qty'); return {qty,disc}; }}).then(res=>{ if(res.isConfirmed){ addItemToBill(item,res.value.qty,res.value.disc); document.getElementById('searchInput').value=''; selectedItemData=null; } }); }
    function addSelectedItem(){ if(selectedItemData) selectItem(selectedItemData); else addItem(); }
    async function addItem(){ let search=document.getElementById('searchInput').value.trim(); if(!search){ Swal.fire('Warning','Enter item code','warning'); return; } try{ let res=await fetch(`get_item.php?search=${encodeURIComponent(search)}`); let item=await res.json(); if(item?.item_code){ let qtyRes=await Swal.fire({title:'Quantity',input:'number',inputValue:1,background:'#1e293b',color:'#fff'}); let discRes=await Swal.fire({title:'Discount %',input:'number',inputValue:0,background:'#1e293b',color:'#fff'}); addItemToBill(item,parseFloat(qtyRes.value||1),parseFloat(discRes.value||0)); document.getElementById('searchInput').value=''; selectedItemData=null; } else{ let create=await Swal.fire({title:'Not Found','text':'Create new?',showCancelButton:true}); if(create.isConfirmed){ let priceRes=await Swal.fire({title:'Price',input:'number'}); await fetch('add_item.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`description=${encodeURIComponent(search)}&price=${priceRes.value}`}); addItem(); } } }catch(e){ Swal.fire('Error','Cannot fetch','error'); } }
    function addItemToBill(item,qty,disc){ billItems.push({id:nextId++,item_code:item.item_code,description:item.description,qty:qty,price:parseFloat(item.price),discount:disc}); renderBill(); calculateTotals(); }
    function renderBill(){ let tbody=document.getElementById('billItemsBody'); if(!billItems.length){ tbody.innerHTML='<tr><td colspan="7" class="text-center py-12 text-slate-400"><i class="fas fa-cart-plus text-4xl mb-3 opacity-50"></i><br>No items added. Search & add products.</td></tr>'; return; } tbody.innerHTML=billItems.map(item=>{ let total=(item.qty*item.price)*(1-item.discount/100); return `<tr class="border-b border-white/5"><td class="p-3 font-mono text-indigo-300">${escapeHtml(item.item_code)}</td><td class="p-3 text-slate-200">${escapeHtml(item.description)}</td><td class="p-3 text-center"><button onclick="updateQty(${item.id},-1)" class="bg-slate-700 px-2 py-1 rounded-lg">-</button><span class="mx-3 font-bold">${item.qty}</span><button onclick="updateQty(${item.id},1)" class="bg-slate-700 px-2 py-1 rounded-lg">+</button></td><td class="p-3 text-right font-mono">${item.price.toFixed(2)}</td><td class="p-3 text-center"><input type="number" value="${item.discount}" onchange="updateDisc(${item.id},this.value)" class="w-20 rounded-lg text-center" step="1" min="0" max="100"></td><td class="p-3 text-right font-bold text-emerald-300">${total.toFixed(2)}</td><td class="p-3 text-center"><button onclick="removeItem(${item.id})" class="text-red-400 hover:text-red-300"><i class="fas fa-trash-alt"></i></button></td></tr>`; }).join(''); }
    function updateQty(id,delta){ let item=billItems.find(i=>i.id===id); if(item){ item.qty=Math.max(0.5,item.qty+delta); renderBill(); calculateTotals(); } }
    function updateDisc(id,val){ let item=billItems.find(i=>i.id===id); if(item){ item.discount=parseFloat(val)||0; renderBill(); calculateTotals(); } }
    function removeItem(id){ billItems=billItems.filter(i=>i.id!==id); renderBill(); calculateTotals(); }
    function calculateTotals(){ let subtotal=billItems.reduce((sum,i)=>sum+(i.qty*i.price)*(1-i.discount/100),0); let discPercent=parseFloat(document.getElementById('billDiscount').value)||0; let taxPercent=parseFloat(document.getElementById('taxPercent').value)||0; let discAmt=subtotal*discPercent/100; let afterDisc=subtotal-discAmt; let taxAmt=afterDisc*taxPercent/100; let final=afterDisc+taxAmt; document.getElementById('subtotal').innerText=subtotal.toFixed(2); document.getElementById('discountAmount').innerText=discAmt.toFixed(2); document.getElementById('taxAmount').innerText=taxAmt.toFixed(2); document.getElementById('finalTotal').innerText=final.toFixed(2); }
    function clearBill(){ Swal.fire({title:'Clear Bill?',text:'All items will be removed',icon:'warning',background:'#1e293b',color:'#fff',showCancelButton:true}).then(res=>{ if(res.isConfirmed){ billItems=[]; document.getElementById('billDiscount').value=0; document.getElementById('taxPercent').value=0; renderBill(); calculateTotals(); localStorage.removeItem('currentHoldBill'); Swal.fire('Cleared!','','success'); } }); }
    function holdBill(){ if(!billItems.length){ Swal.fire('Warning','No items','warning'); return; } let holdData={id:Date.now(),timestamp:new Date().toLocaleString(),items:billItems,discount:document.getElementById('billDiscount').value,tax:document.getElementById('taxPercent').value,total:document.getElementById('finalTotal').innerText}; let held=JSON.parse(localStorage.getItem('heldBills')||'[]'); held.push(holdData); localStorage.setItem('heldBills',JSON.stringify(held)); localStorage.setItem('currentHoldBill',JSON.stringify({items:billItems,discount:document.getElementById('billDiscount').value,tax:document.getElementById('taxPercent').value})); Swal.fire('Held!','Bill saved','success'); }
    function showHoldBills(){ let held=JSON.parse(localStorage.getItem('heldBills')||'[]'); let listDiv=document.getElementById('holdBillsList'); if(!held.length) listDiv.innerHTML='<div class="text-center py-8 text-slate-400"><i class="fas fa-inbox text-5xl"></i><p>No held bills</p></div>'; else listDiv.innerHTML=held.reverse().map(b=>`<div class="hold-card rounded-xl p-4 bg-slate-700/50"><div class="flex justify-between"><div><p class="font-bold text-white">Bill #${b.id}</p><p class="text-sm text-slate-300">${b.timestamp}</p><p class="text-sm">Items: ${b.items.length} | Total: Rs ${b.total}</p></div><div class="flex gap-2"><button onclick="loadHoldBill(${b.id})" class="bg-green-600 px-3 py-1 rounded-lg text-white"><i class="fas fa-download"></i> Load</button><button onclick="deleteHoldBill(${b.id})" class="bg-red-600 px-3 py-1 rounded-lg text-white"><i class="fas fa-trash"></i> Del</button></div></div></div>`).join(''); document.getElementById('holdBillsModal').classList.remove('hidden'); document.getElementById('holdBillsModal').classList.add('flex'); }
    function loadHoldBill(id){ let held=JSON.parse(localStorage.getItem('heldBills')||'[]'); let bill=held.find(b=>b.id===id); if(bill){ billItems=bill.items; document.getElementById('billDiscount').value=bill.discount; document.getElementById('taxPercent').value=bill.tax; renderBill(); calculateTotals(); localStorage.setItem('currentHoldBill',JSON.stringify({items:bill.items,discount:bill.discount,tax:bill.tax})); Swal.fire('Loaded','Bill restored','success'); closeHoldBillsModal(); } }
    function deleteHoldBill(id){ Swal.fire({title:'Delete?',text:'Permanent',icon:'warning',showCancelButton:true}).then(res=>{ if(res.isConfirmed){ let held=JSON.parse(localStorage.getItem('heldBills')||'[]'); held=held.filter(b=>b.id!==id); localStorage.setItem('heldBills',JSON.stringify(held)); showHoldBills(); Swal.fire('Deleted','','success'); } }); }
    function closeHoldBillsModal(){ let m=document.getElementById('holdBillsModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
    
    async function printCurrentBill(){ if(!billItems.length){ Swal.fire('Error','No items','error'); return; } let data={items:billItems,bill_discount:parseFloat(document.getElementById('billDiscount').value),tax:parseFloat(document.getElementById('taxPercent').value)}; try{ let res=await fetch('save_invoice.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(data)}); let result=await res.json(); if(result.success){ await printBill(result.invoice); localStorage.removeItem('currentHoldBill'); Swal.fire('Success',`Invoice ${result.invoice} saved`,'success').then(()=>{ billItems=[]; renderBill(); calculateTotals(); }); } else Swal.fire('Error','Save failed','error'); }catch(e){ Swal.fire('Error','Server error','error'); } }
    async function printBill(invoiceNo){ Swal.fire({title:'Loading...',didOpen:()=>{Swal.showLoading();},allowOutsideClick:false}); let res=await fetch(`get_invoice.php?invoice_no=${invoiceNo}`); let data=await res.json(); Swal.close(); if(data.success){ let inv=data.invoice,items=data.items; document.getElementById('printInvoiceNo').innerText=inv.invoice_no; let d=new Date(inv.created_at); document.getElementById('printDate').innerText=d.toLocaleDateString(); document.getElementById('printTime').innerText=d.toLocaleTimeString(); document.getElementById('printItemsBody').innerHTML=items.map(it=>`<tr><td style="padding:8px;">${escapeHtml(it.description)}</td><td style="text-align:center;">${it.quantity}</td><td style="text-align:right;">${parseFloat(it.price).toFixed(2)}</td><td style="text-align:right;">${parseFloat(it.total).toFixed(2)}</td></tr>`).join(''); document.getElementById('printSubtotal').innerText=parseFloat(inv.subtotal).toFixed(2); document.getElementById('printDiscPercent').innerText=inv.bill_discount_percent; document.getElementById('printDiscAmount').innerText=parseFloat(inv.bill_discount_amount).toFixed(2); document.getElementById('printTaxPercent').innerText=inv.tax_percent; document.getElementById('printTaxAmount').innerText=parseFloat(inv.tax_amount).toFixed(2); document.getElementById('printFinalTotal').innerText=parseFloat(inv.final_total).toFixed(2); let printDiv=document.getElementById('printableBill'); printDiv.style.display='block'; setTimeout(()=>{ window.print(); setTimeout(()=>{ printDiv.style.display='none'; Swal.fire('Printed!','','success'); },500); },100); } else Swal.fire('Error','Fetch failed','error'); }
    async function reprintInvoice(invoiceNo){ closeReprintModal(); await printBill(invoiceNo); }
    
    // Reprint modal functions
    function showReprintModal(){ document.getElementById('reprintModal').classList.remove('hidden'); document.getElementById('reprintModal').classList.add('flex'); selectedReprintInvoice=null; document.getElementById('selectedInvoicePreview').classList.add('hidden'); loadAllInvoices(); }
    function closeReprintModal(){ let m=document.getElementById('reprintModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
    async function loadAllInvoices(){ let invoiceList=document.getElementById('invoiceList'); invoiceList.innerHTML='<div class="text-center py-8"><div class="loading-spinner"></div><p class="text-slate-400">Loading...</p></div>'; try{ let res=await fetch('search_invoices.php?limit=100'); let data=await res.json(); currentInvoices=data.invoices||[]; displayInvoiceResults(data); }catch(e){ invoiceList.innerHTML='<div class="text-center py-8 text-red-400">Error</div>'; } }
    function displayInvoiceResults(data){ let listDiv=document.getElementById('invoiceList'); let countSpan=document.getElementById('resultCount'); if(data.success && data.invoices?.length){ countSpan.innerText=`${data.invoices.length} invoice(s)`; listDiv.innerHTML=`<div class="space-y-2">${data.invoices.map(inv=>`<div class="invoice-card rounded-xl p-4 ${selectedReprintInvoice===inv.invoice_no?'selected':''}" onclick="selectInvoiceForReprint('${inv.invoice_no}')"><div class="flex justify-between items-center"><div><p class="font-bold text-indigo-300">${inv.invoice_no}</p><p class="text-xs text-slate-400">${new Date(inv.created_at).toLocaleString()}</p><p class="text-sm">Amount: Rs ${parseFloat(inv.final_total).toFixed(2)}</p></div><button onclick="event.stopPropagation(); reprintInvoice('${inv.invoice_no}')" class="btn-success px-4 py-1 rounded-lg text-sm"><i class="fas fa-print"></i> Reprint</button></div></div>`).join('')}</div>`; } else { countSpan.innerText='0 invoices'; listDiv.innerHTML='<div class="text-center py-8 text-slate-400"><i class="fas fa-search"></i><p>No invoices found</p></div>'; } }
    function selectInvoiceForReprint(invoiceNo){ selectedReprintInvoice=invoiceNo; document.getElementById('selectedInvoiceNo').innerText=invoiceNo; document.getElementById('selectedInvoicePreview').classList.remove('hidden'); document.querySelectorAll('.invoice-card').forEach(c=>c.classList.remove('selected')); event.currentTarget.classList.add('selected'); }
    function printSelectedInvoice(){ if(selectedReprintInvoice) reprintInvoice(selectedReprintInvoice); else Swal.fire('Warning','Select an invoice','warning'); }
    let dynamicSearchTimeout; function dynamicSearch(){ clearTimeout(dynamicSearchTimeout); dynamicSearchTimeout=setTimeout(()=>{ let s=document.getElementById('searchInvoice').value.trim(); if(s.length>=2) searchInvoicesByNumber(); else if(!s.length) loadAllInvoices(); },500); }
    async function searchInvoicesByNumber(){ let s=document.getElementById('searchInvoice').value; try{ let res=await fetch(`search_invoices.php?search=${encodeURIComponent(s)}`); let data=await res.json(); displayInvoiceResults(data); }catch(e){} }
    function quickDateSearch(){ let opt=document.getElementById('quickSearch').value; let today=new Date(); let start,end; if(opt==='today'){ start=end=today; } else if(opt==='yesterday'){ start=new Date(today); start.setDate(today.getDate()-1); end=start; } else if(opt==='last7days'){ start=new Date(today); start.setDate(today.getDate()-7); end=today; } else if(opt==='last30days'){ start=new Date(today); start.setDate(today.getDate()-30); end=today; } else if(opt==='thismonth'){ start=new Date(today.getFullYear(),today.getMonth(),1); end=today; } else return; document.getElementById('startDate').value=start.toISOString().split('T')[0]; document.getElementById('endDate').value=end.toISOString().split('T')[0]; searchByDateRange(); }
    async function searchByDateRange(){ let start=document.getElementById('startDate').value,end=document.getElementById('endDate').value; if(!start||!end){ Swal.fire('Warning','Select dates','warning'); return; } let res=await fetch(`search_invoices_by_date.php?start_date=${start}&end_date=${end}`); let data=await res.json(); displayInvoiceResults(data); }
    function resetReprintSearch(){ document.getElementById('searchInvoice').value=''; document.getElementById('startDate').value=''; document.getElementById('endDate').value=''; document.getElementById('quickSearch').value=''; selectedReprintInvoice=null; document.getElementById('selectedInvoicePreview').classList.add('hidden'); loadAllInvoices(); }
    function loadLastHoldBill(){ let saved=localStorage.getItem('currentHoldBill'); if(saved){ Swal.fire({title:'Hold Bill Found',text:'Load previous bill?',icon:'question',showCancelButton:true}).then(res=>{ if(res.isConfirmed){ let data=JSON.parse(saved); billItems=data.items; document.getElementById('billDiscount').value=data.discount; document.getElementById('taxPercent').value=data.tax; renderBill(); calculateTotals(); Swal.fire('Loaded!','','success'); } else localStorage.removeItem('currentHoldBill'); }); } }
    function escapeHtml(str){ if(!str) return ''; return str.replace(/[&<>]/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;'}[m])); }
    document.addEventListener('click',function(e){ if(!document.querySelector('.search-container')?.contains(e.target)) hideAutocomplete(); });
</script>
</body>
</html>