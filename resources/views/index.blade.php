<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscador de Gastos 2025</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-blue-50 to-white min-h-screen font-sans text-gray-800 p-6">

<div class="max-w-7xl mx-auto bg-white shadow-2xl rounded-xl p-8">

    <div class="flex justify-between items-center mb-10"> 
        
        <img src="{{ asset('images/logo.png') }}" alt="Logo Ayuntamiento" class="h-16 w-auto">

        <div class="flex gap-3"> 

            <button id="btn-nuevo" class="px-5 py-2 rounded-full bg-sky-600 text-white hover:bg-sky-700 shadow-md transition flex items-center">
                ➕ Nuevo Presupuesto
            </button>

            <a id="btn-pdf" href="#" target="_blank"
            class="px-5 py-2 rounded-full bg-gray-800 text-white hover:bg-gray-900 transition flex items-center">
            📄 Exportar PDF
            </a>
            
        </div>
    </div>


    <h1 class="text-4xl font-extrabold text-center text-transparent bg-clip-text bg-gradient-to-r from-sky-600 to-blue-800 mb-10 drop-shadow-lg">
        🔍 Buscador de Gastos Presupuestarios 2025
    </h1>


    <div class="relative mb-8">
        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-sky-600 text-xl">🔎</span>
        <input type="text" id="search" placeholder="Buscar por programa, clasificación o texto..."
            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-full shadow-md focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition placeholder-gray-400" />
    </div>


    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
        <table class="min-w-full text-left text-sm bg-white" id="tabla-gastos">
            <thead class="bg-blue-100 text-blue-800 uppercase font-semibold tracking-wide">
                <tr>
                    <th data-campo="CODI_PROG" class="px-4 py-3 border cursor-pointer sortable">Programa <span></span></th>
                    <th data-campo="CODI_ECON" class="px-4 py-3 border cursor-pointer sortable">Económico <span></span></th>
                    <th data-campo="APLICACION_PRESUPUESTARIA" class="px-4 py-3 border cursor-pointer sortable">Aplicación <span></span></th>
                    <th data-campo="CR_INIC_2024" class="px-4 py-3 border text-right cursor-pointer sortable">Crédito 2024 (€) <span></span></th>
                    <th data-campo="CR_INIC_2025" class="px-4 py-3 border text-right cursor-pointer sortable">Crédito 2025 (€) <span></span></th>
                    <th data-campo="VARIACION" class="px-4 py-3 border text-right cursor-pointer sortable">Variación <span></span></th>
                </tr>
            </thead>
            <tbody id="tabla-body" class="divide-y divide-gray-100">
                </tbody>
        </table>
    </div>

    <div id="paginacion" class="mt-6 flex justify-center gap-2"></div>
</div>

<div id="modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-8 relative border border-sky-100">

        <h2 class="text-2xl font-bold text-sky-700 mb-6">✏️ Editar Gasto Presupuestario</h2>

        <button id="btn-cerrar-x" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 hover:rotate-90 transition-all duration-300 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <form id="form-editar" class="space-y-5">
            <input type="hidden" id="gasto-id">

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Programa</label>
                    <input type="text" id="CODI_PROG"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Económico</label>
                    <input type="text" id="CODI_ECON"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Aplicación Presupuestaria</label>
                <textarea id="APLICACION_PRESUPUESTARIA" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 resize-none focus:ring-2 focus:ring-sky-400 focus:outline-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Crédito 2024 (€)</label>
                    <input type="number" step="0.01" id="CR_INIC_2024"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Crédito 2025 (€)</label>
                    <input type="number" step="0.01" id="CR_INIC_2025"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Variación (%)</label>
                <input type="text" id="VARIACION"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none">
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-6">
                <button type="button" id="btn-cerrar"
                    class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Cancelar
                </button>
                <button type="button" id="btn-eliminar"
                    class="px-5 py-2 rounded-full bg-red-600 text-white hover:bg-red-700 transition hidden">
                    🗑️ Eliminar
                </button>
                <button type="submit"
                    class="px-5 py-2 rounded-full bg-sky-600 text-white hover:bg-sky-700 shadow-md transition">
                    💾 Guardar Cambios
                </button>
            </div>
            
        </form>

        <div class="mt-6 p-4 bg-slate-50 rounded-xl border border-gray-200">
            
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Comparativa Anual</h3>
                <div id="selector-grafico" class="inline-flex rounded-md shadow-sm">
                    <button type="button" data-type="line" 
                            class="btn-graph-type px-3 py-1 text-xs font-medium border border-gray-300 rounded-l-md bg-sky-500 text-white hover:bg-sky-600 transition">
                        📈 Línea
                    </button>
                    <button type="button" data-type="bar" 
                            class="btn-graph-type px-3 py-1 text-xs font-medium border border-gray-300 rounded-r-md bg-white text-gray-700 hover:bg-gray-100 transition">
                        📊 Barras
                    </button>
                </div>
            </div>
            
            <div class="h-40">
                <canvas id="graficoModal"></canvas>
            </div>
        </div>
        
    </div>
</div>

<!-- 📊 Contenedores de gráficos -->
<!-- 📊 Contenedores de gráficos con borde como la tabla -->
<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-4 my-8 border border-gray-200 rounded-lg shadow-sm bg-white p-4">
     <div class="p-4">
        <h2 class="text-lg font-semibold mb-2 text-gray-700 text-center">Presupuesto por Código de Programa</h2>
        <canvas id="graficoPrograma" height="200"></canvas>
    </div>
 
    <div class="p-4">
        <h2 class="text-lg font-semibold mb-2 text-gray-700 text-center">Presupuesto por Código Económico</h2>
        <canvas id="graficoEconomico" height="200"></canvas>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const input = document.getElementById('search');
const tablaBody = document.getElementById('tabla-body');
let currentPage = 1;

// Buscar gastos y actualizar tabla
let sortCampo = '';
let sortDireccion = 'asc';

let chartGastos = null; // guardamos la instancia para actualizarla después

async function buscarGastos(page = 1) {
    currentPage = page;
    const q = input.value;
    const response = await fetch(`/gastos/search?q=${encodeURIComponent(q)}&page=${page}&sort=${sortCampo}&dir=${sortDireccion}`);
    const json = await response.json();

    const datos = json.data;
    const current = json.current_page;
    const last = json.last_page;

    // 👉 Pintar filas en la tabla
    tablaBody.innerHTML = datos.map(g => `
        <tr data-id="${g.id}" class="hover:bg-blue-50 cursor-pointer transition">
            <td class="px-4 py-2 border font-medium">${g.CODI_PROG}</td>
            <td class="px-4 py-2 border">${g.CODI_ECON}</td>
            <td class="px-4 py-2 border">${g.APLICACION_PRESUPUESTARIA}</td>
            <td class="px-4 py-2 border text-right font-mono">${parseFloat(g.CR_INIC_2024).toLocaleString('es-ES',{minimumFractionDigits:2, maximumFractionDigits:2})}</td>
            <td class="px-4 py-2 border text-right font-mono">${parseFloat(g.CR_INIC_2025).toLocaleString('es-ES',{minimumFractionDigits:2, maximumFractionDigits:2})}</td>
            <td class="px-4 py-2 border text-right">
                ${g.VARIACION 
                    ? (g.VARIACION.includes('-') 
                        ? `<span class="text-red-600 font-semibold">${g.VARIACION}</span>` 
                        : `<span class="text-green-600 font-semibold">+${g.VARIACION}</span>`)
                    : '<span class="text-gray-400">—</span>'
                }
            </td>
        </tr>
    `).join('');

    renderPagination(current, last);

}


document.querySelectorAll('th.sortable').forEach(th => {
    th.addEventListener('click', () => {
        const campo = th.dataset.campo;

        if (sortCampo === campo) {
            // Cambiar la dirección si ya está ordenando por ese campo
            sortDireccion = sortDireccion === 'asc' ? 'desc' : 'asc';
        } else {
            sortCampo = campo;
            sortDireccion = 'asc';
        }

        // Quitar flechas de todos los th
        document.querySelectorAll('th.sortable span').forEach(span => span.textContent = '');

        // Poner flecha en el actual
        th.querySelector('span').textContent = sortDireccion === 'asc' ? '▲' : '▼';

        buscarGastos(1);
    });
});

// Actualizar enlace PDF según búsqueda y orden
const btnPdf = document.getElementById('btn-pdf');

// Solo actualizamos la URL del botón (sin enviar todavía)
function actualizarPdfLink() {
    const q = input.value;
    const url = `/gastos/pdf?sort=${sortCampo}&dir=${sortDireccion}&q=${encodeURIComponent(q)}`;
    btnPdf.href = url; // para mantener funcionalidad tradicional si quieres
}

// Evento click del botón para enviar POST con gráficos
btnPdf.addEventListener('click', function(e){
    e.preventDefault();

    const graficoProgramaImg = document.getElementById('graficoPrograma').toDataURL();
    const graficoEconomicoImg = document.getElementById('graficoEconomico').toDataURL();

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/gastos/pdf';
    form.target = '_blank';

    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfInput);

    ['q','sort','dir'].forEach(name => {
        const inputElem = document.createElement('input');
        inputElem.type = 'hidden';
        inputElem.name = name;
        if(name === 'q') inputElem.value = input.value;
        if(name === 'sort') inputElem.value = sortCampo;
        if(name === 'dir') inputElem.value = sortDireccion;
        form.appendChild(inputElem);
    });

    const progImgInput = document.createElement('input');
    progImgInput.type = 'hidden';
    progImgInput.name = 'grafico_programa';
    progImgInput.value = graficoProgramaImg;
    form.appendChild(progImgInput);

    const econImgInput = document.createElement('input');
    econImgInput.type = 'hidden';
    econImgInput.name = 'grafico_economico';
    econImgInput.value = graficoEconomicoImg;
    form.appendChild(econImgInput);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
});



// Llamar cada vez que cambie la búsqueda o el orden
input.addEventListener('input', actualizarPdfLink);
document.querySelectorAll('th.sortable').forEach(th => {
    th.addEventListener('click', actualizarPdfLink);
});

// Inicializar al cargar
actualizarPdfLink();


const btnNuevo = document.getElementById('btn-nuevo');

btnNuevo.addEventListener('click', () => {
    // Vaciar el formulario
    document.getElementById('gasto-id').value = '';
    document.getElementById('CODI_PROG').value = '';
    document.getElementById('CODI_ECON').value = '';
    document.getElementById('APLICACION_PRESUPUESTARIA').value = '';
    document.getElementById('CR_INIC_2024').value = '';
    document.getElementById('CR_INIC_2025').value = '';
    document.getElementById('VARIACION').value = '';

    document.getElementById('btn-eliminar').classList.add('hidden');


    modal.classList.remove('hidden');
});



// Renderizar botones de paginación
function renderPagination(current, last) {
    let html = '';
    if(last > 1){
        html += `<div class="flex justify-center gap-2 mt-4 flex-wrap">`;
        if(current > 1){
            html += `<button class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600" onclick="buscarGastos(${current-1})">« Prev</button>`;
        }
        for(let i=1; i<=last; i++){
            html += `<button class="px-3 py-1 rounded ${i===current?'bg-blue-600 text-white':'bg-gray-200 hover:bg-gray-300'}" onclick="buscarGastos(${i})">${i}</button>`;
        }
        if(current < last){
            html += `<button class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600" onclick="buscarGastos(${current+1})">Next »</button>`;
        }
        html += `</div>`;
    }
    document.getElementById('paginacion').innerHTML = html;
}

// Buscar en tiempo real
input.addEventListener('input', () => buscarGastos());
buscarGastos();

// Modal de edición
const modal = document.getElementById('modal');
const formEditar = document.getElementById('form-editar');

// Abrir modal al click en fila
tablaBody.addEventListener('click', e=>{
    let row = e.target.closest('tr');
    if(!row) return;

    const cells = row.children;
    document.getElementById('gasto-id').value = row.dataset.id;
    document.getElementById('CODI_PROG').value = cells[0].textContent.trim();
    document.getElementById('CODI_ECON').value = cells[1].textContent.trim();
    document.getElementById('APLICACION_PRESUPUESTARIA').value = cells[2].textContent.trim();
    document.getElementById('CR_INIC_2024').value = cells[3].textContent.replace(/\./g,'').replace(',','.');
    document.getElementById('CR_INIC_2025').value = cells[4].textContent.replace(/\./g,'').replace(',','.');
    document.getElementById('VARIACION').value = cells[5].textContent.replace('+','').replace('%','').trim();

    // Obtenemos los valores limpios para la gráfica
    // Quitamos los puntos de miles y cambiamos coma por punto
    let valor2024 = parseFloat(cells[3].textContent.replace(/\./g,'').replace(',','.')) || 0;
    let valor2025 = parseFloat(cells[4].textContent.replace(/\./g,'').replace(',','.')) || 0;

    // 👇 LLAMADA NUEVA AQUÍ
    actualizarGraficoModal(valor2024, valor2025);

    // 👇 mostrar botón de eliminar SOLO en modo edición
    document.getElementById('btn-eliminar').classList.remove('hidden');

    modal.classList.remove('hidden');
});


// Cerrar modal
document.getElementById('btn-cerrar').addEventListener('click', ()=> modal.classList.add('hidden'));
document.getElementById('btn-cerrar-x').addEventListener('click', () => modal.classList.add('hidden'));

// Guardar cambios
formEditar.addEventListener('submit', async e => {
    e.preventDefault();
    const id = document.getElementById('gasto-id').value;

    const data = {
        CODI_PROG: document.getElementById('CODI_PROG').value,
        CODI_ECON: document.getElementById('CODI_ECON').value,
        APLICACION_PRESUPUESTARIA: document.getElementById('APLICACION_PRESUPUESTARIA').value,
        CR_INIC_2024: document.getElementById('CR_INIC_2024').value,
        CR_INIC_2025: document.getElementById('CR_INIC_2025').value,
        VARIACION: document.getElementById('VARIACION').value
    };

    const url = id ? `/gastos/${id}` : '/gastos';
    const method = id ? 'PUT' : 'POST';

    const response = await fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    });

    if (response.ok) {
        alert(id ? 'Gasto actualizado' : 'Nuevo gasto creado');
        modal.classList.add('hidden');
        buscarGastos(currentPage);
    } else {
        alert('Error al guardar');
    }
});

document.getElementById('btn-eliminar').addEventListener('click', async () => {
    const id = document.getElementById('gasto-id').value;

    if (!id) return;

    if (!confirm('¿Estás seguro de que deseas eliminar este presupuesto?')) return;

    const response = await fetch(`/gastos/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });

    if (response.ok) {
        alert('Presupuesto eliminado correctamente');
        modal.classList.add('hidden');
        buscarGastos(currentPage);
    } else {
        alert('Error al eliminar');
    }
});


async function cargarGrafico() {
    const q = input.value;
    const sort = sortCampo;
    const dir = sortDireccion;

    const response = await fetch(`/gastos/chart-data/economico?q=${encodeURIComponent(q)}&sort=${sort}&dir=${dir}`);
    const data = await response.json();

    const labels = data.map(item => item.codigo_economico);
    // Aseguramos que los valores sean números desde el principio
    const values = data.map(item => parseFloat(item.total) || 0);

    const ctx = document.getElementById('graficoEconomico').getContext('2d');
    if (window.chartEconomico) window.chartEconomico.destroy();
    
    window.chartEconomico = new Chart(ctx, {
        type: 'pie',
        data: { labels, datasets: [{ data: values }] },
        options: { 
            plugins: { 
                tooltip: { 
                    callbacks: { 
                        label: function(context) {
                            let value = context.parsed;
                            // Sumamos asegurándonos de que son números
                            let total = context.dataset.data.reduce((a, b) => a + (parseFloat(b) || 0), 0);
                            
                            // Evitamos división por cero
                            let percentage = total > 0 ? ((value / total) * 100).toFixed(1) + '%' : '0%';
                            
                            return `${context.label}: ${value.toLocaleString('es-ES')} € (${percentage})`;
                        } 
                    } 
                } 
            } 
        }
    });
}



// 🔄 Llamar cuando cambie búsqueda u orden
input.addEventListener('input', cargarGrafico);
document.querySelectorAll('th.sortable').forEach(th => {
    th.addEventListener('click', cargarGrafico);
});

// ▶️ Cargar al inicio
cargarGrafico();

async function cargarGraficoPrograma() {
    const q = input.value;
    const sort = sortCampo;
    const dir = sortDireccion;

    const response = await fetch(`/gastos/chart-data/programa?q=${encodeURIComponent(q)}&sort=${sort}&dir=${dir}`);
    const data = await response.json();

    const labels = data.map(item => item.codigo_programa);
    // Aseguramos que los valores sean números desde el principio
    const values = data.map(item => parseFloat(item.total) || 0);

    if (window.chartPrograma) {
        window.chartPrograma.destroy();
    }

    const ctx = document.getElementById('graficoPrograma').getContext('2d');
    window.chartPrograma = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.parsed || 0;
                            // Sumamos asegurándonos de que son números
                            let total = context.dataset.data.reduce((a, b) => a + (parseFloat(b) || 0), 0);

                            // Evitamos división por cero
                            let percentage = total > 0 ? ((value / total) * 100).toFixed(1) + '%' : '0%';

                            return `${context.label}: ${value.toLocaleString('es-ES')} € (${percentage})`;
                        }
                    }
                }
            }
        }
    });
}

// 🔄 Llamar cuando cambie búsqueda u orden
input.addEventListener('input', cargarGraficoPrograma);
document.querySelectorAll('th.sortable').forEach(th => {
    th.addEventListener('click', cargarGraficoPrograma);
});

// ▶️ Cargar al inicio
cargarGraficoPrograma();


// Graficos de comparativa anual en el modal

let chartModal = null; 
let currentChartType = 'line'; // Estado inicial: Línea

document.addEventListener('DOMContentLoaded', () => {
    // Escucha clics en los botones de tipo de gráfico
    document.querySelectorAll('.btn-graph-type').forEach(button => {
        button.addEventListener('click', function() {
            const newType = this.dataset.type;
            
            // 1. Actualizar el estado global
            currentChartType = newType;
            
            // 2. Cambiar la apariencia de los botones
            document.querySelectorAll('.btn-graph-type').forEach(btn => {
                if (btn.dataset.type === newType) {
                    btn.classList.add('bg-sky-500', 'text-white');
                    btn.classList.remove('bg-white', 'text-gray-700');
                } else {
                    btn.classList.remove('bg-sky-500', 'text-white');
                    btn.classList.add('bg-white', 'text-gray-700');
                }
            });

            // 3. Volver a dibujar el gráfico con el nuevo tipo (usando los datos actuales del modal)
            const val2024 = parseFloat(document.getElementById('CR_INIC_2024').value) || 0;
            const val2025 = parseFloat(document.getElementById('CR_INIC_2025').value) || 0;
            
            actualizarGraficoModal(val2024, val2025);
        });
    });
});


function actualizarGraficoModal(val2024, val2025) {
    const ctx = document.getElementById('graficoModal').getContext('2d');

    if (chartModal) {
        chartModal.destroy();
    }

    const colorTendencia = val2025 >= val2024 ? 'rgb(34, 197, 94)' : 'rgb(239, 68, 68)'; // Verde o Rojo
    
    // --- Configuración del Dataset ---
    const datasetConfig = {
        label: 'Crédito Presupuestario (€)',
        data: [val2024, val2025],
        backgroundColor: currentChartType === 'bar' ? [ 'rgba(156, 163, 175, 0.5)', colorTendencia ] : colorTendencia,
        borderColor: colorTendencia,
        borderWidth: 3,
        // Propiedades específicas del tipo de gráfico
        tension: currentChartType === 'line' ? 0.2 : 0,
        pointRadius: currentChartType === 'line' ? 6 : 0,
        fill: false,
        borderRadius: currentChartType === 'bar' ? 5 : 0,
        barPercentage: currentChartType === 'bar' ? 0.6 : 1,
    };


    chartModal = new Chart(ctx, {
        type: currentChartType, // 👈 Usa el tipo de gráfico seleccionado
        data: {
            labels: ['2024', '2025'],
            datasets: [datasetConfig]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            // Solo usar el eje Y si es un gráfico de barras (para vertical)
            indexAxis: currentChartType === 'bar' ? 'x' : 'x', 
            
            plugins: {
                legend: { display: currentChartType === 'line' }, // Mostramos leyenda solo en línea (para claridad)
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.raw.toLocaleString('es-ES')} €`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    grid: { display: true, color: '#f3f4f6' }
                }
            }
        }
    });
}

</script>

</body>
</html>