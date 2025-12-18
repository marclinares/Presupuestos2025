<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscador de Gastos 2025 - Ayuntamiento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Variables de diseño y utilidades personalizadas */
        :root {
            --primary-gradient: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            --shadow-soft: 0 8px 30px rgba(0, 0, 0, 0.08);
            --shadow-hard: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-primary {
            @apply px-5 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 text-white font-semibold hover:from-sky-600 hover:to-blue-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center gap-2;
        }
        
        .btn-secondary {
            @apply px-5 py-2.5 rounded-xl bg-gray-800 text-white font-medium hover:bg-gray-900 transition-all duration-300 shadow-sm hover:shadow-md flex items-center gap-2;
        }
        
        .btn-danger {
            @apply px-5 py-2.5 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 text-white font-medium hover:from-red-600 hover:to-rose-700 transition-all duration-300 shadow-sm hover:shadow-md flex items-center gap-2;
        }
        
        .input-std {
            @apply w-full border border-gray-200 rounded-xl px-4 py-3 bg-white/80 focus:ring-2 focus:ring-sky-300 focus:outline-none focus:border-sky-400 transition-all duration-300 placeholder:text-gray-400;
        }
        
        .th-sortable {
            @apply px-5 py-4 border-b border-gray-200 cursor-pointer select-none hover:bg-sky-50 transition-colors duration-200 text-left;
        }
        
        .card {
            @apply bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden;
        }
        
        .table-row-hover {
            @apply hover:bg-gradient-to-r hover:from-sky-50/80 hover:to-blue-50/50 transition-all duration-200;
        }
        
        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-sky-50 via-white to-blue-50 min-h-screen font-sans text-gray-800 p-4 md:p-8">

    <div class="max-w-7xl mx-auto">
        <!-- Header mejorado -->
        <header class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10 p-6 rounded-2xl glass-effect shadow-xl animate-fadeIn">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-sky-400 to-blue-500 rounded-xl blur opacity-30"></div>
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Ayuntamiento" class="relative h-16 w-auto rounded-xl">
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Ayuntamiento Almussafes</h2>
                    <p class="text-sm text-gray-600">Gestión Presupuestaria 2025</p>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <button id="btn-nuevo" class="btn-primary">
                    <i class="fas fa-plus-circle"></i>
                    Nuevo Presupuesto
                </button>
                <a id="btn-pdf" href="#" target="_blank" class="btn-secondary">
                    <i class="fas fa-file-pdf"></i>
                    Exportar PDF
                </a>
            </div>
        </header>

        <!-- Título principal -->
        <div class="text-center mb-12 animate-fadeIn" style="animation-delay: 0.1s">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                <span class="bg-gradient-to-r from-sky-600 to-blue-800 bg-clip-text text-transparent">
                    Buscador de Gastos Presupuestarios
                </span>
            </h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Visualiza, analiza y gestiona los gastos presupuestarios del año 2025 de forma interactiva
            </p>
        </div>

        <!-- Barra de búsqueda mejorada -->
        <div class="relative mb-12 max-w-3xl mx-auto animate-fadeIn" style="animation-delay: 0.2s">
            <div class="absolute left-5 top-1/2 transform -translate-y-1/2 text-sky-500 text-xl">
                <i class="fas fa-search"></i>
            </div>
            <input type="text" id="search" placeholder="Buscar por programa, clasificación económica, aplicación presupuestaria..."
                class="w-full pl-14 pr-5 py-4 text-lg border-0 rounded-2xl shadow-lg focus:shadow-xl focus:outline-none focus:ring-4 focus:ring-sky-200 transition-all duration-300 bg-white/90" />

        </div>

        <!-- Sección de gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
            <div class="card animate-fadeIn" style="animation-delay: 0.3s">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-chart-pie text-sky-500"></i>
                            Distribución por Programa
                        </h3>
                    </div>
                </div>
                <div class="p-4 h-72">
                    <canvas id="graficoPrograma"></canvas>
                </div>
            </div>
            
            <div class="card animate-fadeIn" style="animation-delay: 0.4s">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-blue-500"></i>
                            Distribución Económica
                        </h3>
                    </div>
                </div>
                <div class="p-4 h-72">
                    <canvas id="graficoEconomico"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabla de gastos -->
        <div class="card overflow-hidden mb-8 animate-fadeIn" style="animation-delay: 0.5s">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-table text-gray-600"></i>
                    Detalle de Gastos Presupuestarios
                </h3>
                <p class="text-gray-600 text-sm mt-1">
                    Haz clic en cualquier fila para editar los datos
                </p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm" id="tabla-gastos">
                    <thead class="bg-gradient-to-r from-sky-50 to-blue-50 text-gray-700 font-bold">
                        <tr>
                            <th data-campo="CODI_PROG" class="th-sortable">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-project-diagram text-sky-500"></i>
                                    Programa
                                    <span class="sort-icon"></span>
                                </div>
                            </th>
                            <th data-campo="CODI_ECON" class="th-sortable">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-coins text-amber-500"></i>
                                    Económico
                                    <span class="sort-icon"></span>
                                </div>
                            </th>
                            <th data-campo="APLICACION_PRESUPUESTARIA" class="th-sortable">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-file-invoice text-emerald-500"></i>
                                    Aplicación
                                    <span class="sort-icon"></span>
                                </div>
                            </th>
                            <th data-campo="CR_INIC_2024" class="th-sortable text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <i class="fas fa-euro-sign text-gray-500"></i>
                                    2024 (€)
                                    <span class="sort-icon"></span>
                                </div>
                            </th>
                            <th data-campo="CR_INIC_2025" class="th-sortable text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <i class="fas fa-euro-sign text-blue-500"></i>
                                    2025 (€)
                                    <span class="sort-icon"></span>
                                </div>
                            </th>
                            <th data-campo="VARIACION" class="th-sortable text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <i class="fas fa-chart-line"></i>
                                    Variación
                                    <span class="sort-icon"></span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body" class="divide-y divide-gray-100">
                        <!-- Los datos se cargarán aquí -->
                    </tbody>
                </table>
            </div>
            
            <!-- Estado de carga -->
            <div id="loading-state" class="p-12 text-center hidden">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-sky-500"></div>
                <p class="mt-4 text-gray-600">Cargando datos presupuestarios...</p>
            </div>
            
            <!-- Estado vacío -->
            <div id="empty-state" class="p-12 text-center hidden">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-600">No se encontraron registros con los filtros aplicados</p>
            </div>
        </div>

        <!-- Paginación mejorada -->
        <div id="paginacion" class="flex flex-wrap justify-center items-center gap-2 mb-12 animate-fadeIn" style="animation-delay: 0.6s">
            <!-- Los botones se generarán aquí -->
        </div>

        <!-- Footer informativo -->
        <footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-200 mt-8">
            <p>Sistema de Gestión Presupuestaria © 2025 • Última actualización: <span id="current-date"></span></p>
            <p class="mt-1">Total registros: <span id="total-records" class="font-semibold text-sky-600">0</span></p>
        </footer>
    </div>

    <!-- Modal mejorado -->
    <div id="modal" class="fixed inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center p-4 hidden z-50 transition-opacity duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden border border-gray-200 animate-fadeIn">
            <!-- Header del modal -->
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-sky-50 to-blue-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                            <i class="fas fa-edit text-sky-600"></i>
                            <span id="modal-title">Editar Gasto Presupuestario</span>
                        </h2>
                        <p class="text-gray-600 text-sm mt-1">Modifica los datos del gasto seleccionado</p>
                    </div>
                    <button id="btn-cerrar-x" class="text-gray-400 hover:text-gray-600 transition-colors duration-200 p-2 hover:bg-gray-100 rounded-full">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Contenido del modal -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
                <form id="form-editar" class="space-y-6">
                    <input type="hidden" id="gasto-id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-project-diagram text-sky-500"></i>
                                Programa
                            </label>
                            <input type="text" id="CODI_PROG" class="input-std border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-coins text-amber-500"></i>
                                Económico
                            </label>
                            <input type="text" id="CODI_ECON" class="input-std border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-file-alt text-emerald-500"></i>
                            Aplicación Presupuestaria
                        </label>
                            <textarea id="APLICACION_PRESUPUESTARIA" rows="3" class="w-full input-std resize-none border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-gray-500"></i>
                                Crédito 2024 (€)
                            </label>
                            <input type="number" step="0.01" id="CR_INIC_2024" class="input-std border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-blue-500"></i>
                                Crédito 2025 (€)
                            </label>
                            <input type="number" step="0.01" id="CR_INIC_2025" class="input-std border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-percentage text-purple-500"></i>
                            Variación (%)
                        </label>
                        <input type="text" id="VARIACION" class="input-std border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none">
                    </div>

                    <!-- Gráfico comparativo -->
                    <div class="mt-6 p-5 bg-gradient-to-r from-gray-50 to-white rounded-2xl border border-gray-200">
                        <div class="flex flex-wrap justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-chart-line text-sky-600"></i>
                                Comparativa Anual
                            </h3>
                            <div class="inline-flex rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                <button type="button" data-type="line" class="btn-graph-type px-4 py-2 text-sm font-medium border-r border-gray-200 bg-sky-500 text-white flex items-center gap-2">
                                    <i class="fas fa-chart-line"></i> Línea
                                </button>
                                <button type="button" data-type="bar" class="btn-graph-type px-4 py-2 text-sm font-medium bg-white text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                    <i class="fas fa-chart-bar"></i> Barras
                                </button>
                            </div>
                        </div>
                        <div class="h-48"><canvas id="graficoModal"></canvas></div>
                    </div>

                    <!-- Acciones del modal -->
                    <div class="flex flex-wrap justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                        <button type="button" id="btn-cerrar" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-all duration-300 flex items-center gap-2">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="button" id="btn-eliminar" class="btn-danger hidden">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        /**
         * ExpenseManager: Aplicación principal mejorada
         */
        const ExpenseManager = {
            // Estado de la aplicación
            state: {
                page: 1,
                search: '',
                sortCampo: '',
                sortDireccion: 'asc',
                chartModalType: 'line',
                debounceTimer: null,
                totalRecords: 0
            },
            
            // Referencias a gráficos
            charts: {
                mainPrograma: null,
                mainEconomico: null,
                modal: null
            },
            
            // Elementos DOM cacheados
            dom: {},

            // Inicialización
            init() {
                this.cacheDOM();
                this.bindEvents();
                this.setCurrentDate();
                this.loadData();
            },

            // Cachear elementos DOM
            cacheDOM() {
                this.dom = {
                    inputSearch: document.getElementById('search'),
                    tablaBody: document.getElementById('tabla-body'),
                    paginacion: document.getElementById('paginacion'),
                    modal: document.getElementById('modal'),
                    formEditar: document.getElementById('form-editar'),
                    btnNuevo: document.getElementById('btn-nuevo'),
                    btnPdf: document.getElementById('btn-pdf'),
                    btnCerrar: document.getElementById('btn-cerrar'),
                    btnCerrarX: document.getElementById('btn-cerrar-x'),
                    btnEliminar: document.getElementById('btn-eliminar'),
                    loadingState: document.getElementById('loading-state'),
                    emptyState: document.getElementById('empty-state'),
                    totalRecords: document.getElementById('total-records'),
                    modalTitle: document.getElementById('modal-title'),
                    inputsForm: {
                        id: document.getElementById('gasto-id'),
                        prog: document.getElementById('CODI_PROG'),
                        econ: document.getElementById('CODI_ECON'),
                        app: document.getElementById('APLICACION_PRESUPUESTARIA'),
                        c24: document.getElementById('CR_INIC_2024'),
                        c25: document.getElementById('CR_INIC_2025'),
                        var: document.getElementById('VARIACION'),
                    },
                    csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
            },

            // Vincular eventos
            bindEvents() {
                // Buscador con debounce mejorado
                this.dom.inputSearch.addEventListener('input', (e) => {
                    clearTimeout(this.state.debounceTimer);
                    this.dom.inputSearch.classList.add('ring-2', 'ring-sky-300');
                    
                    this.state.debounceTimer = setTimeout(() => {
                        this.state.search = e.target.value;
                        this.state.page = 1;
                        this.dom.inputSearch.classList.remove('ring-2', 'ring-sky-300');
                        this.loadData();
                    }, 400);
                });
                
                // Buscar con Enter
                this.dom.inputSearch.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        clearTimeout(this.state.debounceTimer);
                        this.state.search = e.target.value;
                        this.state.page = 1;
                        this.loadData();
                    }
                });

                // Ordenación de columnas
                document.querySelectorAll('th.th-sortable').forEach(th => {
                    th.addEventListener('click', () => this.handleSort(th));
                });

                // Paginación (delegación de eventos)
                this.dom.paginacion.addEventListener('click', (e) => {
                    if (e.target.tagName === 'BUTTON' && e.target.dataset.page) {
                        const page = parseInt(e.target.dataset.page);
                        if(page) {
                            this.state.page = page;
                            this.fetchGastos();
                        }
                    }
                });

                // Abrir modal al hacer clic en fila
                this.dom.tablaBody.addEventListener('click', (e) => {
                    const row = e.target.closest('tr[data-id]');
                    if (row) this.openModal(row);
                });

                // Botón nuevo gasto
                this.dom.btnNuevo.addEventListener('click', () => this.openModal(null));

                // Cerrar modal
                [this.dom.btnCerrar, this.dom.btnCerrarX].forEach(btn => {
                    btn.addEventListener('click', () => this.closeModal());
                });

                // Guardar formulario
                this.dom.formEditar.addEventListener('submit', (e) => this.handleSave(e));

                // Eliminar registro
                this.dom.btnEliminar.addEventListener('click', () => this.handleDelete());

                // Exportar PDF
                this.dom.btnPdf.addEventListener('click', (e) => this.handlePdfExport(e));

                // Cambiar tipo de gráfico en modal
                document.querySelectorAll('.btn-graph-type').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        this.state.chartModalType = e.target.dataset.type;
                        this.updateGraphTypeButtons();
                        this.renderModalChart();
                    });
                });
                
                // Cerrar modal al hacer clic fuera
                this.dom.modal.addEventListener('click', (e) => {
                    if (e.target === this.dom.modal) this.closeModal();
                });
            },

            // Cargar datos iniciales
            loadData() {
                this.showLoading();
                Promise.all([
                    this.fetchGastos(),
                    this.fetchChartsData()
                ]).finally(() => {
                    this.hideLoading();
                });
            },

            // Mostrar estado de carga
            showLoading() {
                this.dom.loadingState.classList.remove('hidden');
                this.dom.tablaBody.innerHTML = '';
                this.dom.emptyState.classList.add('hidden');
            },

            // Ocultar estado de carga
            hideLoading() {
                this.dom.loadingState.classList.add('hidden');
            },

            // Obtener gastos
            async fetchGastos() {
                const { search, page, sortCampo, sortDireccion } = this.state;
                const url = `/gastos/search?q=${encodeURIComponent(search)}&page=${page}&sort=${sortCampo}&dir=${sortDireccion}`;
                
                try {
                    const res = await fetch(url);
                    const json = await res.json();
                    
                    this.state.totalRecords = json.total || json.data.length;
                    this.dom.totalRecords.textContent = this.formatNumber(this.state.totalRecords);
                    
                    if (json.data.length === 0) {
                        this.dom.emptyState.classList.remove('hidden');
                        this.dom.tablaBody.innerHTML = '';
                    } else {
                        this.dom.emptyState.classList.add('hidden');
                        this.renderTable(json.data);
                    }
                    
                    this.renderPagination(json.current_page, json.last_page);
                    this.updatePdfLink();
                } catch (error) {
                    console.error("Error cargando gastos:", error);
                    this.dom.tablaBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-red-600">
                                <i class="fas fa-exclamation-triangle text-xl mb-2"></i>
                                <p>Error al cargar los datos. Intenta nuevamente.</p>
                            </td>
                        </tr>
                    `;
                }
            },
        

            // Renderizar tabla
            renderTable(data) {
                this.dom.tablaBody.innerHTML = data.map(g => {
                    const varClass = g.VARIACION && parseFloat(g.VARIACION) < 0 ? 'text-red-600 bg-red-50' : 'text-emerald-600 bg-emerald-50';
                    const varIcon = g.VARIACION && parseFloat(g.VARIACION) < 0 ? 'fa-arrow-down' : 'fa-arrow-up';
                    const varText = g.VARIACION ? `${parseFloat(g.VARIACION) > 0 ? '+' : ''}${g.VARIACION}%` : '—';
                    
                    return `
                        <tr data-id="${g.id}" class="table-row-hover group">
                            <td class="px-5 py-4 font-semibold text-gray-900 group-hover:text-sky-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-sky-500"></div>
                                    ${g.CODI_PROG}
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-700">${g.CODI_ECON}</td>
                            <td class="px-5 py-4 text-gray-600 max-w-xs truncate" title="${g.APLICACION_PRESUPUESTARIA}">
                                ${g.APLICACION_PRESUPUESTARIA}
                            </td>
                            <td class="px-5 py-4 text-right font-mono text-gray-500">${this.formatMoney(g.CR_INIC_2024)}</td>
                            <td class="px-5 py-4 text-right font-mono font-bold text-gray-900">${this.formatMoney(g.CR_INIC_2025)}</td>
                            <td class="px-5 py-4 text-right">
                                <span class="px-3 py-1.5 rounded-full text-sm font-semibold ${varClass}">
                                    <i class="fas ${varIcon} mr-1"></i>${varText}
                                </span>
                            </td>
                        </tr>
                    `;
                }).join('');
            },

            // Renderizar paginación
            renderPagination(current, last) {
                if (last <= 1) {
                    this.dom.paginacion.innerHTML = '';
                    return;
                }
                
                let html = '';
                
                // Botón anterior
                if (current > 1) {
                    html += `
                        <button data-page="${current - 1}" class="px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-all duration-300 flex items-center gap-2">
                            <i class="fas fa-chevron-left"></i> Anterior
                        </button>
                    `;
                }
                
                // Números de página
                const visiblePages = this.getVisiblePages(current, last);
                visiblePages.forEach(page => {
                    if (page === '...') {
                        html += `<span class="px-3 py-2 text-gray-400">...</span>`;
                    } else {
                        const activeClass = page === current ? 
                            'bg-gradient-to-r from-sky-500 to-blue-600 text-white border-transparent shadow-md' : 
                            'bg-white text-gray-700 border-gray-300 hover:bg-gray-50';
                        
                        html += `
                            <button data-page="${page}" class="px-4 py-2.5 rounded-lg border transition-all duration-300 ${activeClass}">
                                ${page}
                            </button>
                        `;
                    }
                });
                
                // Botón siguiente
                if (current < last) {
                    html += `
                        <button data-page="${current + 1}" class="px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-all duration-300 flex items-center gap-2">
                            Siguiente <i class="fas fa-chevron-right"></i>
                        </button>
                    `;
                }
                
                this.dom.paginacion.innerHTML = html;
            },

            // Obtener páginas visibles para paginación
            getVisiblePages(current, last) {
                const delta = 2;
                const range = [];
                const rangeWithDots = [];
                let l;

                for (let i = 1; i <= last; i++) {
                    if (i === 1 || i === last || (i >= current - delta && i <= current + delta)) {
                        range.push(i);
                    }
                }

                range.forEach(i => {
                    if (l) {
                        if (i - l === 2) {
                            rangeWithDots.push(l + 1);
                        } else if (i - l !== 1) {
                            rangeWithDots.push('...');
                        }
                    }
                    rangeWithDots.push(i);
                    l = i;
                });

                return rangeWithDots;
            },

            // Manejar ordenación
            handleSort(th) {
                const campo = th.dataset.campo;
                
                if (this.state.sortCampo === campo) {
                    this.state.sortDireccion = this.state.sortDireccion === 'asc' ? 'desc' : 'asc';
                } else {
                    this.state.sortCampo = campo;
                    this.state.sortDireccion = 'asc';
                }

                // Actualizar iconos de ordenación
                document.querySelectorAll('th.sortable .sort-icon').forEach(s => {
                    s.innerHTML = '';
                });
                
                const icon = th.querySelector('.sort-icon');
                icon.innerHTML = this.state.sortDireccion === 'asc' ? 
                    '<i class="fas fa-arrow-up text-sky-500 ml-1"></i>' : 
                    '<i class="fas fa-arrow-down text-sky-500 ml-1"></i>';

                this.loadData();
            },

            // --- Funcionalidad del Modal ---

            // Abrir modal
            openModal(row) {
                const f = this.dom.inputsForm;
                
                if (row) {
                    // Modo edición
                    const cells = row.children;
                    f.id.value = row.dataset.id;
                    f.prog.value = cells[0].textContent.trim().replace('●', '').trim();
                    f.econ.value = cells[1].textContent.trim();
                    f.app.value = cells[2].getAttribute('title') || cells[2].textContent.trim();
                    f.c24.value = this.parseMoney(cells[3].textContent);
                    f.c25.value = this.parseMoney(cells[4].textContent);
                    
                    const varText = cells[5].querySelector('span').textContent;
                    f.var.value = varText.replace(/[+%↑↓]/g, '').replace('—', '').trim();

                    this.dom.btnEliminar.classList.remove('hidden');
                    this.dom.modalTitle.textContent = 'Editar Gasto Presupuestario';
                } else {
                    // Modo creación
                    f.id.value = '';
                    this.dom.formEditar.reset();
                    this.dom.btnEliminar.classList.add('hidden');
                    this.dom.modalTitle.textContent = 'Nuevo Gasto Presupuestario';
                }

                this.renderModalChart();
                this.toggleModal(true);
            },

            // Cerrar modal
            closeModal() {
                this.toggleModal(false);
            },

            // Mostrar/ocultar modal
            toggleModal(show) {
                if (show) {
                    this.dom.modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                } else {
                    this.dom.modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            },

            // Manejar guardado
            async handleSave(e) {
                e.preventDefault();
                const f = this.dom.inputsForm;
                const id = f.id.value;
                const method = id ? 'PUT' : 'POST';
                const url = id ? `/gastos/${id}` : '/gastos';

                // Validación básica
                if (!f.prog.value.trim() || !f.econ.value.trim()) {
                    this.showNotification('Los campos Programa y Económico son obligatorios', 'error');
                    return;
                }

                const data = {
                    CODI_PROG: f.prog.value.trim(),
                    CODI_ECON: f.econ.value.trim(),
                    APLICACION_PRESUPUESTARIA: f.app.value.trim(),
                    CR_INIC_2024: parseFloat(f.c24.value) || 0,
                    CR_INIC_2025: parseFloat(f.c25.value) || 0,
                    VARIACION: f.var.value.trim()
                };

                try {
                    const res = await fetch(url, {
                        method,
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': this.dom.csrf 
                        },
                        body: JSON.stringify(data)
                    });

                    if (res.ok) {
                        this.closeModal();
                        this.showNotification(
                            id ? 'Registro actualizado correctamente' : 'Registro creado correctamente',
                            'success'
                        );
                        this.loadData();
                    } else {
                        throw new Error('Error en la respuesta del servidor');
                    }
                } catch (error) {
                    console.error('Error al guardar:', error);
                    this.showNotification('❌ Error al guardar los datos', 'error');
                }
            },

            // Manejar eliminación
            async handleDelete() {
                const id = this.dom.inputsForm.id.value;
                if (!id) return;

                if (!confirm('¿Estás seguro de que quieres eliminar este registro? Esta acción no se puede deshacer.')) {
                    return;
                }

                try {
                    const res = await fetch(`/gastos/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': this.dom.csrf }
                    });

                    if (res.ok) {
                        this.closeModal();
                        this.showNotification('Registro eliminado correctamente', 'success');
                        this.loadData();
                    } else {
                        throw new Error('Error al eliminar');
                    }
                } catch (error) {
                    console.error('Error al eliminar:', error);
                    this.showNotification('❌ Error al eliminar el registro', 'error');
                }
            },

            // --- Funcionalidad de Gráficos ---

            // Obtener datos para gráficos principales (restaurado del código original)
            async fetchChartsData() {
                const params = `q=${encodeURIComponent(this.state.search)}&sort=${this.state.sortCampo}&dir=${this.state.sortDireccion}`;
                
                try {
                    const [progRes, econRes] = await Promise.all([
                        fetch(`/gastos/chart-data/programa?${params}`),
                        fetch(`/gastos/chart-data/economico?${params}`)
                    ]);

                    const progData = await progRes.json();
                    const econData = await econRes.json();

                    this.renderMainChart('graficoPrograma', progData, 'codigo_programa', 'mainPrograma');
                    this.renderMainChart('graficoEconomico', econData, 'codigo_economico', 'mainEconomico');
                } catch (error) {
                    console.error("Error cargando gráficos:", error);
                    // Si hay error, mostrar gráficos vacíos o con datos de ejemplo
                    this.renderEmptyCharts();
                }
            },

            // Renderizar gráficos vacíos o con datos de ejemplo
            renderEmptyCharts() {
                const emptyData = [{ codigo: 'Sin datos', total: 1 }];
                this.renderMainChart('graficoPrograma', emptyData, 'codigo', 'mainPrograma');
                this.renderMainChart('graficoEconomico', emptyData, 'codigo', 'mainEconomico');
            },

            // Renderizar gráfico principal (restaurado del código original)
            renderMainChart(canvasId, data, labelKey, chartKey) {
                const ctx = document.getElementById(canvasId);
                if (!ctx) return;
                
                // Preparar datos para el gráfico
                const labels = data.map(d => d[labelKey] || 'Sin etiqueta');
                const values = data.map(d => parseFloat(d.total) || 0);
                
                // Destruir gráfico anterior si existe
                if (this.charts[chartKey]) {
                    this.charts[chartKey].destroy();
                }

                // Colores para los gráficos
                const isPrograma = chartKey === 'mainPrograma';
                const backgroundColors = isPrograma ? [
                    '#0ea5e9', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe',
                    '#7dd3fc', '#38bdf8', '#0284c7', '#0369a1', '#0c4a6e'
                ] : [
                    '#f59e0b', '#fbbf24', '#fcd34d', '#fde68a', '#fef3c7',
                    '#d97706', '#b45309', '#92400e', '#78350f', '#451a03'
                ];

                this.charts[chartKey] = new Chart(ctx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: backgroundColors.slice(0, Math.min(values.length, backgroundColors.length)),
                            borderColor: '#ffffff',
                            borderWidth: 2,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: { 
                                position: 'right',
                                labels: { 
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: { size: 11 }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) => {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = values.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${label}: ${this.formatMoney(value)} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            },

            // Renderizar gráfico del modal
            renderModalChart() {
                const ctx = document.getElementById('graficoModal');
                if (!ctx) return;
                
                const context = ctx.getContext('2d');
                const v24 = parseFloat(this.dom.inputsForm.c24.value) || 0;
                const v25 = parseFloat(this.dom.inputsForm.c25.value) || 0;
                const type = this.state.chartModalType;

                // Destruir gráfico anterior si existe
                if (this.charts.modal) {
                    this.charts.modal.destroy();
                }

                const isIncrease = v25 >= v24;
                const color = isIncrease ? '#10b981' : '#ef4444';
                
                // Crear gradiente para el gráfico
                let gradient;
                if (type === 'bar') {
                    gradient = context.createLinearGradient(0, 0, 0, 200);
                    gradient.addColorStop(0, isIncrease ? 'rgba(16, 185, 129, 0.8)' : 'rgba(239, 68, 68, 0.8)');
                    gradient.addColorStop(1, isIncrease ? 'rgba(16, 185, 129, 0.3)' : 'rgba(239, 68, 68, 0.3)');
                } else {
                    gradient = context.createLinearGradient(0, 0, 0, 200);
                    gradient.addColorStop(0, isIncrease ? 'rgba(16, 185, 129, 0.6)' : 'rgba(239, 68, 68, 0.6)');
                    gradient.addColorStop(1, isIncrease ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)');
                }

                this.charts.modal = new Chart(context, {
                    type: type,
                    data: {
                        labels: ['2024', '2025'],
                        datasets: [{
                            label: 'Presupuesto (€)',
                            data: [v24, v25],
                            backgroundColor: type === 'bar' ? [
                                'rgba(148, 163, 184, 0.5)',
                                gradient
                            ] : gradient,
                            borderColor: color,
                            borderWidth: type === 'line' ? 3 : 0,
                            fill: type === 'line',
                            tension: 0.4,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: color
                        }]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (context) => {
                                        return `${context.dataset.label}: ${this.formatMoney(context.raw)}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { 
                                    color: 'rgba(0,0,0,0.05)',
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    callback: (value) => this.formatMoney(value)
                                }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            },

            // Actualizar botones de tipo de gráfico
            updateGraphTypeButtons() {
                document.querySelectorAll('.btn-graph-type').forEach(btn => {
                    const isActive = btn.dataset.type === this.state.chartModalType;
                    if (isActive) {
                        btn.classList.remove('bg-white', 'text-gray-700', 'hover:bg-gray-50');
                        btn.classList.add('bg-sky-500', 'text-white');
                    } else {
                        btn.classList.remove('bg-sky-500', 'text-white');
                        btn.classList.add('bg-white', 'text-gray-700', 'hover:bg-gray-50');
                    }
                });
            },

            // --- Funciones auxiliares ---

            // Actualizar enlace PDF
            updatePdfLink() {
                const { search, sortCampo, sortDireccion } = this.state;
                this.dom.btnPdf.href = `/gastos/pdf?sort=${sortCampo}&dir=${sortDireccion}&q=${encodeURIComponent(search)}`;
            },

            // Manejar exportación PDF
            handlePdfExport(e) {
                e.preventDefault();
                
                if (this.state.totalRecords === 0) {
                    this.showNotification('No hay datos para exportar', 'warning');
                    return;
                }
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/gastos/pdf';
                form.target = '_blank';

                const payload = {
                    _token: this.dom.csrf,
                    q: this.state.search,
                    sort: this.state.sortCampo,
                    dir: this.state.sortDireccion
                };

                // Agregar imágenes de gráficos solo si existen
                try {
                    const programaCanvas = document.getElementById('graficoPrograma');
                    const economicoCanvas = document.getElementById('graficoEconomico');
                    
                    if (programaCanvas) {
                        const programaImg = document.createElement('input');
                        programaImg.type = 'hidden';
                        programaImg.name = 'grafico_programa';
                        programaImg.value = programaCanvas.toDataURL('image/png', 1.0);
                        form.appendChild(programaImg);
                    }
                    
                    if (economicoCanvas) {
                        const economicoImg = document.createElement('input');
                        economicoImg.type = 'hidden';
                        economicoImg.name = 'grafico_economico';
                        economicoImg.value = economicoCanvas.toDataURL('image/png', 1.0);
                        form.appendChild(economicoImg);
                    }
                } catch (error) {
                    console.error('Error al obtener imágenes de gráficos:', error);
                }

                Object.entries(payload).forEach(([key, val]) => {
                    const inp = document.createElement('input');
                    inp.type = 'hidden';
                    inp.name = key;
                    inp.value = val;
                    form.appendChild(inp);
                });

                document.body.appendChild(form);
                this.showNotification('🔄 Generando PDF...', 'info');
                form.submit();
                document.body.removeChild(form);
            },

            // Mostrar notificación
            showNotification(message, type = 'info') {
                // Crear notificación si no existe
                let notification = document.getElementById('expense-notification');
                
                if (!notification) {
                    notification = document.createElement('div');
                    notification.id = 'expense-notification';
                    notification.className = 'fixed top-6 right-6 z-50 max-w-sm';
                    document.body.appendChild(notification);
                }
                
                const typeClasses = {
                    success: 'bg-emerald-50 border-emerald-200 text-emerald-800',
                    error: 'bg-red-50 border-red-200 text-red-800',
                    warning: 'bg-amber-50 border-amber-200 text-amber-800',
                    info: 'bg-sky-50 border-sky-200 text-sky-800'
                };
                
                const iconClasses = {
                    success: 'fa-check-circle text-emerald-500',
                    error: 'fa-exclamation-circle text-red-500',
                    warning: 'fa-exclamation-triangle text-amber-500',
                    info: 'fa-info-circle text-sky-500'
                };
                
                const notificationId = Date.now();
                const notificationHtml = `
                    <div id="notification-${notificationId}" class="rounded-xl border p-4 shadow-lg mb-3 transition-all duration-300 transform translate-x-0 opacity-100 ${typeClasses[type]}">
                        <div class="flex items-center gap-3">
                            <i class="fas ${iconClasses[type]} text-xl"></i>
                            <div class="flex-1">${message}</div>
                            <button onclick="document.getElementById('notification-${notificationId}').remove()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                
                notification.insertAdjacentHTML('afterbegin', notificationHtml);
                
                // Auto-eliminar después de 5 segundos
                setTimeout(() => {
                    const notifElement = document.getElementById(`notification-${notificationId}`);
                    if (notifElement) {
                        notifElement.style.transform = 'translateX(100%)';
                        notifElement.style.opacity = '0';
                        setTimeout(() => notifElement.remove(), 300);
                    }
                }, 5000);
            },

            // Establecer fecha actual
            setCurrentDate() {
                const now = new Date();
                const options = { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                document.getElementById('current-date').textContent = 
                    now.toLocaleDateString('es-ES', options);
            },

            // Formatear moneda
            formatMoney(amount) {
                const num = parseFloat(amount);
                if (isNaN(num)) return '0,00 €';
                
                return new Intl.NumberFormat('es-ES', {
                    style: 'currency',
                    currency: 'EUR',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(num);
            },

            // Formatear número
            formatNumber(num) {
                return new Intl.NumberFormat('es-ES').format(num);
            },

            // Parsear moneda
            parseMoney(str) {
                return str.replace(/\./g, '').replace(',', '.').replace(/[^\d.-]/g, '');
            }
        };

        // Inicializar aplicación cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', () => {
            ExpenseManager.init();
        });
    </script>
</body>
</html>