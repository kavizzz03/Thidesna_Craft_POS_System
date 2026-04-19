<?php include "db.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thidesna Craft - Business Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.15);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.2);
        }
        
        .btn-billing {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            transition: all 0.3s ease;
        }
        
        .btn-billing:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(245, 87, 108, 0.3);
        }
        
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .invoice-row, .item-row {
            transition: all 0.2s ease;
        }
        
        .invoice-row:hover, .item-row:hover {
            background-color: #f3f4f6;
        }
        
        .footer {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
        }
        
        .nav-tab {
            transition: all 0.3s ease;
            cursor: pointer;
            border-bottom: 3px solid transparent;
        }
        
        .nav-tab:hover, .nav-tab.active {
            border-bottom-color: #6366f1;
            color: #6366f1;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .edit-btn, .delete-btn {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .edit-btn:hover, .delete-btn:hover {
            transform: scale(1.05);
        }
        
        .item-row {
            cursor: default;
        }
    </style>
</head>
<body class="p-6">

<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="glass-card p-6 mb-6 animate-fadeInUp">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-gem text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">THIDASNA CRAFT</h1>
                    <p class="text-sm text-gray-500">Premium Handicraft Store Management System</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-calendar-alt text-indigo-600"></i> 
                    <span id="currentDate"></span>
                </p>
                <p class="text-sm text-gray-600">
                    <i class="fas fa-clock text-indigo-600"></i> 
                    <span id="currentTime"></span>
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="glass-card p-4 mb-6 animate-fadeInUp" style="animation-delay: 0.05s">
        <div class="flex gap-6">
            <div class="nav-tab px-4 py-2 font-semibold text-gray-700 active" onclick="showTab('dashboard', this)">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </div>
            <div class="nav-tab px-4 py-2 font-semibold text-gray-700" onclick="showTab('items', this)">
                <i class="fas fa-boxes mr-2"></i> Items Management
            </div>
            <div class="nav-tab px-4 py-2 font-semibold text-gray-700" onclick="showTab('invoices', this)">
                <i class="fas fa-file-invoice mr-2"></i> Invoices
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div id="dashboardContent" class="animate-fadeInUp">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card p-6 text-white" onclick="showTab('items', null)">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Total Items</p>
                        <p class="text-3xl font-bold mt-2" id="totalItems">0</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-boxes text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-xs opacity-75">
                        <i class="fas fa-chart-line"></i> Available Products
                    </span>
                </div>
            </div>

            <div class="stat-card p-6 text-white" style="background: linear-gradient(135deg, #3b82f6, #1e40af);" onclick="showTab('invoices', null)">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Total Bills</p>
                        <p class="text-3xl font-bold mt-2" id="totalBills">0</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-receipt text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-xs opacity-75">
                        <i class="fas fa-chart-line"></i> All time invoices
                    </span>
                </div>
            </div>

            <div class="stat-card p-6 text-white" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Today's Sales</p>
                        <p class="text-3xl font-bold mt-2">Rs <span id="todaySales">0</span></p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-xs opacity-75">
                        <i class="fas fa-calendar"></i> Today's revenue
                    </span>
                </div>
            </div>

            <div class="stat-card p-6 text-white cursor-pointer btn-billing" onclick="redirectToBilling()">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Quick Action</p>
                        <p class="text-xl font-bold mt-2">Create New Bill</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-plus-circle text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-xs opacity-75">
                        <i class="fas fa-arrow-right"></i> Click to start billing
                    </span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="glass-card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-bar text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Sales Overview</h3>
                        <p class="text-xs text-gray-500">Last 7 days performance</p>
                    </div>
                </div>
                <canvas id="salesChart" height="250"></canvas>
            </div>

            <div class="glass-card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-pie text-pink-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Revenue Distribution</h3>
                        <p class="text-xs text-gray-500">Top 5 selling items</p>
                    </div>
                </div>
                <canvas id="pieChart" height="250"></canvas>
            </div>
        </div>

        <!-- Recent Invoices Table -->
        <div class="glass-card p-6">
            <div class="flex justify-between items-center mb-4 flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-invoice text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Recent Invoices</h3>
                        <p class="text-xs text-gray-500">Last 10 transactions</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <input type="text" id="searchInvoice" placeholder="Search invoice..." 
                           class="border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400">
                    <button onclick="searchInvoices()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <button onclick="refreshInvoices()" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-600">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            
            <div class="table-container">
                <table class="w-full">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr class="border-b">
                            <th class="p-3 text-left text-sm font-semibold text-gray-600">Invoice No</th>
                            <th class="p-3 text-left text-sm font-semibold text-gray-600">Date & Time</th>
                            <th class="p-3 text-right text-sm font-semibold text-gray-600">Amount (Rs)</th>
                            <th class="p-3 text-center text-sm font-semibold text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody id="invoicesTableBody">
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-400">
                                <i class="fas fa-spinner fa-spin text-2xl"></i>
                                <p class="mt-2">Loading invoices...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Items Management Content -->
    <div id="itemsContent" style="display: none;" class="animate-fadeInUp">
        <div class="glass-card p-6">
            <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-boxes text-indigo-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Items Management</h2>
                        <p class="text-sm text-gray-500">Manage your product inventory</p>
                    </div>
                </div>
                <button onclick="openAddItemModal()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-plus-circle"></i> Add New Item
                </button>
            </div>
            
            <div class="mb-6">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchItem" placeholder="Search items by code or description..." 
                           class="w-full pl-10 pr-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-400"
                           onkeyup="filterItems()">
                </div>
            </div>
            
            <div class="table-container">
                <table class="w-full">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr class="border-b">
                            <th class="p-3 text-left text-sm font-semibold text-gray-600">Item Code</th>
                            <th class="p-3 text-left text-sm font-semibold text-gray-600">Description</th>
                            <th class="p-3 text-right text-sm font-semibold text-gray-600">Price (Rs)</th>
                            <th class="p-3 text-center text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Invoices Management Content -->
    <div id="invoicesContent" style="display: none;" class="animate-fadeInUp">
        <div class="glass-card p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-invoice-dollar text-green-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Invoice Management</h2>
                    <p class="text-sm text-gray-500">View and manage all invoices</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Invoice</label>
                    <input type="text" id="searchInvoiceManage" placeholder="Invoice number..." 
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" id="startDateInvoice" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" id="endDateInvoice" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>
            
            <div class="flex gap-2 mb-6">
                <button onclick="searchInvoicesManage()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-search"></i> Search
                </button>
                <button onclick="resetInvoiceSearch()" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
            
            <div class="table-container">
                <table class="w-full">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr class="border-b">
                            <th class="p-3 text-left text-sm font-semibold text-gray-600">Invoice No</th>
                            <th class="p-3 text-left text-sm font-semibold text-gray-600">Date & Time</th>
                            <th class="p-3 text-right text-sm font-semibold text-gray-600">Amount (Rs)</th>
                            <th class="p-3 text-center text-sm font-semibold text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody id="allInvoicesTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer rounded-xl p-6 mt-8 text-white text-center animate-fadeInUp" style="animation-delay: 0.5s">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="text-left">
                <p class="text-sm opacity-90">© 2026-2027 Thidasna Craft - All Rights Reserved</p>
                <p class="text-xs opacity-75 mt-1">Galle Road, Balapitiya, Sri Lanka</p>
            </div>
            <div class="text-center">
                <i class="fas fa-code text-2xl opacity-50"></i>
                <p class="text-sm font-semibold mt-1">Developed by Vexel IT by Kavizz</p>
                <p class="text-xs opacity-75">📞 94740890730</p>
            </div>
            <div class="text-right">
                <i class="fas fa-certificate text-2xl opacity-50"></i>
                <p class="text-xs opacity-75 mt-1">Version 3.0 | Premium Support</p>
            </div>
        </div>
    </div>
</div>

<script>
let salesChart = null;
let pieChart = null;
let allItems = [];

// Update date and time
function updateDateTime() {
    const now = new Date();
    document.getElementById('currentDate').innerText = now.toLocaleDateString('en-LK', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    document.getElementById('currentTime').innerText = now.toLocaleTimeString('en-LK');
}
updateDateTime();
setInterval(updateDateTime, 1000);

// Show tab
function showTab(tab, element) {
    // Update navigation active state
    document.querySelectorAll('.nav-tab').forEach(nav => {
        nav.classList.remove('active');
    });
    if(element) element.classList.add('active');
    
    // Hide all content
    document.getElementById('dashboardContent').style.display = 'none';
    document.getElementById('itemsContent').style.display = 'none';
    document.getElementById('invoicesContent').style.display = 'none';
    
    // Show selected content
    if(tab === 'dashboard') {
        document.getElementById('dashboardContent').style.display = 'block';
        loadDashboardData();
    } else if(tab === 'items') {
        document.getElementById('itemsContent').style.display = 'block';
        loadItems();
    } else if(tab === 'invoices') {
        document.getElementById('invoicesContent').style.display = 'block';
        loadAllInvoices();
    }
}

// Load dashboard data
async function loadDashboardData() {
    try {
        const response = await fetch('dashboard_data.php');
        const data = await response.json();
        
        if(data.success) {
            document.getElementById('totalItems').innerText = data.total_items || 0;
            document.getElementById('totalBills').innerText = data.total_bills;
            document.getElementById('todaySales').innerText = data.today_sales.toFixed(2);
            
            updateSalesChart(data.last_7_days);
            updatePieChart(data.top_items);
            updateInvoicesTable(data.recent_invoices);
        }
    } catch(e) {
        console.error('Error loading dashboard:', e);
    }
}

// Update sales chart
function updateSalesChart(salesData) {
    const ctx = document.getElementById('salesChart').getContext('2d');
    if(salesChart) salesChart.destroy();
    
    const labels = salesData.map(item => item.date);
    const values = salesData.map(item => parseFloat(item.total));
    
    salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sales (Rs)',
                data: values,
                backgroundColor: 'rgba(99, 102, 241, 0.6)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rs ' + v } } }
        }
    });
}

// Update pie chart
function updatePieChart(topItems) {
    const ctx = document.getElementById('pieChart').getContext('2d');
    if(pieChart) pieChart.destroy();
    
    const labels = topItems.map(item => item.description);
    const values = topItems.map(item => parseFloat(item.total_sales));
    const colors = ['#6366f1', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'];
    
    pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors.slice(0, labels.length),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 } } },
                tooltip: { callbacks: { label: (ctx) => {
                    const total = ctx.dataset.data.reduce((a,b) => a + b, 0);
                    const percent = ((ctx.raw / total) * 100).toFixed(1);
                    return `${ctx.label}: Rs ${ctx.raw.toFixed(2)} (${percent}%)`;
                } } }
            }
        }
    });
}

// Update invoices table
function updateInvoicesTable(invoices) {
    const tbody = document.getElementById('invoicesTableBody');
    if(!invoices || invoices.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center py-8 text-gray-400">No invoices found</td></tr>';
        return;
    }
    
    tbody.innerHTML = invoices.map(inv => `
        <tr class="border-b invoice-row" onclick="viewInvoiceDetails('${inv.invoice_no}')">
            <td class="p-3 text-sm font-mono text-indigo-600">${inv.invoice_no}</td>
            <td class="p-3 text-sm text-gray-600">${new Date(inv.created_at).toLocaleString()}</td>
            <td class="p-3 text-right text-sm font-bold text-green-600">Rs ${parseFloat(inv.final_total).toFixed(2)}</td>
            <td class="p-3 text-center">
                <button onclick="event.stopPropagation(); reprintInvoice('${inv.invoice_no}')" 
                        class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                    <i class="fas fa-print"></i> Reprint
                </button>
            </td>
        </tr>
    `).join('');
}

// Load items
async function loadItems() {
    try {
        const response = await fetch('get_items.php');
        allItems = await response.json();
        displayItems(allItems);
    } catch(e) {
        console.error('Error loading items:', e);
    }
}

// Display items
function displayItems(items) {
    const tbody = document.getElementById('itemsTableBody');
    if(!items || items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center py-8 text-gray-400">No items found</td></tr>';
        return;
    }
    
    tbody.innerHTML = items.map(item => `
        <tr class="border-b item-row">
            <td class="p-3 text-sm font-mono text-indigo-600">${escapeHtml(item.item_code)}</td>
            <td class="p-3 text-sm">${escapeHtml(item.description)}</td>
            <td class="p-3 text-right text-sm font-bold text-green-600">Rs ${parseFloat(item.price).toFixed(2)}</td>
            <td class="p-3 text-center">
                <button onclick="editItem(${item.id})" class="edit-btn bg-yellow-500 text-white px-3 py-1 rounded text-xs mr-2 hover:bg-yellow-600">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button onclick="deleteItem(${item.id})" class="delete-btn bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </td>
        </tr>
    `).join('');
}

// Filter items
function filterItems() {
    const search = document.getElementById('searchItem').value.toLowerCase();
    const filtered = allItems.filter(item => 
        item.item_code.toLowerCase().includes(search) || 
        item.description.toLowerCase().includes(search)
    );
    displayItems(filtered);
}

// Generate automatic item code
function generateItemCode() {
    const prefix = 'ITEM';
    const date = new Date();
    const year = date.getFullYear().toString().slice(-2);
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    return `${prefix}${year}${month}${day}${random}`;
}

// Open add item modal
function openAddItemModal() {
    const autoCode = generateItemCode();
    
    Swal.fire({
        title: 'Add New Item',
        html: `
            <div class="text-left">
                <label class="block text-sm font-medium text-gray-700 mb-1">Item Code</label>
                <input id="itemCode" class="swal2-input w-full mb-3" value="${autoCode}" placeholder="Item Code">
                <p class="text-xs text-gray-500 mb-3">Leave empty for auto-generation</p>
                
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <input id="itemDesc" class="swal2-input w-full mb-3" placeholder="Enter item description" required>
                
                <label class="block text-sm font-medium text-gray-700 mb-1">Price (Rs)</label>
                <input id="itemPrice" class="swal2-input w-full" type="number" step="0.01" placeholder="Enter price" required>
            </div>
        `,
        width: '500px',
        showCancelButton: true,
        confirmButtonText: 'Add Item',
        preConfirm: () => {
            const code = document.getElementById('itemCode').value || generateItemCode();
            const desc = document.getElementById('itemDesc').value;
            const price = document.getElementById('itemPrice').value;
            if(!desc || !price) {
                Swal.showValidationMessage('Description and Price are required');
                return false;
            }
            if(price <= 0) {
                Swal.showValidationMessage('Price must be greater than 0');
                return false;
            }
            return { code, desc, price };
        }
    }).then(async (result) => {
        if(result.isConfirmed) {
            const formData = new URLSearchParams();
            formData.append('item_code', result.value.code);
            formData.append('description', result.value.desc);
            formData.append('price', result.value.price);
            
            const response = await fetch('add_item.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            });
            const data = await response.json();
            
            if(data.success) {
                Swal.fire('Success', 'Item added successfully!', 'success');
                loadItems();
                loadDashboardData();
            } else {
                Swal.fire('Error', data.error || 'Failed to add item', 'error');
            }
        }
    });
}

// Edit item
async function editItem(id) {
    try {
        const response = await fetch(`get_item_by_id.php?id=${id}`);
        const item = await response.json();
        
        Swal.fire({
            title: 'Edit Item',
            html: `
                <div class="text-left">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Code</label>
                    <input id="editCode" class="swal2-input w-full mb-3" value="${escapeHtml(item.item_code)}" readonly disabled>
                    
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input id="editDesc" class="swal2-input w-full mb-3" value="${escapeHtml(item.description)}" required>
                    
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (Rs)</label>
                    <input id="editPrice" class="swal2-input w-full" type="number" step="0.01" value="${item.price}" required>
                </div>
            `,
            width: '500px',
            showCancelButton: true,
            confirmButtonText: 'Update Item',
            preConfirm: () => {
                const desc = document.getElementById('editDesc').value;
                const price = document.getElementById('editPrice').value;
                if(!desc || !price) {
                    Swal.showValidationMessage('All fields are required');
                    return false;
                }
                if(price <= 0) {
                    Swal.showValidationMessage('Price must be greater than 0');
                    return false;
                }
                return { desc, price };
            }
        }).then(async (result) => {
            if(result.isConfirmed) {
                const formData = new URLSearchParams();
                formData.append('id', id);
                formData.append('description', result.value.desc);
                formData.append('price', result.value.price);
                
                const response = await fetch('update_item.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData
                });
                const data = await response.json();
                
                if(data.success) {
                    Swal.fire('Success', 'Item updated successfully!', 'success');
                    loadItems();
                    loadDashboardData();
                } else {
                    Swal.fire('Error', 'Failed to update item', 'error');
                }
            }
        });
    } catch(e) {
        Swal.fire('Error', 'Could not load item details', 'error');
    }
}

// Delete item
async function deleteItem(id) {
    const result = await Swal.fire({
        title: 'Delete Item?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    });
    
    if(result.isConfirmed) {
        const response = await fetch(`delete_item.php?id=${id}`);
        const data = await response.json();
        
        if(data.success) {
            Swal.fire('Deleted!', 'Item has been deleted.', 'success');
            loadItems();
            loadDashboardData();
        } else {
            Swal.fire('Error', 'Failed to delete item', 'error');
        }
    }
}

// Load all invoices
async function loadAllInvoices() {
    try {
        const response = await fetch('search_invoices.php?limit=100');
        const data = await response.json();
        displayAllInvoices(data.invoices);
    } catch(e) {
        console.error('Error loading invoices:', e);
    }
}

// Display all invoices
function displayAllInvoices(invoices) {
    const tbody = document.getElementById('allInvoicesTableBody');
    if(!invoices || invoices.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center py-8 text-gray-400">No invoices found</td></tr>';
        return;
    }
    
    tbody.innerHTML = invoices.map(inv => `
        <tr class="border-b invoice-row" onclick="viewInvoiceDetails('${inv.invoice_no}')">
            <td class="p-3 text-sm font-mono text-indigo-600">${inv.invoice_no}</td>
            <td class="p-3 text-sm text-gray-600">${new Date(inv.created_at).toLocaleString()}</td>
            <td class="p-3 text-right text-sm font-bold text-green-600">Rs ${parseFloat(inv.final_total).toFixed(2)}</td>
            <td class="p-3 text-center">
                <button onclick="event.stopPropagation(); reprintInvoice('${inv.invoice_no}')" 
                        class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                    <i class="fas fa-print"></i> Reprint
                </button>
             </td>
        </tr>
    `).join('');
}

// Search invoices
async function searchInvoices() {
    const search = document.getElementById('searchInvoice').value;
    const response = await fetch(`search_invoices.php?search=${encodeURIComponent(search)}`);
    const data = await response.json();
    updateInvoicesTable(data.invoices);
}

// Search invoices manage
async function searchInvoicesManage() {
    const search = document.getElementById('searchInvoiceManage').value;
    const startDate = document.getElementById('startDateInvoice').value;
    const endDate = document.getElementById('endDateInvoice').value;
    
    let url = 'search_invoices.php?';
    if(search) url += `search=${encodeURIComponent(search)}&`;
    if(startDate && endDate) url += `start_date=${startDate}&end_date=${endDate}`;
    
    const response = await fetch(url);
    const data = await response.json();
    displayAllInvoices(data.invoices);
}

// Reset invoice search
function resetInvoiceSearch() {
    document.getElementById('searchInvoiceManage').value = '';
    document.getElementById('startDateInvoice').value = '';
    document.getElementById('endDateInvoice').value = '';
    loadAllInvoices();
}

// Refresh invoices
function refreshInvoices() {
    document.getElementById('searchInvoice').value = '';
    loadDashboardData();
}

// View invoice details
async function viewInvoiceDetails(invoiceNo) {
    const response = await fetch(`get_invoice.php?invoice_no=${invoiceNo}`);
    const data = await response.json();
    
    if(data.success) {
        const invoice = data.invoice;
        const items = data.items;
        
        let itemsHtml = '<div class="max-h-96 overflow-y-auto"><table class="w-full text-sm"><thead><tr class="border-b"><th class="text-left p-2">Item</th><th class="text-center p-2">Qty</th><th class="text-right p-2">Price</th><th class="text-right p-2">Total</th></tr></thead><tbody>';
        items.forEach(item => {
            itemsHtml += `<tr><td class="p-2">${escapeHtml(item.description)}</td><td class="text-center p-2">${item.quantity}</td><td class="text-right p-2">Rs ${parseFloat(item.price).toFixed(2)}</td><td class="text-right p-2">Rs ${parseFloat(item.total).toFixed(2)}</td></tr>`;
        });
        itemsHtml += '</tbody>}</table></div>';
        
        Swal.fire({
            title: `Invoice ${invoice.invoice_no}`,
            html: `
                <div class="text-left">
                    <p><strong>Date:</strong> ${new Date(invoice.created_at).toLocaleString()}</p>
                    <p><strong>Subtotal:</strong> Rs ${parseFloat(invoice.subtotal).toFixed(2)}</p>
                    <p><strong>Discount:</strong> Rs ${parseFloat(invoice.bill_discount_amount).toFixed(2)}</p>
                    <p><strong>Tax:</strong> Rs ${parseFloat(invoice.tax_amount).toFixed(2)}</p>
                    <p class="text-lg font-bold mt-2"><strong>Total:</strong> Rs ${parseFloat(invoice.final_total).toFixed(2)}</p>
                    <hr class="my-3">
                    <p class="font-semibold mb-2">Items:</p>
                    ${itemsHtml}
                </div>
            `,
            width: '700px',
            showCancelButton: true,
            confirmButtonText: 'Print Bill'
        }).then(result => {
            if(result.isConfirmed) reprintInvoice(invoiceNo);
        });
    }
}

// Reprint invoice
async function reprintInvoice(invoiceNo) {
    const response = await fetch(`get_invoice.php?invoice_no=${invoiceNo}`);
    const data = await response.json();
    
    if(data.success) {
        const invoice = data.invoice;
        const items = data.items;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head><title>Invoice ${invoice.invoice_no}</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 30px; max-width: 800px; margin: 0 auto; }
                .header { text-align: center; margin-bottom: 30px; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
                th { background: #f3f4f6; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .totals { text-align: right; margin-top: 20px; }
                .footer { text-align: center; margin-top: 50px; font-size: 12px; }
                .signatures { display: flex; justify-content: space-between; margin-top: 50px; }
            </style>
            </head>
            <body>
                <div class="header">
                    <h2>THIDASNA CRAFT</h2>
                    <p>Galle Road, Balapitiya, Sri Lanka</p>
                    <p>Tel: 0741977324</p>
                    <hr>
                    <h3>Invoice No: ${invoice.invoice_no}</h3>
                    <p>Date: ${new Date(invoice.created_at).toLocaleString()}</p>
                    <hr>
                </div>
                <table>
                    <thead><tr><th>Item</th><th class="text-center">Qty</th><th class="text-right">Price</th><th class="text-right">Total</th></tr></thead>
                    <tbody>
                        ${items.map(item => `<tr><td>${escapeHtml(item.description)}</td><td class="text-center">${item.quantity}</td><td class="text-right">Rs ${parseFloat(item.price).toFixed(2)}</td><td class="text-right">Rs ${parseFloat(item.total).toFixed(2)}</td></tr>`).join('')}
                    </tbody>
                </table>
                <div class="totals">
                    <p>Subtotal: Rs ${parseFloat(invoice.subtotal).toFixed(2)}</p>
                    <p>Discount: -Rs ${parseFloat(invoice.bill_discount_amount).toFixed(2)}</p>
                    <p>Tax: +Rs ${parseFloat(invoice.tax_amount).toFixed(2)}</p>
                    <h3>Total: Rs ${parseFloat(invoice.final_total).toFixed(2)}</h3>
                </div>
                <div class="signatures">
                    <div><hr style="width: 200px;"><p>Customer Signature</p></div>
                    <div><hr style="width: 200px;"><p>Cashier Signature</p></div>
                </div>
                <div class="footer">
                    <p>Thank you for shopping with us!</p>
                    <p>Developed by Vexel IT by Kavizz | 📞 94740890730</p>
                </div>
                <script>window.onload = function() { window.print(); setTimeout(function() { window.close(); }, 500); };<\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
}

// Redirect to billing
function redirectToBilling() {
    window.location.href = 'index1.php';
}

function escapeHtml(str) {
    if(!str) return '';
    return str.replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m]));
}

// Initial load
window.onload = () => {
    loadDashboardData();
    setInterval(loadDashboardData, 30000);
};
</script>

</body>
</html>