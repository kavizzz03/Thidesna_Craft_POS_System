<?php include "db.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thidasna Craft - Billing System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        @media print {
            body * {
                visibility: hidden;
            }
            #printableBill, #printableBill * {
                visibility: visible;
            }
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
            .no-print {
                display: none !important;
            }
        }
        
        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 300px;
            overflow-y: auto;
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.2s;
        }
        
        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }
        
        .search-container {
            position: relative;
        }
        
        .footer {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
            border-radius: 10px;
        }
        
        .hold-bill-card, .invoice-card {
            transition: all 0.3s ease;
        }
        
        .hold-bill-card:hover, .invoice-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0,0,0,.3);
            border-radius: 50%;
            border-top-color: #6366f1;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .search-active {
            background-color: #e0e7ff;
            border-color: #6366f1;
        }
        
        .invoice-card {
            cursor: pointer;
        }
        
        .invoice-card.selected {
            background-color: #e0e7ff;
            border: 2px solid #6366f1;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }
    </style>
</head>
<body class="bg-gray-100 p-4 md:p-6">

<div class="max-w-6xl mx-auto">
    <!-- Main Billing Interface -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6 no-print">
        <div class="bg-gradient-to-r from-indigo-700 to-purple-800 px-6 py-4 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">✨ THIDASNA CRAFT</h1>
                    <p class="text-sm opacity-90">Premium Billing System</p>
                </div>
                <div class="text-right">
                    <p class="text-sm">Date: <span id="currentDate"></span></p>
                    <p class="text-sm">Time: <span id="currentTime"></span></p>
                </div>
            </div>
        </div>
        
        <!-- Search & Controls -->
        <div class="p-5 border-b">
            <div class="flex gap-3 mb-4">
                <div class="flex-1 search-container relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Search by item code or description..." 
                           class="w-full pl-10 pr-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-400"
                           autocomplete="off">
                    <div id="autocompleteList" class="autocomplete-items hidden"></div>
                </div>
                <button onclick="addSelectedItem()" class="bg-indigo-600 text-white px-6 rounded-xl hover:bg-indigo-700">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
            <div class="flex gap-3 flex-wrap">
                <button onclick="holdBill()" class="bg-yellow-500 text-white px-5 py-2 rounded-lg hover:bg-yellow-600">
                    <i class="fas fa-pause-circle"></i> Hold Bill
                </button>
                <button onclick="showHoldBills()" class="bg-purple-600 text-white px-5 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-history"></i> View Hold Bills
                </button>
                <button onclick="clearBill()" class="bg-red-500 text-white px-5 py-2 rounded-lg hover:bg-red-600">
                    <i class="fas fa-trash-alt"></i> Clear
                </button>
                <button onclick="printCurrentBill()" class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-print"></i> Print & Save
                </button>
                <button onclick="showReprintModal()" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-print"></i> Reprint Bill
                </button>
            </div>
        </div>
        
        <!-- Bill Items Table -->
        <div class="overflow-x-auto p-5">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
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
                    <tr><td colspan="7" class="text-center py-8 text-gray-400">No items added. Search and add products.右侧</tr>
                </tbody>
            </table>
        </div>
        
        <!-- Totals Section -->
        <div class="p-5 bg-gray-50 border-t">
            <div class="flex justify-between gap-6 flex-wrap">
                <div class="flex-1 space-y-3">
                    <div class="flex items-center gap-3">
                        <label class="w-32 font-semibold">Bill Discount %</label>
                        <input type="number" id="billDiscount" value="0" step="0.5" class="border rounded-lg px-3 py-2 w-28" oninput="calculateTotals()">
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="w-32 font-semibold">Tax %</label>
                        <input type="number" id="taxPercent" value="0" step="0.5" class="border rounded-lg px-3 py-2 w-28" oninput="calculateTotals()">
                    </div>
                </div>
                <div class="flex-1 text-right space-y-2">
                    <p>Subtotal: Rs <span id="subtotal">0.00</span></p>
                    <p class="text-red-600">Discount: -Rs <span id="discountAmount">0.00</span></p>
                    <p class="text-green-600">Tax: +Rs <span id="taxAmount">0.00</span></p>
                    <p class="text-2xl font-bold text-indigo-700">Total: Rs <span id="finalTotal">0.00</span></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer no-print">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="text-left">
                <p class="text-sm opacity-90">© 2026-2027 Thidasna Craft - All Rights Reserved</p>
                <p class="text-xs opacity-75 mt-1">Galle Road, Balapitiya, Sri Lanka</p>
            </div>
            <div class="text-center">
                <i class="fas fa-code"></i>
                <p class="text-sm font-semibold">Developed by Vexel IT by Kavizz</p>
                <p class="text-xs opacity-75">📞 94740890730</p>
            </div>
            <div class="text-right">
                <i class="fas fa-certificate"></i>
                <p class="text-xs opacity-75">Version 2.0 | Premium Support</p>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Printable Bill Template -->
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
        <thead>
            <tr style="background: #f3f4f6; border-bottom: 2px solid #000;">
                <th style="padding: 10px; text-align: left;">Item</th>
                <th style="padding: 10px; text-align: center;">Qty</th>
                <th style="padding: 10px; text-align: right;">Price (Rs)</th>
                <th style="padding: 10px; text-align: right;">Total (Rs)</th>
            </tr>
        </thead>
        <tbody id="printItemsBody"></tbody>
    </table>
    
    <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
    
    <div style="text-align: right; margin: 20px 0;">
        <p>Subtotal: Rs <span id="printSubtotal"></span></p>
        <p>Bill Discount (<span id="printDiscPercent"></span>%): -Rs <span id="printDiscAmount"></span></p>
        <p>Tax (<span id="printTaxPercent"></span>%): +Rs <span id="printTaxAmount"></span></p>
        <h3 style="font-size: 20px; margin-top: 10px;"><strong>GRAND TOTAL: Rs <span id="printFinalTotal"></span></strong></h3>
    </div>
    
    <div style="border-top: 1px dashed #000; margin: 20px 0;"></div>
    
    <div style="display: flex; justify-content: space-between; margin-top: 40px;">
        <div style="text-align: center;">
            <hr style="width: 150px; margin-bottom: 5px;">
            <p>Customer Signature</p>
        </div>
        <div style="text-align: center;">
            <hr style="width: 150px; margin-bottom: 5px;">
            <p>Cashier Signature</p>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 30px; font-size: 12px;">
        <p>Thank you for shopping with us!</p>
        <p style="font-size: 10px; margin-top: 5px;">Developed by Vexel IT by Kavizz | 📞 94740890730</p>
        <p style="font-size: 10px; margin-top: 5px;">This is a computer generated invoice - Valid without signature</p>
    </div>
</div>

<!-- Hold Bills Modal -->
<div id="holdBillsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 no-print">
    <div class="bg-white rounded-xl p-6 w-full max-w-4xl max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-pause-circle text-yellow-500"></i> Held Bills
            </h3>
            <button onclick="closeHoldBillsModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div id="holdBillsList" class="space-y-3"></div>
        <div class="mt-6 flex justify-end">
            <button onclick="closeHoldBillsModal()" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Reprint Modal with Enhanced Search -->
<div id="reprintModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 no-print">
    <div class="bg-white rounded-xl p-6 w-full max-w-4xl max-h-[85vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-print text-blue-600"></i> Reprint Bill
            </h3>
            <button onclick="closeReprintModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <!-- Search Options -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-hashtag text-indigo-600"></i> Search by Invoice Number
                    </label>
                    <input type="text" id="searchInvoice" 
                           placeholder="Enter invoice number..." 
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400"
                           onkeyup="dynamicSearch()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-indigo-600"></i> Quick Date Range
                    </label>
                    <select id="quickSearch" onchange="quickDateSearch()" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400">
                        <option value="">Select Date Range</option>
                        <option value="today">📅 Today</option>
                        <option value="yesterday">📅 Yesterday</option>
                        <option value="last7days">📅 Last 7 Days</option>
                        <option value="last30days">📅 Last 30 Days</option>
                        <option value="thisweek">📅 This Week</option>
                        <option value="thismonth">📅 This Month</option>
                    </select>
                </div>
            </div>
            
            <div class="border-t border-gray-200 my-4 pt-4">
                <p class="text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-calendar-week text-indigo-600"></i> Custom Date Range Search
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Start Date</label>
                        <input type="date" id="startDate" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">End Date</label>
                        <input type="date" id="endDate" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400">
                    </div>
                    <div class="flex items-end">
                        <button onclick="searchByDateRange()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 w-full">
                            <i class="fas fa-search"></i> Search by Date
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-2 mt-4">
                <button onclick="loadAllInvoices()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 flex-1">
                    <i class="fas fa-list"></i> Show All Invoices
                </button>
                <button onclick="resetReprintSearch()" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </div>
        
        <!-- Results Header -->
        <div id="resultsHeader" class="flex justify-between items-center mb-3">
            <h4 class="font-semibold text-gray-700">
                <i class="fas fa-file-invoice"></i> Invoices List
            </h4>
            <span id="resultCount" class="text-sm text-gray-500"></span>
        </div>
        
        <!-- Results List -->
        <div id="invoiceList" class="max-h-96 overflow-y-auto space-y-2">
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3"></i>
                <p>Select search criteria and click search</p>
                <p class="text-sm mt-2">You can search by invoice number or date range</p>
            </div>
        </div>
        
        <!-- Selected Invoice Preview -->
        <div id="selectedInvoicePreview" class="mt-4 hidden">
            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                <div class="flex justify-between items-center">
                    <div>
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span class="font-semibold text-green-700">Selected Invoice:</span>
                        <span id="selectedInvoiceNo" class="font-bold text-green-800"></span>
                    </div>
                    <button onclick="printSelectedInvoice()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                        <i class="fas fa-print"></i> Print Now
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Close Button -->
        <div class="mt-6 flex justify-end">
            <button onclick="closeReprintModal()" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                Close
            </button>
        </div>
    </div>
</div>

<script>
let billItems = [];
let nextId = 1;
let currentInvoiceNo = '';
let selectedItemData = null;
let searchTimeout = null;
let selectedReprintInvoice = null;
let currentInvoices = [];

// Update date/time
function updateDateTime() {
    const now = new Date();
    document.getElementById('currentDate').innerText = now.toLocaleDateString('en-LK');
    document.getElementById('currentTime').innerText = now.toLocaleTimeString('en-LK');
}
updateDateTime();
setInterval(updateDateTime, 1000);

// Load held bill on startup
window.onload = function() {
    loadLastHoldBill();
    
    const searchInput = document.getElementById('searchInput');
    if(searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value.trim();
            if(searchTerm.length >= 2) {
                searchTimeout = setTimeout(() => searchItems(searchTerm), 300);
            } else {
                hideAutocomplete();
            }
        });
        
        searchInput.addEventListener('keypress', function(e) {

            if(e.key === 'Enter') {
                e.preventDefault();
                if(selectedItemData) {
                    selectItem(selectedItemData);
                } else if(this.value.trim()) {
                    addItem();
                }
            }
        });
    }
    
    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    const lastMonth = new Date();
    lastMonth.setMonth(lastMonth.getMonth() - 1);
    if(document.getElementById('startDate')) {
        document.getElementById('startDate').value = lastMonth.toISOString().split('T')[0];
        document.getElementById('endDate').value = today;
    }
};

// Load last hold bill
function loadLastHoldBill() {
    const saved = localStorage.getItem('currentHoldBill');
    if(saved) {
        Swal.fire({
            title: 'Hold Bill Found',
            text: 'You have a bill on hold. Do you want to load it?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Load It',
            cancelButtonText: 'No, Start Fresh'
        }).then(result => {
            if(result.isConfirmed) {
                const data = JSON.parse(saved);
                billItems = data.items || [];
                document.getElementById('billDiscount').value = data.discount || 0;
                document.getElementById('taxPercent').value = data.tax || 0;
                renderBill();
                calculateTotals();
                Swal.fire('Loaded!', 'Your held bill has been loaded', 'success');
            } else {
                localStorage.removeItem('currentHoldBill');
            }
        });
    }
}

// Dynamic search as user types
let dynamicSearchTimeout = null;
function dynamicSearch() {
    clearTimeout(dynamicSearchTimeout);
    dynamicSearchTimeout = setTimeout(() => {
        const searchTerm = document.getElementById('searchInvoice').value.trim();
        if(searchTerm.length >= 2) {
            searchInvoices();
        } else if(searchTerm.length === 0) {
            loadAllInvoices();
        }
    }, 500);
}

// Load all invoices
async function loadAllInvoices() {
    const invoiceListDiv = document.getElementById('invoiceList');
    invoiceListDiv.innerHTML = `
        <div class="text-center py-8">
            <div class="loading-spinner"></div>
            <p class="mt-2 text-gray-500">Loading all invoices...</p>
        </div>
    `;
    
    try {
        const response = await fetch('search_invoices.php?limit=100');
        const data = await response.json();
        currentInvoices = data.invoices || [];
        displayInvoiceResults(data);
    } catch(e) {
        invoiceListDiv.innerHTML = `
            <div class="text-center py-8 text-red-500">
                <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                <p>Error loading invoices</p>
            </div>
        `;
        console.error(e);
    }
}

// Quick date search
function quickDateSearch() {
    const option = document.getElementById('quickSearch').value;
    const today = new Date();
    let startDate = new Date();
    let endDate = new Date();
    
    switch(option) {
        case 'today':
            startDate = today;
            endDate = today;
            break;
        case 'yesterday':
            startDate.setDate(today.getDate() - 1);
            endDate = startDate;
            break;
        case 'last7days':
            startDate.setDate(today.getDate() - 7);
            endDate = today;
            break;
        case 'last30days':
            startDate.setDate(today.getDate() - 30);
            endDate = today;
            break;
        case 'thisweek':
            const day = today.getDay();
            startDate.setDate(today.getDate() - day);
            endDate = today;
            break;
        case 'thismonth':
            startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            endDate = today;
            break;

        default:
            return;
    }
    
    document.getElementById('startDate').value = startDate.toISOString().split('T')[0];
    document.getElementById('endDate').value = endDate.toISOString().split('T')[0];
    searchByDateRange();
}

// Search by date range
async function searchByDateRange() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if(!startDate || !endDate) {
        Swal.fire('Warning', 'Please select both start and end dates', 'warning');
        return;
    }
    
    const invoiceListDiv = document.getElementById('invoiceList');
    invoiceListDiv.innerHTML = `
        <div class="text-center py-8">
            <div class="loading-spinner"></div>
            <p class="mt-2 text-gray-500">Searching invoices from ${startDate} to ${endDate}...</p>
        </div>
    `;
    
    try {
        const response = await fetch(`search_invoices_by_date.php?start_date=${startDate}&end_date=${endDate}`);
        const data = await response.json();
        currentInvoices = data.invoices || [];
        displayInvoiceResults(data);
    } catch(e) {
        invoiceListDiv.innerHTML = `
            <div class="text-center py-8 text-red-500">
                <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                <p>Error searching invoices</p>
            </div>
        `;
        console.error(e);
    }
}

// Search invoices
async function searchInvoices() {
    const search = document.getElementById('searchInvoice').value;
    const invoiceListDiv = document.getElementById('invoiceList');
    
    if(!search.trim()) {
        loadAllInvoices();
        return;
    }
    
    invoiceListDiv.innerHTML = `
        <div class="text-center py-8">
            <div class="loading-spinner"></div>
            <p class="mt-2 text-gray-500">Searching for "${search}"...</p>
        </div>
    `;
    
    try {
        const response = await fetch(`search_invoices.php?search=${encodeURIComponent(search)}`);
        const data = await response.json();
        currentInvoices = data.invoices || [];
        displayInvoiceResults(data);
    } catch(e) {
        invoiceListDiv.innerHTML = `
            <div class="text-center py-8 text-red-500">
                <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                <p>Error searching invoices</p>
            </div>
        `;
        console.error(e);
    }
}

// Display invoice results with selection capability
function displayInvoiceResults(data) {
    const invoiceListDiv = document.getElementById('invoiceList');
    const resultCountSpan = document.getElementById('resultCount');
    
    if(data.success && data.invoices && data.invoices.length > 0) {
        resultCountSpan.innerHTML = `${data.invoices.length} invoice(s) found`;
        invoiceListDiv.innerHTML = `
            <div class="space-y-2">
                ${data.invoices.map(inv => `
                    <div class="invoice-card border rounded-lg p-4 hover:bg-gray-50 transition ${selectedReprintInvoice === inv.invoice_no ? 'selected' : ''}" 
                         onclick="selectInvoice('${inv.invoice_no}')">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-file-invoice text-indigo-600"></i>
                                    <p class="font-bold text-indigo-600 text-lg">${inv.invoice_no}</p>
                                    ${selectedReprintInvoice === inv.invoice_no ? '<span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded"><i class="fas fa-check"></i> Selected</span>' : ''}
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="far fa-calendar-alt"></i> ${new Date(inv.created_at).toLocaleString()}
                                </p>
                                <p class="text-sm font-semibold mt-1">
                                    Amount: Rs ${parseFloat(inv.final_total).toFixed(2)}
                                </p>
                            </div>
                            <button onclick="event.stopPropagation(); reprintInvoice('${inv.invoice_no}')" 
                                    class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                                <i class="fas fa-print"></i> Reprint Now
                            </button>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    } else {
        resultCountSpan.innerHTML = '0 invoices found';
        invoiceListDiv.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-search text-4xl mb-3"></i>
                <p>No invoices found</p>
                <p class="text-sm mt-2">Try different search criteria</p>
            </div>
        `;
    }
}

// Select invoice for preview
function selectInvoice(invoiceNo) {
    selectedReprintInvoice = invoiceNo;
    document.getElementById('selectedInvoiceNo').innerText = invoiceNo;
    document.getElementById('selectedInvoicePreview').classList.remove('hidden');
    
    // Highlight selected invoice
    const invoices = document.querySelectorAll('.invoice-card');
    invoices.forEach(inv => {
        inv.classList.remove('selected');
    });
    event.currentTarget.classList.add('selected');
}

// Print selected invoice
function printSelectedInvoice() {
    if(selectedReprintInvoice) {
        reprintInvoice(selectedReprintInvoice);
    } else {
        Swal.fire('Warning', 'Please select an invoice first', 'warning');
    }
}

// Reset search
function resetReprintSearch() {
    document.getElementById('searchInvoice').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    document.getElementById('quickSearch').value = '';
    selectedReprintInvoice = null;
    document.getElementById('selectedInvoicePreview').classList.add('hidden');
    
    const today = new Date().toISOString().split('T')[0];
    const lastMonth = new Date();
    lastMonth.setMonth(lastMonth.getMonth() - 1);
    document.getElementById('startDate').value = lastMonth.toISOString().split('T')[0];
    document.getElementById('endDate').value = today;
    
    loadAllInvoices();
}

// Search items from database
async function searchItems(searchTerm) {
    try {
        const response = await fetch(`search_items.php?search=${encodeURIComponent(searchTerm)}`);
        const items = await response.json();
        
        if(items && items.length > 0) {
            showAutocomplete(items);
        } else {
            hideAutocomplete();
        }
    } catch(e) {
        console.error('Search error:', e);
        hideAutocomplete();
    }
}

// Show autocomplete suggestions
function showAutocomplete(items) {
    const autocompleteDiv = document.getElementById('autocompleteList');
    autocompleteDiv.innerHTML = '';
    autocompleteDiv.classList.remove('hidden');
    
    items.forEach(item => {
        const div = document.createElement('div');
        div.innerHTML = `
            <div class="flex justify-between items-center">
                <div>
                    <span class="font-semibold text-indigo-600">${escapeHtml(item.item_code)}</span>
                    <span class="mx-2">-</span>
                    <span>${escapeHtml(item.description)}</span>
                </div>
                <div class="text-right">
                    <span class="text-green-600 font-bold">Rs ${parseFloat(item.price).toFixed(2)}</span>
                </div>
            </div>
        `;
        div.addEventListener('click', () => {
            selectItem(item);
        });
        autocompleteDiv.appendChild(div);
    });
}

// Hide autocomplete
function hideAutocomplete() {
    const autocompleteDiv = document.getElementById('autocompleteList');
    if(autocompleteDiv) {
        autocompleteDiv.classList.add('hidden');
        autocompleteDiv.innerHTML = '';
    }
}

// Select item from suggestions
function selectItem(item) {
    selectedItemData = item;
    document.getElementById('searchInput').value = `${item.item_code} - ${item.description}`;
    hideAutocomplete();
    
    Swal.fire({
        title: 'Add Item',
        html: `
            <div class="text-left">
                <p><strong>Item:</strong> ${item.description}</p>
                <p><strong>Code:</strong> ${item.item_code}</p>
                <p><strong>Price:</strong> Rs ${parseFloat(item.price).toFixed(2)}</p>
                <hr class="my-2">
                <label class="block mt-2">Quantity:</label>
                <input id="qtyInput" class="swal2-input" value="1" type="number" min="0.5" step="0.5">
                <label class="block mt-2">Discount %:</label>
                <input id="discInput" class="swal2-input" value="0" type="number" min="0" max="100" step="1">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Add to Bill',
        preConfirm: () => {
            const qty = parseFloat(document.getElementById('qtyInput').value);
            const disc = parseFloat(document.getElementById('discInput').value);
            if(isNaN(qty) || qty <= 0) {
                Swal.showValidationMessage('Quantity must be greater than 0');
                return false;
            }
            return { qty, disc };
        }
    }).then(result => {
        if(result.isConfirmed) {
            addItemToBill(item, result.value.qty, result.value.disc);
            document.getElementById('searchInput').value = '';
            selectedItemData = null;
        }
    });
}

// Add selected item
function addSelectedItem() {
    if(selectedItemData) {
        selectItem(selectedItemData);
    } else {
        addItem();
    }
}

// Add item to bill
function addItemToBill(item, qty, discount) {
    billItems.push({
        id: nextId++,
        item_code: item.item_code,
        description: item.description,
        qty: qty,
        price: parseFloat(item.price),
        discount: discount
    });
    renderBill();
    calculateTotals();
}

// Original add item function
async function addItem() {
    const search = document.getElementById('searchInput').value.trim();
    if(!search) {
        Swal.fire('Warning', 'Enter item code or description', 'warning');
        return;
    }
    
    try {
        const response = await fetch(`get_item.php?search=${encodeURIComponent(search)}`);
        const item = await response.json();
        
        if(item && item.item_code) {
            const qtyResult = await Swal.fire({ title: 'Quantity', input: 'number', inputValue: 1 });
            const discResult = await Swal.fire({ title: 'Discount %', input: 'number', inputValue: 0 });
            
            billItems.push({
                id: nextId++,
                item_code: item.item_code,
                description: item.description,
                qty: parseFloat(qtyResult.value || 1),
                price: parseFloat(item.price),
                discount: parseFloat(discResult.value || 0)
            });
            renderBill();
            calculateTotals();
            document.getElementById('searchInput').value = '';
            selectedItemData = null;
        } else {
            const createNew = await Swal.fire({
                title: 'Item Not Found',
                text: 'Create new item?',
                showCancelButton: true
            });
            if(createNew.isConfirmed) {
                const priceResult = await Swal.fire({ title: 'Price (Rs)', input: 'number' });
                await fetch('add_item.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `description=${encodeURIComponent(search)}&price=${priceResult.value}`
                });
                addItem();
            }
        }
    } catch(e) {
        Swal.fire('Error', 'Could not fetch item', 'error');
    }
}

// Hold current bill
function holdBill() {
    if(billItems.length === 0) {
        Swal.fire('Warning', 'No items to hold', 'warning');
        return;
    }
    
    const holdData = {
        id: Date.now(),
        timestamp: new Date().toLocaleString(),
        items: billItems,
        discount: document.getElementById('billDiscount').value,
        tax: document.getElementById('taxPercent').value,
        total: document.getElementById('finalTotal').innerText
    };
    
    let heldBills = JSON.parse(localStorage.getItem('heldBills') || '[]');
    heldBills.push(holdData);
    localStorage.setItem('heldBills', JSON.stringify(heldBills));
    localStorage.setItem('currentHoldBill', JSON.stringify({
        items: billItems,
        discount: document.getElementById('billDiscount').value,
        tax: document.getElementById('taxPercent').value
    }));
    
    Swal.fire({
        icon: 'success',
        title: 'Bill Held Successfully!',
        text: 'You can retrieve it from "View Hold Bills"',
        timer: 2000
    });
}

// Show all held bills
function showHoldBills() {
    const heldBills = JSON.parse(localStorage.getItem('heldBills') || '[]');
    const modal = document.getElementById('holdBillsModal');
    const listDiv = document.getElementById('holdBillsList');
    
    if(heldBills.length === 0) {
        listDiv.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-6xl mb-3"></i>
                <p>No held bills found</p>
            </div>
        `;
    } else {
        listDiv.innerHTML = heldBills.reverse().map(bill => `
            <div class="hold-bill-card border rounded-lg p-4 bg-gray-50">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-bold text-lg">Bill #${bill.id}</p>
                        <p class="text-sm text-gray-600">Held on: ${bill.timestamp}</p>
                        <p class="text-sm mt-1">Items: ${bill.items.length} | Total: Rs ${bill.total}</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="loadHoldBill(${bill.id})" class="bg-green-600 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-download"></i> Load
                        </button>
                        <button onclick="deleteHoldBill(${bill.id})" class="bg-red-500 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// Load specific hold bill
function loadHoldBill(billId) {
    const heldBills = JSON.parse(localStorage.getItem('heldBills') || '[]');
    const bill = heldBills.find(b => b.id === billId);
    
    if(bill) {
        billItems = bill.items;
        document.getElementById('billDiscount').value = bill.discount;
        document.getElementById('taxPercent').value = bill.tax;
        renderBill();
        calculateTotals();
        localStorage.setItem('currentHoldBill', JSON.stringify({
            items: bill.items,
            discount: bill.discount,
            tax: bill.tax
        }));
        Swal.fire('Loaded!', 'Bill loaded successfully', 'success');
        closeHoldBillsModal();
    }
}

// Delete hold bill
function deleteHoldBill(billId) {
    Swal.fire({
        title: 'Delete Hold Bill?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then(result => {
        if(result.isConfirmed) {
            let heldBills = JSON.parse(localStorage.getItem('heldBills') || '[]');
            heldBills = heldBills.filter(b => b.id !== billId);
            localStorage.setItem('heldBills', JSON.stringify(heldBills));
            showHoldBills();
            Swal.fire('Deleted!', 'Hold bill has been deleted.', 'success');
        }
    });
}

function closeHoldBillsModal() {
    const modal = document.getElementById('holdBillsModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Render bill table
function renderBill() {
    const tbody = document.getElementById('billItemsBody');
    if(billItems.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-400">No items added右侧</tr>';
        return;
    }
    
    tbody.innerHTML = billItems.map(item => {
        const total = (item.qty * item.price) * (1 - item.discount / 100);
        return `
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3 text-sm font-mono">${escapeHtml(item.item_code)}</td>
                <td class="p-3">${escapeHtml(item.description)}</td>
                <td class="p-3 text-center">
                    <button onclick="updateQty(${item.id}, -1)" class="bg-gray-200 px-2 rounded hover:bg-gray-300">-</button>
                    <span class="mx-2 font-semibold">${item.qty}</span>
                    <button onclick="updateQty(${item.id}, 1)" class="bg-gray-200 px-2 rounded hover:bg-gray-300">+</button>
                </td>
                <td class="p-3 text-right font-mono">${item.price.toFixed(2)}</td>
                <td class="p-3 text-center">
                    <input type="number" value="${item.discount}" onchange="updateDisc(${item.id}, this.value)" 
                           class="w-16 border rounded text-center px-1" min="0" max="100" step="1">
                </td>
                <td class="p-3 text-right font-bold font-mono">${total.toFixed(2)}</td>
                <td class="p-3 text-center">
                    <button onclick="removeItem(${item.id})" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

function updateQty(id, delta) {
    const item = billItems.find(i => i.id === id);
    if(item) {
        item.qty = Math.max(0.5, item.qty + delta);
        renderBill();
        calculateTotals();
    }
}

function updateDisc(id, value) {
    const item = billItems.find(i => i.id === id);
    if(item) {
        item.discount = parseFloat(value) || 0;
        renderBill();
        calculateTotals();
    }
}

function removeItem(id) {
    billItems = billItems.filter(i => i.id !== id);
    renderBill();
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    billItems.forEach(item => {
        subtotal += (item.qty * item.price) * (1 - item.discount / 100);
    });
    
    const discountPercent = parseFloat(document.getElementById('billDiscount').value) || 0;
    const taxPercent = parseFloat(document.getElementById('taxPercent').value) || 0;
    
    const discountAmount = subtotal * (discountPercent / 100);
    const afterDiscount = subtotal - discountAmount;
    const taxAmount = afterDiscount * (taxPercent / 100);
    const finalTotal = afterDiscount + taxAmount;
    
    document.getElementById('subtotal').innerText = subtotal.toFixed(2);
    document.getElementById('discountAmount').innerText = discountAmount.toFixed(2);
    document.getElementById('taxAmount').innerText = taxAmount.toFixed(2);
    document.getElementById('finalTotal').innerText = finalTotal.toFixed(2);
}

function clearBill() {
    Swal.fire({
        title: 'Clear bill?',
        text: "All items will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, clear it'
    }).then(result => {
        if(result.isConfirmed) {
            billItems = [];
            document.getElementById('billDiscount').value = 0;
            document.getElementById('taxPercent').value = 0;
            renderBill();
            calculateTotals();
            localStorage.removeItem('currentHoldBill');
            Swal.fire('Cleared!', 'Bill has been cleared.', 'success');
        }
    });
}

async function printCurrentBill() {
    if(billItems.length === 0) {
        Swal.fire('Error', 'No items to print', 'error');
        return;
    }
    
    const invoiceData = {
        items: billItems,
        bill_discount: parseFloat(document.getElementById('billDiscount').value),
        tax: parseFloat(document.getElementById('taxPercent').value)
    };
    
    try {
        const response = await fetch('save_invoice.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(invoiceData)
        });
        const result = await response.json();
        
        if(result.success) {
            currentInvoiceNo = result.invoice;
            await printBill(currentInvoiceNo);
            localStorage.removeItem('currentHoldBill');
            
            Swal.fire('Success', `Invoice ${currentInvoiceNo} saved and printed`, 'success').then(() => {
                billItems = [];
                renderBill();
                calculateTotals();
            });
        } else {
            Swal.fire('Error', 'Failed to save invoice', 'error');
        }
    } catch(e) {
        Swal.fire('Error', 'Could not connect to server', 'error');
    }
}

// Unified print bill function
async function printBill(invoiceNo) {
    try {
        Swal.fire({
            title: 'Loading Invoice...',
            text: 'Please wait while we fetch the invoice details',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const response = await fetch(`get_invoice.php?invoice_no=${invoiceNo}`);
        const data = await response.json();
        
        Swal.close();
        
        if(data.success) {
            const invoice = data.invoice;
            const items = data.items;
            
            document.getElementById('printInvoiceNo').innerText = invoice.invoice_no;
            const date = new Date(invoice.created_at);
            document.getElementById('printDate').innerText = date.toLocaleDateString('en-LK');
            document.getElementById('printTime').innerText = date.toLocaleTimeString('en-LK');
            
            let itemsHtml = '';
            items.forEach(item => {
                itemsHtml += `
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 8px;">${escapeHtml(item.description)}</td>
                        <td style="padding: 8px; text-align: center;">${item.quantity}</td>
                        <td style="padding: 8px; text-align: right;">${parseFloat(item.price).toFixed(2)}</td>
                        <td style="padding: 8px; text-align: right;">${parseFloat(item.total).toFixed(2)}</td>
                    </tr>
                `;
            });
            document.getElementById('printItemsBody').innerHTML = itemsHtml;
            
            document.getElementById('printSubtotal').innerText = parseFloat(invoice.subtotal).toFixed(2);
            document.getElementById('printDiscPercent').innerText = invoice.bill_discount_percent;
            document.getElementById('printDiscAmount').innerText = parseFloat(invoice.bill_discount_amount).toFixed(2);
            document.getElementById('printTaxPercent').innerText = invoice.tax_percent;
            document.getElementById('printTaxAmount').innerText = parseFloat(invoice.tax_amount).toFixed(2);
            document.getElementById('printFinalTotal').innerText = parseFloat(invoice.final_total).toFixed(2);
            
            const printDiv = document.getElementById('printableBill');
            printDiv.style.display = 'block';
            
            setTimeout(() => {
                window.print();
                setTimeout(() => {
                    printDiv.style.display = 'none';
                    Swal.fire({
                        icon: 'success',
                        title: 'Printed Successfully!',
                        text: `Invoice ${invoiceNo} has been printed`,
                        timer: 2000
                    });
                }, 500);
            }, 100);
        } else {
            Swal.fire('Error', data.error || 'Could not fetch invoice', 'error');
        }
    } catch(e) {
        Swal.fire('Error', 'Could not fetch invoice details', 'error');
        console.error(e);
    }
}

// Show reprint modal
function showReprintModal() {
    document.getElementById('reprintModal').classList.remove('hidden');
    document.getElementById('reprintModal').classList.add('flex');
    selectedReprintInvoice = null;
    document.getElementById('selectedInvoicePreview').classList.add('hidden');
    loadAllInvoices();
}

// Close reprint modal
function closeReprintModal() {
    document.getElementById('reprintModal').classList.add('hidden');
    document.getElementById('reprintModal').classList.remove('flex');
}

// Reprint specific invoice
async function reprintInvoice(invoiceNo) {
    closeReprintModal();
    await printBill(invoiceNo);
}

function escapeHtml(str) {
    if(!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if(m === '&') return '&amp;';
        if(m === '<') return '&lt;';
        if(m === '>') return '&gt;';
        return m;
    });
}

// Click outside to hide autocomplete
document.addEventListener('click', function(e) {
    const searchContainer = document.querySelector('.search-container');
    if(searchContainer && !searchContainer.contains(e.target)) {
        hideAutocomplete();
    }
});
</script>

</body>
</html>