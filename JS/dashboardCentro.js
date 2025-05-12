document.addEventListener('DOMContentLoaded', function() {
    // Estado global de la aplicación
    const appState = {
        selectedEspecialista: null,
        selectedUsuario: null,
        todosEspecialistas: [],
        filtradoEspecialistas: [],
        todosUsuarios: [],
        filtradoUsuarios: [],
        busquedaActual: ''
    };

    // Función para renderizar especialistas
    function renderEspecialistas() {
        const contenedor = document.getElementById('especialista');
        contenedor.innerHTML = '';

        // Combinar resultados filtrados + seleccionado si no está incluido
        const toDisplay = [
            ...(appState.selectedEspecialista && !appState.filtradoEspecialistas.some(e => e.Id === appState.selectedEspecialista.Id) 
                ? [appState.selectedEspecialista] 
                : []),
            ...appState.filtradoEspecialistas
        ];

        if (toDisplay.length === 0) {
            contenedor.innerHTML = '<p>No se encontraron especialistas</p>';
            return;
        }

        toDisplay.forEach(especialista => {
            const elemento = document.createElement('div');
            elemento.className = 'list-item' + (appState.selectedEspecialista?.Id === especialista.Id ? ' selected' : '');

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.checked = appState.selectedEspecialista?.Id === especialista.Id;
            
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    appState.selectedEspecialista = especialista;
                    // Asegurarnos que existe en todosEspecialistas
                    if (!appState.todosEspecialistas.some(e => e.Id === especialista.Id)) {
                        appState.todosEspecialistas.push(especialista);
                    }
                } else {
                    appState.selectedEspecialista = null;
                }
                renderEspecialistas();
            });

            const label = document.createElement('label');
            label.innerHTML = `
                <strong>${especialista.Nombre} ${especialista.Apellidos}</strong>
                <br><small>${especialista.Email}</small>
            `;

            elemento.appendChild(checkbox);
            elemento.appendChild(label);
            contenedor.appendChild(elemento);
        });
    }

    // Función para renderizar usuarios
    function renderUsuarios() {
        const contenedor = document.getElementById('usuario');
        contenedor.innerHTML = '';
    
        // Combinar resultados filtrados + seleccionado si no está incluido
        const toDisplay = [
            ...(appState.selectedUsuario && !appState.filtradoUsuarios.some(u => u.Id === appState.selectedUsuario.Id) 
                ? [appState.selectedUsuario] 
                : []),
            ...appState.filtradoUsuarios
        ];
    
        if (toDisplay.length === 0) {
            contenedor.innerHTML = '<p>No se encontraron usuarios</p>';
            return;
        }
    
        toDisplay.forEach(usuario => {
            const elemento = document.createElement('div');
            elemento.className = 'list-item' + (appState.selectedUsuario?.Id === usuario.Id ? ' selected' : '');
    
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.checked = appState.selectedUsuario?.Id === usuario.Id;
            
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    appState.selectedUsuario = usuario;
                    if (!appState.todosUsuarios.some(u => u.Id === usuario.Id)) {
                        appState.todosUsuarios.push(usuario);
                    }
                } else {
                    appState.selectedUsuario = null;
                }
                renderUsuarios();
            });
    
            const label = document.createElement('label');
            label.innerHTML = `
                <strong>${usuario.Nombre} ${usuario.Apellidos}</strong>
                <br><small>${usuario.Email}</small>
            `;
    
            const p = document.createElement('p');
            
            // Mostrar nombre del especialista o "Ninguno"
            const nombreEspecialista = 
            usuario.especialista_nombre && usuario.especialista_apellidos
                ? `${usuario.especialista_nombre} ${usuario.especialista_apellidos}`
                : "Ninguno";

            
            p.innerHTML = `
                <strong>Especialista asignado:</strong>
                <br><small>${nombreEspecialista}</small>
            `;
    
            elemento.appendChild(checkbox);
            elemento.appendChild(label);
            elemento.appendChild(p);
            contenedor.appendChild(elemento);
        });
    }

    // Función para buscar especialistas
    async function buscarEspecialistas(email) {
        try {
            const response = await fetch('../PHP/centro/obtenerEspecialistas.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, centro_id: centroId })
            });
            
            const data = await response.json();
            
            if (data.error) throw new Error(data.error);
            
            appState.filtradoEspecialistas = data.Especialista || [];
            renderEspecialistas();
        } catch (error) {
            console.error('Error buscando especialistas:', error);
            alert("Error al buscar especialistas");
        }
    }

    // Función para buscar usuarios
    async function buscarUsuarios(email) {
        try {
            const response = await fetch('../PHP/centro/obtenerTodosUsuarios.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, centro_id: centroId })
            });
            
            const data = await response.json();
            
            if (data.error) throw new Error(data.error);
            
            appState.filtradoUsuarios = data.Usuario || [];
            renderUsuarios();
        } catch (error) {
            console.error('Error buscando usuarios:', error);
            alert("Error al buscar usuarios");
        }
    }

    // Función para realizar búsquedas
    function hacerBusqueda(busqueda) {
        appState.busquedaActual = busqueda;
        
        if (busqueda.trim() === '') {
            // Si la búsqueda está vacía muestrar todos
            appState.filtradoEspecialistas = [...appState.todosEspecialistas];
            appState.filtradoUsuarios = [...appState.todosUsuarios];
        } else {
            // Realizar búsquedas individuales
            buscarEspecialistas(busqueda);
            buscarUsuarios(busqueda);
        }
        
        renderEspecialistas();
        renderUsuarios();
    }

    // Evento de búsqueda con debounce
    document.getElementById('buscar').addEventListener('input', debounce(function(e) {
        hacerBusqueda(e.target.value.trim());
    }, 300));

    // Botón Enlazar
    document.getElementById('btnEnlazar').addEventListener('click', async function() {
        if (!appState.selectedEspecialista || !appState.selectedUsuario) {
            alert("Debes seleccionar un especialista y un usuario");
            return;
        }

        try {
            const response = await fetch('../PHP/centro/enlazarEspecialistaUsuario.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    especialista_id: appState.selectedEspecialista.Id,
                    usuario_id: appState.selectedUsuario.Id,
                    centro_id: centroId
                })
            });

            const data = await response.json();
            
            if (data.success) {
                alert("Enlace realizado con éxito");
                // Actualizar estado
                appState.selectedUsuario.especialista_id = appState.selectedEspecialista.Id;
                appState.selectedEspecialista = null;
                appState.selectedUsuario = null;
                renderEspecialistas();
                renderUsuarios();
            } else {
                throw new Error(data.error || "Error al realizar el enlace");
            }
        } catch (error) {
            alert(error.message);
        }
    });

    // Botón Borrar
    document.getElementById('btnBorrar').addEventListener('click', async function() {
        if (!appState.selectedUsuario) {
            alert("Debes seleccionar un usuario primero");
            return;
        }

        try {
            const response = await fetch('../PHP/centro/borrarRelacionEspecialista.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    usuario_id: appState.selectedUsuario.Id,
                    centro_id: centroId
                })
            });

            const data = await response.json();
            
            if (data.success) {
                alert("Relación eliminada correctamente");
                // Actualizar el estado del usuario
                appState.selectedUsuario.especialista_id = null;
                appState.selectedUsuario = null;
                renderUsuarios();
            } else {
                throw new Error(data.error || "Error al eliminar la relación");
            }
        } catch (error) {
            alert(error.message);
        }
    });

    // Función debounce para mejor performance
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Carga inicial
    hacerBusqueda('');

    // Funcionalidad para el botón de cambiar entre modo oscuro y claro
    const modoSwitch = document.querySelector('.modo-switch');
    modoSwitch.addEventListener('click', function () {
        document.body.classList.toggle('modo-oscuro'); // Cambiar clase para el modo oscuro
        modoSwitch.textContent = document.body.classList.contains('modo-oscuro') ? "☀️ Modo Claro" : "🌙 Modo Oscuro";
    });
});

// Función para cerrar sesión
function cerrarSesion() {
    window.location.href = '../PHP/usuario/cerrar_sesion.php';
}

// Función para cambiar contraseña
function cambiarContrasena() {
    window.location.href = '../PHP/centro/cambiar_pass.php';
}