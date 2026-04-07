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
            @apply hover:bg-gradient-to-r hover:from-sky-50/80 hover:to-blue-50/50 transition-all duration-200 cursor-pointer;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeIn { animation: fadeIn 0.3s ease-out; }

        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Modal de confirmación personalizado */
        #confirm-modal {
            transition: opacity 0.2s ease;
        }

        /* Fila de tabla accesible */
        tr[data-id]:focus {
            outline: 2px solid #0ea5e9;
            outline-offset: -2px;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-sky-50 via-white to-blue-50 min-h-screen font-sans text-gray-800 p-4 md:p-8">

<div class="max-w-7xl mx-auto">

    <!-- Header -->
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
                <i class="fas fa-plus-circle"></i> Nuevo Presupuesto
            </button>
            <button id="btn-pdf" class="btn-secondary">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </button>
        </div>
    </header>

    <!-- Título -->
    <div class="text-center mb-12 animate-fadeIn" style="animation-delay:0.1s">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
            <span class="bg-gradient-to-r from-sky-600 to-blue-800 bg-clip-text text-transparent">
                Buscador de Gastos Presupuestarios
            </span>
        </h1>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto">
            Visualiza, analiza y gestiona los gastos presupuestarios del año 2025 de forma interactiva
        </p>
    </div>

    <!-- Búsqueda -->
    <div class="relative mb-12 max-w-3xl mx-auto animate-fadeIn" style="animation-delay:0.2s">
        <div class="absolute left-5 top-1/2 transform -translate-y-1/2 text-sky-500 text-xl">
            <i class="fas fa-search"></i>
        </div>
        <input
            type="text"
            id="search"
            placeholder="Buscar por programa, clasificación económica, aplicación presupuestaria..."
            class="w-full pl-14 pr-5 py-4 text-lg border-0 rounded-2xl shadow-lg focus:shadow-xl focus:outline-none focus:ring-4 focus:ring-sky-200 transition-all duration-300 bg-white/90"
            aria-label="Buscar gastos"
        />
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <div class="card animate-fadeIn" style="animation-delay:0.3s">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-sky-500"></i>
                    Distribución por Programa
                </h3>
            </div>
            <div class="p-4 h-72">
                <canvas id="graficoPrograma"></canvas>
            </div>
        </div>
        <div class="card animate-fadeIn" style="animation-delay:0.4s">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-blue-500"></i>
                    Distribución Económica
                </h3>
            </div>
            <div class="p-4 h-72">
                <canvas id="graficoEconomico"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card overflow-hidden mb-8 animate-fadeIn" style="animation-delay:0.5s">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-table text-gray-600"></i>
                Detalle de Gastos Presupuestarios
            </h3>
            <p class="text-gray-600 text-sm mt-1">Haz clic en cualquier fila para editar los datos</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm" id="tabla-gastos">
                <thead class="bg-gradient-to-r from-sky-50 to-blue-50 text-gray-700 font-bold">
                    <tr>
                        <th data-campo="CODI_PROG" class="th-sortable" scope="col">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-project-diagram text-sky-500"></i>
                                Programa <span class="sort-icon"></span>
                            </div>
                        </th>
                        <th data-campo="CODI_ECON" class="th-sortable" scope="col">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-coins text-amber-500"></i>
                                Económico <span class="sort-icon"></span>
                            </div>
                        </th>
                        <th data-campo="APLICACION_PRESUPUESTARIA" class="th-sortable" scope="col">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-file-invoice text-emerald-500"></i>
                                Aplicación <span class="sort-icon"></span>
                            </div>
                        </th>
                        <th data-campo="CR_INIC_2024" class="th-sortable text-right" scope="col">
                            <div class="flex items-center justify-end gap-2">
                                <i class="fas fa-euro-sign text-gray-500"></i>
                                2024 (€) <span class="sort-icon"></span>
                            </div>
                        </th>
                        <th data-campo="CR_INIC_2025" class="th-sortable text-right" scope="col">
                            <div class="flex items-center justify-end gap-2">
                                <i class="fas fa-euro-sign text-blue-500"></i>
                                2025 (€) <span class="sort-icon"></span>
                            </div>
                        </th>
                        <th data-campo="VARIACION" class="th-sortable text-right" scope="col">
                            <div class="flex items-center justify-end gap-2">
                                <i class="fas fa-chart-line"></i>
                                Variación <span class="sort-icon"></span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody id="tabla-body" class="divide-y divide-gray-100">
                </tbody>
            </table>
        </div>

        <div id="loading-state" class="p-12 text-center hidden">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-sky-500"></div>
            <p class="mt-4 text-gray-600">Cargando datos presupuestarios...</p>
        </div>

        <div id="empty-state" class="p-12 text-center hidden">
            <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-600">No se encontraron registros con los filtros aplicados</p>
        </div>
    </div>

    <!-- Paginación -->
    <div id="paginacion" class="flex flex-wrap justify-center items-center gap-2 mb-12 animate-fadeIn" style="animation-delay:0.6s"></div>

    <!-- Footer -->
    <footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-200 mt-8">
        <p>Sistema de Gestión Presupuestaria © 2025 • Última actualización: <span id="current-date"></span></p>
        <p class="mt-1">Total registros: <span id="total-records" class="font-semibold text-sky-600">0</span></p>
    </footer>
</div>

<!-- Modal edición -->
<div id="modal" class="fixed inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden border border-gray-200 animate-fadeIn">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-sky-50 to-blue-50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                        <i class="fas fa-edit text-sky-600"></i>
                        <span id="modal-title">Editar Gasto Presupuestario</span>
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Modifica los datos del gasto seleccionado</p>
                </div>
                <button id="btn-cerrar-x" class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-full" aria-label="Cerrar modal">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
            <form id="form-editar" class="space-y-6" novalidate>
                <input type="hidden" id="gasto-id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="CODI_PROG" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-project-diagram text-sky-500 mr-1"></i> Programa *
                        </label>
                        <input type="text" id="CODI_PROG" class="input-std" required aria-required="true">
                        <p class="text-red-500 text-xs mt-1 hidden" id="err-prog">Campo obligatorio</p>
                    </div>
                    <div>
                        <label for="CODI_ECON" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-coins text-amber-500 mr-1"></i> Económico *
                        </label>
                        <input type="text" id="CODI_ECON" class="input-std" required aria-required="true">
                        <p class="text-red-500 text-xs mt-1 hidden" id="err-econ">Campo obligatorio</p>
                    </div>
                </div>

                <div>
                    <label for="APLICACION_PRESUPUESTARIA" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-file-alt text-emerald-500 mr-1"></i> Aplicación Presupuestaria
                    </label>
                    <textarea id="APLICACION_PRESUPUESTARIA" rows="3" class="w-full input-std resize-none"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="CR_INIC_2024" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-gray-500 mr-1"></i> Crédito 2024 (€)
                        </label>
                        <input type="number" step="0.01" min="0" id="CR_INIC_2024" class="input-std">
                        <p class="text-red-500 text-xs mt-1 hidden" id="err-c24">Debe ser un número positivo</p>
                    </div>
                    <div>
                        <label for="CR_INIC_2025" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> Crédito 2025 (€)
                        </label>
                        <input type="number" step="0.01" min="0" id="CR_INIC_2025" class="input-std">
                        <p class="text-red-500 text-xs mt-1 hidden" id="err-c25">Debe ser un número positivo</p>
                    </div>
                </div>

                <div>
                    <label for="VARIACION" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-percentage text-purple-500 mr-1"></i> Variación (%)
                        <span class="text-xs text-gray-400 font-normal ml-1">(se calcula automáticamente)</span>
                    </label>
                    <input type="text" id="VARIACION" class="input-std bg-gray-50" readonly
                           aria-label="Variación calculada automáticamente">
                </div>

                <!-- Gráfico comparativo -->
                <div class="mt-6 p-5 bg-gradient-to-r from-gray-50 to-white rounded-2xl border border-gray-200">
                    <div class="flex flex-wrap justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-chart-line text-sky-600"></i> Comparativa Anual
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

<!-- Modal de confirmación personalizado (reemplaza confirm()) -->
<div id="confirm-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden z-[60]">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 animate-fadeIn">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Confirmar eliminación</h3>
        </div>
        <p class="text-gray-600 mb-6">¿Estás seguro de que quieres eliminar este registro? Esta acción no se puede deshacer.</p>
        <div class="flex justify-end gap-3">
            <button id="confirm-cancel" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all font-medium">
                Cancelar
            </button>
            <button id="confirm-ok" class="px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700 transition-all font-medium flex items-center gap-2">
                <i class="fas fa-trash-alt"></i> Eliminar
            </button>
        </div>
    </div>
</div>

<script>
    /**
     * CORRECCIONES APLICADAS:
     * 1. [SEGURIDAD]     renderTable() — eliminado innerHTML con datos del servidor. Se usa createElement + textContent.
     * 2. [BUG]           handleSort()  — selector corregido de 'th.sortable' a 'th.th-sortable'.
     * 3. [LÓGICA]        openModal()   — lee datos desde atributos data-* en lugar de parsear el DOM visual.
     * 4. [LÓGICA]        VARIACION     — se recalcula automáticamente al cambiar CR_INIC_2024 o CR_INIC_2025.
     * 5. [UX]            handleDelete()— reemplazado confirm() nativo por modal de confirmación personalizado.
     * 6. [RENDIMIENTO]   paginación    — no recarga gráficos al paginar, solo al buscar/ordenar.
     * 7. [VALIDACIÓN]    handleSave()  — validación extendida (tipos, rangos negativos, mensajes de error visibles).
     * 8. [ACCESIBILIDAD] Filas de tabla — añadido tabindex="0" y soporte teclado (Enter/Space).
     * 9. [HTML]          btn-pdf — cambiado de <a> a <button> para evitar href="#" y manejar el export por JS.
     * 10.[HTML]          Etiquetas <label for="..."> vinculadas correctamente a sus inputs.
     */

    const ExpenseManager = {
        state: {
            page: 1,
            search: '',
            sortCampo: '',
            sortDireccion: 'asc',
            chartModalType: 'line',
            debounceTimer: null,
            totalRecords: 0
        },

        charts: {
            mainPrograma: null,
            mainEconomico: null,
            modal: null
        },

        dom: {},

        init() {
            this.cacheDOM();
            this.bindEvents();
            this.setCurrentDate();
            this.loadData();
        },

        cacheDOM() {
            this.dom = {
                inputSearch:  document.getElementById('search'),
                tablaBody:    document.getElementById('tabla-body'),
                paginacion:   document.getElementById('paginacion'),
                modal:        document.getElementById('modal'),
                confirmModal: document.getElementById('confirm-modal'),
                formEditar:   document.getElementById('form-editar'),
                btnNuevo:     document.getElementById('btn-nuevo'),
                btnPdf:       document.getElementById('btn-pdf'),
                btnCerrar:    document.getElementById('btn-cerrar'),
                btnCerrarX:   document.getElementById('btn-cerrar-x'),
                btnEliminar:  document.getElementById('btn-eliminar'),
                confirmOk:    document.getElementById('confirm-ok'),
                confirmCancel:document.getElementById('confirm-cancel'),
                loadingState: document.getElementById('loading-state'),
                emptyState:   document.getElementById('empty-state'),
                totalRecords: document.getElementById('total-records'),
                modalTitle:   document.getElementById('modal-title'),
                inputsForm: {
                    id:   document.getElementById('gasto-id'),
                    prog: document.getElementById('CODI_PROG'),
                    econ: document.getElementById('CODI_ECON'),
                    app:  document.getElementById('APLICACION_PRESUPUESTARIA'),
                    c24:  document.getElementById('CR_INIC_2024'),
                    c25:  document.getElementById('CR_INIC_2025'),
                    var:  document.getElementById('VARIACION'),
                },
                errors: {
                    prog: document.getElementById('err-prog'),
                    econ: document.getElementById('err-econ'),
                    c24:  document.getElementById('err-c24'),
                    c25:  document.getElementById('err-c25'),
                },
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
        },

        bindEvents() {
            // Buscador con debounce
            this.dom.inputSearch.addEventListener('input', (e) => {
                clearTimeout(this.state.debounceTimer);
                this.dom.inputSearch.classList.add('ring-2', 'ring-sky-300');

                this.state.debounceTimer = setTimeout(() => {
                    this.state.search = e.target.value;
                    this.state.page = 1;
                    this.dom.inputSearch.classList.remove('ring-2', 'ring-sky-300');
                    this.loadData(); // recarga tabla + gráficos al buscar
                }, 400);
            });

            this.dom.inputSearch.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    clearTimeout(this.state.debounceTimer);
                    this.state.search = e.target.value;
                    this.state.page = 1;
                    this.loadData();
                }
            });

            // Ordenación
            document.querySelectorAll('th.th-sortable').forEach(th => {
                th.addEventListener('click', () => this.handleSort(th));
            });

            // Paginación (delegación de eventos) — solo recarga tabla, no gráficos
            this.dom.paginacion.addEventListener('click', (e) => {
                const btn = e.target.closest('button[data-page]');
                if (btn) {
                    const page = parseInt(btn.dataset.page);
                    if (page) {
                        this.state.page = page;
                        this.fetchGastos(); // ← solo tabla, no gráficos
                    }
                }
            });

            // Click en fila de tabla
            this.dom.tablaBody.addEventListener('click', (e) => {
                const row = e.target.closest('tr[data-id]');
                if (row) this.openModal(row);
            });

            // Teclado en filas de tabla (accesibilidad)
            this.dom.tablaBody.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    const row = e.target.closest('tr[data-id]');
                    if (row) {
                        e.preventDefault();
                        this.openModal(row);
                    }
                }
            });

            // Nuevo gasto
            this.dom.btnNuevo.addEventListener('click', () => this.openModal(null));

            // Cerrar modal principal
            [this.dom.btnCerrar, this.dom.btnCerrarX].forEach(btn => {
                btn.addEventListener('click', () => this.closeModal());
            });

            this.dom.modal.addEventListener('click', (e) => {
                if (e.target === this.dom.modal) this.closeModal();
            });

            // Guardar
            this.dom.formEditar.addEventListener('submit', (e) => this.handleSave(e));

            // Eliminar — abre el modal de confirmación personalizado
            this.dom.btnEliminar.addEventListener('click', () => this.showConfirmModal());

            // Botones del modal de confirmación
            this.dom.confirmOk.addEventListener('click', () => {
                this.dom.confirmModal.classList.add('hidden');
                this.handleDelete();
            });
            this.dom.confirmCancel.addEventListener('click', () => {
                this.dom.confirmModal.classList.add('hidden');
            });

            // Exportar PDF
            this.dom.btnPdf.addEventListener('click', () => this.handlePdfExport());

            // Cambiar tipo de gráfico modal
            document.querySelectorAll('.btn-graph-type').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const target = e.target.closest('[data-type]');
                    if (target) {
                        this.state.chartModalType = target.dataset.type;
                        this.updateGraphTypeButtons();
                        this.renderModalChart();
                    }
                });
            });

            // CORRECCIÓN: Recalcular VARIACION automáticamente al cambiar créditos
            [this.dom.inputsForm.c24, this.dom.inputsForm.c25].forEach(input => {
                input.addEventListener('input', () => {
                    this.recalcVariacion();
                    this.renderModalChart();
                });
            });
        },

        // Recalcula la variación a partir de los créditos introducidos
        recalcVariacion() {
            const v24 = parseFloat(this.dom.inputsForm.c24.value) || 0;
            const v25 = parseFloat(this.dom.inputsForm.c25.value) || 0;
            if (v24 === 0) {
                this.dom.inputsForm.var.value = '';
                return;
            }
            const variacion = (((v25 - v24) / v24) * 100).toFixed(2);
            this.dom.inputsForm.var.value = variacion;
        },

        // Carga inicial: tabla + gráficos
        loadData() {
            this.showLoading();
            Promise.all([
                this.fetchGastos(),
                this.fetchChartsData()
            ]).finally(() => {
                this.hideLoading();
            });
        },

        showLoading() {
            this.dom.loadingState.classList.remove('hidden');
            this.dom.tablaBody.innerHTML = '';
            this.dom.emptyState.classList.add('hidden');
        },

        hideLoading() {
            this.dom.loadingState.classList.add('hidden');
        },

        async fetchGastos() {
            const { search, page, sortCampo, sortDireccion } = this.state;
            const url = `/gastos/search?q=${encodeURIComponent(search)}&page=${page}&sort=${sortCampo}&dir=${sortDireccion}`;

            try {
                const res = await fetch(url);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const json = await res.json();

                this.state.totalRecords = json.total ?? json.data.length;
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
                console.error('Error cargando gastos:', error);
                this.dom.tablaBody.innerHTML = '';
                const tr = document.createElement('tr');
                const td = document.createElement('td');
                td.colSpan = 6;
                td.className = 'px-5 py-8 text-center text-red-600';
                td.textContent = 'Error al cargar los datos. Intenta nuevamente.';
                tr.appendChild(td);
                this.dom.tablaBody.appendChild(tr);
            }
        },

        /**
         * CORRECCIÓN 1 — XSS: se construye el DOM con createElement + textContent.
         * CORRECCIÓN 3 — Los datos originales se guardan en data-* para leerlos en openModal().
         */
        renderTable(data) {
            this.dom.tablaBody.innerHTML = '';

            data.forEach(g => {
                const varNum = g.VARIACION ? parseFloat(g.VARIACION) : null;
                const varClass = varNum !== null && varNum < 0
                    ? 'text-red-600 bg-red-50'
                    : 'text-emerald-600 bg-emerald-50';
                const varIcon  = varNum !== null && varNum < 0 ? 'fa-arrow-down' : 'fa-arrow-up';
                const varText  = varNum !== null
                    ? `${varNum > 0 ? '+' : ''}${g.VARIACION}%`
                    : '—';

                const tr = document.createElement('tr');
                tr.className = 'table-row-hover group';
                tr.dataset.id   = g.id;
                // Guardar datos limpios en data-* para el modal
                tr.dataset.prog = g.CODI_PROG;
                tr.dataset.econ = g.CODI_ECON;
                tr.dataset.app  = g.APLICACION_PRESUPUESTARIA;
                tr.dataset.c24  = g.CR_INIC_2024;
                tr.dataset.c25  = g.CR_INIC_2025;
                tr.dataset.var  = g.VARIACION ?? '';
                tr.setAttribute('tabindex', '0');
                tr.setAttribute('role', 'button');
                tr.setAttribute('aria-label', `Editar gasto ${g.CODI_PROG}`);

                // Celda Programa
                const tdProg = document.createElement('td');
                tdProg.className = 'px-5 py-4 font-semibold text-gray-900 group-hover:text-sky-700';
                const progWrap = document.createElement('div');
                progWrap.className = 'flex items-center gap-3';
                const dot = document.createElement('div');
                dot.className = 'w-2 h-2 rounded-full bg-sky-500 flex-shrink-0';
                const progText = document.createElement('span');
                progText.textContent = g.CODI_PROG;
                progWrap.appendChild(dot);
                progWrap.appendChild(progText);
                tdProg.appendChild(progWrap);

                // Celda Económico
                const tdEcon = document.createElement('td');
                tdEcon.className = 'px-5 py-4 text-gray-700';
                tdEcon.textContent = g.CODI_ECON;

                // Celda Aplicación (truncada con title)
                const tdApp = document.createElement('td');
                tdApp.className = 'px-5 py-4 text-gray-600 max-w-xs truncate';
                tdApp.title = g.APLICACION_PRESUPUESTARIA;
                tdApp.textContent = g.APLICACION_PRESUPUESTARIA;

                // Celda 2024
                const td24 = document.createElement('td');
                td24.className = 'px-5 py-4 text-right font-mono text-gray-500';
                td24.textContent = this.formatMoney(g.CR_INIC_2024);

                // Celda 2025
                const td25 = document.createElement('td');
                td25.className = 'px-5 py-4 text-right font-mono font-bold text-gray-900';
                td25.textContent = this.formatMoney(g.CR_INIC_2025);

                // Celda Variación
                const tdVar = document.createElement('td');
                tdVar.className = 'px-5 py-4 text-right';
                const varSpan = document.createElement('span');
                varSpan.className = `px-3 py-1.5 rounded-full text-sm font-semibold ${varClass}`;
                const varIconEl = document.createElement('i');
                varIconEl.className = `fas ${varIcon} mr-1`;
                varSpan.appendChild(varIconEl);
                varSpan.appendChild(document.createTextNode(varText));
                tdVar.appendChild(varSpan);

                tr.append(tdProg, tdEcon, tdApp, td24, td25, tdVar);
                this.dom.tablaBody.appendChild(tr);
            });
        },

        renderPagination(current, last) {
            if (last <= 1) {
                this.dom.paginacion.innerHTML = '';
                return;
            }

            let html = '';

            if (current > 1) {
                html += `<button data-page="${current - 1}" class="px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-chevron-left"></i> Anterior
                </button>`;
            }

            this.getVisiblePages(current, last).forEach(page => {
                if (page === '...') {
                    html += `<span class="px-3 py-2 text-gray-400">...</span>`;
                } else {
                    const activeClass = page === current
                        ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white border-transparent shadow-md'
                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50';
                    html += `<button data-page="${page}" class="px-4 py-2.5 rounded-lg border transition-all duration-300 ${activeClass}" ${page === current ? 'aria-current="page"' : ''}>${page}</button>`;
                }
            });

            if (current < last) {
                html += `<button data-page="${current + 1}" class="px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-all duration-300 flex items-center gap-2">
                    Siguiente <i class="fas fa-chevron-right"></i>
                </button>`;
            }

            this.dom.paginacion.innerHTML = html;
        },

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
                    if (i - l === 2) rangeWithDots.push(l + 1);
                    else if (i - l !== 1) rangeWithDots.push('...');
                }
                rangeWithDots.push(i);
                l = i;
            });

            return rangeWithDots;
        },

        /**
         * CORRECCIÓN 2 — Selector corregido: 'th.th-sortable .sort-icon'
         */
        handleSort(th) {
            const campo = th.dataset.campo;

            if (this.state.sortCampo === campo) {
                this.state.sortDireccion = this.state.sortDireccion === 'asc' ? 'desc' : 'asc';
            } else {
                this.state.sortCampo = campo;
                this.state.sortDireccion = 'asc';
            }

            // Limpiar todos los iconos
            document.querySelectorAll('th.th-sortable .sort-icon').forEach(s => {
                s.innerHTML = '';
            });

            // Actualizar el icono de la columna activa
            const icon = th.querySelector('.sort-icon');
            if (icon) {
                icon.innerHTML = this.state.sortDireccion === 'asc'
                    ? '<i class="fas fa-arrow-up text-sky-500 ml-1"></i>'
                    : '<i class="fas fa-arrow-down text-sky-500 ml-1"></i>';
            }

            this.loadData();
        },

        /**
         * CORRECCIÓN 3 — openModal() lee datos desde data-* en lugar del DOM visual.
         */
        openModal(row) {
            const f = this.dom.inputsForm;
            this.clearErrors();

            if (row) {
                // Modo edición: datos limpios desde data-*
                f.id.value   = row.dataset.id;
                f.prog.value = row.dataset.prog;
                f.econ.value = row.dataset.econ;
                f.app.value  = row.dataset.app;
                f.c24.value  = row.dataset.c24;
                f.c25.value  = row.dataset.c25;
                f.var.value  = row.dataset.var;

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

        closeModal() {
            this.toggleModal(false);
        },

        toggleModal(show) {
            if (show) {
                this.dom.modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                // Enfocar primer campo
                setTimeout(() => this.dom.inputsForm.prog.focus(), 100);
            } else {
                this.dom.modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        },

        /**
         * CORRECCIÓN 5 — Modal personalizado de confirmación en lugar de confirm().
         */
        showConfirmModal() {
            this.dom.confirmModal.classList.remove('hidden');
        },

        /**
         * CORRECCIÓN 7 — Validación extendida: tipos, rangos negativos, mensajes visibles.
         */
        validateForm() {
            const f = this.dom.inputsForm;
            let valid = true;
            this.clearErrors();

            if (!f.prog.value.trim()) {
                this.showError('prog', 'Campo obligatorio');
                valid = false;
            }
            if (!f.econ.value.trim()) {
                this.showError('econ', 'Campo obligatorio');
                valid = false;
            }
            if (f.c24.value !== '' && parseFloat(f.c24.value) < 0) {
                this.showError('c24', 'Debe ser un número positivo');
                valid = false;
            }
            if (f.c25.value !== '' && parseFloat(f.c25.value) < 0) {
                this.showError('c25', 'Debe ser un número positivo');
                valid = false;
            }

            return valid;
        },

        showError(field, msg) {
            const el = this.dom.errors[field];
            if (el) {
                el.textContent = msg;
                el.classList.remove('hidden');
                this.dom.inputsForm[field].classList.add('border-red-400', 'ring-2', 'ring-red-200');
            }
        },

        clearErrors() {
            Object.keys(this.dom.errors).forEach(key => {
                const el = this.dom.errors[key];
                if (el) el.classList.add('hidden');
                const input = this.dom.inputsForm[key];
                if (input) input.classList.remove('border-red-400', 'ring-2', 'ring-red-200');
            });
        },

        async handleSave(e) {
            e.preventDefault();

            if (!this.validateForm()) return;

            const f = this.dom.inputsForm;
            const id = f.id.value;
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/gastos/${id}` : '/gastos';

            const data = {
                CODI_PROG:                f.prog.value.trim(),
                CODI_ECON:                f.econ.value.trim(),
                APLICACION_PRESUPUESTARIA:f.app.value.trim(),
                CR_INIC_2024:             parseFloat(f.c24.value) || 0,
                CR_INIC_2025:             parseFloat(f.c25.value) || 0,
                VARIACION:                f.var.value.trim()
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
                    const errBody = await res.json().catch(() => ({}));
                    throw new Error(errBody.message || `Error ${res.status}`);
                }
            } catch (error) {
                console.error('Error al guardar:', error);
                this.showNotification(`Error al guardar: ${error.message}`, 'error');
            }
        },

        async handleDelete() {
            const id = this.dom.inputsForm.id.value;
            if (!id) return;

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
                    throw new Error(`Error ${res.status}`);
                }
            } catch (error) {
                console.error('Error al eliminar:', error);
                this.showNotification(`Error al eliminar: ${error.message}`, 'error');
            }
        },

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
                console.error('Error cargando gráficos:', error);
                this.renderEmptyCharts();
            }
        },

        renderEmptyCharts() {
            const emptyData = [{ codigo: 'Sin datos', total: 1 }];
            this.renderMainChart('graficoPrograma', emptyData, 'codigo', 'mainPrograma');
            this.renderMainChart('graficoEconomico', emptyData, 'codigo', 'mainEconomico');
        },

        renderMainChart(canvasId, data, labelKey, chartKey) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;

            const labels = data.map(d => d[labelKey] || 'Sin etiqueta');
            const values = data.map(d => parseFloat(d.total) || 0);

            if (this.charts[chartKey]) {
                this.charts[chartKey].destroy();
            }

            const isPrograma = chartKey === 'mainPrograma';
            const backgroundColors = isPrograma
                ? ['#0ea5e9','#3b82f6','#60a5fa','#93c5fd','#bfdbfe','#7dd3fc','#38bdf8','#0284c7','#0369a1','#0c4a6e']
                : ['#f59e0b','#fbbf24','#fcd34d','#fde68a','#fef3c7','#d97706','#b45309','#92400e','#78350f','#451a03'];

            this.charts[chartKey] = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels,
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
                            labels: { padding: 15, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } }
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const value = context.raw || 0;
                                    const total = values.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${this.formatMoney(value)} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        },

        renderModalChart() {
            const ctx = document.getElementById('graficoModal');
            if (!ctx) return;

            const context = ctx.getContext('2d');
            const v24 = parseFloat(this.dom.inputsForm.c24.value) || 0;
            const v25 = parseFloat(this.dom.inputsForm.c25.value) || 0;
            const type = this.state.chartModalType;

            if (this.charts.modal) {
                this.charts.modal.destroy();
            }

            const isIncrease = v25 >= v24;
            const color = isIncrease ? '#10b981' : '#ef4444';

            let gradient = context.createLinearGradient(0, 0, 0, 200);
            if (type === 'bar') {
                gradient.addColorStop(0, isIncrease ? 'rgba(16,185,129,0.8)' : 'rgba(239,68,68,0.8)');
                gradient.addColorStop(1, isIncrease ? 'rgba(16,185,129,0.3)' : 'rgba(239,68,68,0.3)');
            } else {
                gradient.addColorStop(0, isIncrease ? 'rgba(16,185,129,0.6)' : 'rgba(239,68,68,0.6)');
                gradient.addColorStop(1, isIncrease ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)');
            }

            this.charts.modal = new Chart(context, {
                type,
                data: {
                    labels: ['2024', '2025'],
                    datasets: [{
                        label: 'Presupuesto (€)',
                        data: [v24, v25],
                        backgroundColor: type === 'bar'
                            ? ['rgba(148,163,184,0.5)', gradient]
                            : gradient,
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
                                label: (ctx) => `${ctx.dataset.label}: ${this.formatMoney(ctx.raw)}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)', borderDash: [5, 5] },
                            ticks: { callback: (v) => this.formatMoney(v) }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        },

        updateGraphTypeButtons() {
            document.querySelectorAll('.btn-graph-type').forEach(btn => {
                const isActive = btn.dataset.type === this.state.chartModalType;
                btn.classList.toggle('bg-sky-500', isActive);
                btn.classList.toggle('text-white', isActive);
                btn.classList.toggle('bg-white', !isActive);
                btn.classList.toggle('text-gray-700', !isActive);
                btn.classList.toggle('hover:bg-gray-50', !isActive);
            });
        },

        updatePdfLink() {
            // Solo actualiza el estado interno; el export se gestiona por JS
            this._pdfParams = {
                q:    this.state.search,
                sort: this.state.sortCampo,
                dir:  this.state.sortDireccion
            };
        },

        handlePdfExport() {
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
                q:      this.state.search,
                sort:   this.state.sortCampo,
                dir:    this.state.sortDireccion
            };

            try {
                const programaCanvas  = document.getElementById('graficoPrograma');
                const economicoCanvas = document.getElementById('graficoEconomico');
                if (programaCanvas) {
                    const inp = document.createElement('input');
                    inp.type  = 'hidden';
                    inp.name  = 'grafico_programa';
                    inp.value = programaCanvas.toDataURL('image/png', 1.0);
                    form.appendChild(inp);
                }
                if (economicoCanvas) {
                    const inp = document.createElement('input');
                    inp.type  = 'hidden';
                    inp.name  = 'grafico_economico';
                    inp.value = economicoCanvas.toDataURL('image/png', 1.0);
                    form.appendChild(inp);
                }
            } catch (err) {
                console.error('Error obteniendo imágenes de gráficos:', err);
            }

            Object.entries(payload).forEach(([key, val]) => {
                const inp = document.createElement('input');
                inp.type  = 'hidden';
                inp.name  = key;
                inp.value = val;
                form.appendChild(inp);
            });

            document.body.appendChild(form);
            this.showNotification('Generando PDF...', 'info');
            form.submit();
            document.body.removeChild(form);
        },

        showNotification(message, type = 'info') {
            let container = document.getElementById('expense-notification');
            if (!container) {
                container = document.createElement('div');
                container.id = 'expense-notification';
                container.className = 'fixed top-6 right-6 z-50 max-w-sm';
                document.body.appendChild(container);
            }

            const typeClasses = {
                success: 'bg-emerald-50 border-emerald-200 text-emerald-800',
                error:   'bg-red-50 border-red-200 text-red-800',
                warning: 'bg-amber-50 border-amber-200 text-amber-800',
                info:    'bg-sky-50 border-sky-200 text-sky-800'
            };
            const iconClasses = {
                success: 'fa-check-circle text-emerald-500',
                error:   'fa-exclamation-circle text-red-500',
                warning: 'fa-exclamation-triangle text-amber-500',
                info:    'fa-info-circle text-sky-500'
            };

            const id = Date.now();
            const div = document.createElement('div');
            div.id = `notification-${id}`;
            div.className = `rounded-xl border p-4 shadow-lg mb-3 transition-all duration-300 ${typeClasses[type]}`;

            const inner = document.createElement('div');
            inner.className = 'flex items-center gap-3';

            const icon = document.createElement('i');
            icon.className = `fas ${iconClasses[type]} text-xl`;

            const text = document.createElement('div');
            text.className = 'flex-1';
            text.textContent = message;

            const closeBtn = document.createElement('button');
            closeBtn.className = 'text-gray-400 hover:text-gray-600';
            closeBtn.setAttribute('aria-label', 'Cerrar notificación');
            closeBtn.innerHTML = '<i class="fas fa-times"></i>';
            closeBtn.addEventListener('click', () => div.remove());

            inner.append(icon, text, closeBtn);
            div.appendChild(inner);
            container.insertAdjacentElement('afterbegin', div);

            setTimeout(() => {
                div.style.transform = 'translateX(100%)';
                div.style.opacity   = '0';
                setTimeout(() => div.remove(), 300);
            }, 5000);
        },

        setCurrentDate() {
            const now = new Date();
            document.getElementById('current-date').textContent =
                now.toLocaleDateString('es-ES', {
                    year: 'numeric', month: 'long', day: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });
        },

        formatMoney(amount) {
            const num = parseFloat(amount);
            if (isNaN(num)) return '0,00 €';
            return new Intl.NumberFormat('es-ES', {
                style: 'currency', currency: 'EUR',
                minimumFractionDigits: 2, maximumFractionDigits: 2
            }).format(num);
        },

        formatNumber(num) {
            return new Intl.NumberFormat('es-ES').format(num);
        },

        parseMoney(str) {
            return str.replace(/\./g, '').replace(',', '.').replace(/[^\d.-]/g, '');
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        ExpenseManager.init();
    });
</script>
</body>
</html>
