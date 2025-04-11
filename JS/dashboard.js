document.addEventListener('DOMContentLoaded', function () {
    // Inicializar el calendario
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // Vista inicial: vista de mes
        locale: 'es', // Establecer el idioma del calendario a español
        dateClick: function (info) {
            selectedDate = info.dateStr; // Guardar la fecha seleccionada
            // Eliminar la clase 'selected' de cualquier otro día previamente marcado
            document.querySelectorAll('.fc-day').forEach(function (day) {
                day.classList.remove('selected');
            });
            // Agregar la clase 'selected' al día clickeado
            info.dayEl.classList.add('selected');
            mostrarTareasDelDia(selectedDate); // Mostrar tareas del día seleccionado
        },
        eventClick: function (info) {
            const eventTitle = info.event.title;
            const eventId = info.event.id; // Obtener el ID del evento (nota)

            if (eventTitle) {
                // Mostrar la nota en el modal
                document.getElementById('notaContenido').innerText = eventTitle;

                // Asegurarse de que el eventId se asigne correctamente al botón de borrar
                document.getElementById('btnBorrarNota').setAttribute('data-event-id', eventId);

                console.log("Event ID asignado al botón:", eventId); // Verificar si el ID se asigna correctamente

                // Mostrar el modal con la nota completa
                const modal = new bootstrap.Modal(document.getElementById('modalNota'));
                modal.show();
            }
        }
    });
    calendar.render(); // Renderizar el calendario

    let selectedDate = null; // Variable para almacenar la fecha seleccionada
    const tareasPorFecha = {}; // Objeto para almacenar tareas por fecha
    const estadosTareas = {}; // Objeto para almacenar el estado de cada tarea (marcada/desmarcada)

    // Función para agregar una emoción a un día específico
    window.agregarEmocion = function (emocion) {
        if (selectedDate) {
            const eventId = Date.now(); // Usar el timestamp como ID único para la emoción
            // Agregar evento (emoción) al calendario
            calendar.addEvent({
                id: eventId, // Asignar el ID único
                title: emocion,
                start: selectedDate,
                allDay: true
            });
            alert("Estado de ánimo agregado: " + emocion);

            // Asignar el ID al botón de borrar si es necesario
            document.getElementById('btnBorrarNota').setAttribute('data-event-id', eventId);
        } else {
            alert("Selecciona una fecha primero.");
        }
    };

    // Función para agregar una nota a un día específico
    window.agregarNota = function () {
        if (selectedDate) {
            const nota = document.getElementById('nota').value; // Obtener la nota escrita
            const eventId = Date.now(); // Usar el timestamp como ID único

            // Agregar evento (nota) al calendario con un ID único
            calendar.addEvent({
                id: eventId, // Asignar el ID único
                title: nota,
                start: selectedDate,
                allDay: true
            });

            // Asignar el ID al botón de borrar
            document.getElementById('btnBorrarNota').setAttribute('data-event-id', eventId);

            document.getElementById('nota').value = ''; // Limpiar el campo de nota
            alert("Nota agregada.");
        } else {
            alert("Selecciona una fecha primero.");
        }
    };
    // Función para eliminar la nota o emoción
    document.getElementById('btnBorrarNota').addEventListener('click', function () {
        const eventId = this.getAttribute('data-event-id'); // Obtener el ID del evento (nota o emoción)
        console.log("Event ID en el botón de borrar:", eventId); // Verificar si el ID se obtiene correctamente

        if (eventId) {
            const event = calendar.getEventById(eventId); // Obtener el evento por ID

            if (event) {
                event.remove(); // Eliminar el evento del calendario
                console.log("Evento eliminado.");

                // Cerrar el modal después de eliminar
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalNota'));
                modal.hide(); // Cerrar el modal
            } else {
                alert("No se encontró el evento con ID: " + eventId); // Si no se encuentra el evento
            }
        } else {
            alert("No se pudo obtener el ID del evento.");
        }
    });

    // Función para agregar una tarea al día seleccionado
    window.agregarTarea = function () {
        const tarea = document.getElementById('nuevaTarea').value; // Obtener la tarea escrita
        if (tarea && selectedDate) {
            if (!tareasPorFecha[selectedDate]) {
                tareasPorFecha[selectedDate] = []; // Crear arreglo de tareas si no existe
            }
            tareasPorFecha[selectedDate].push(tarea); // Agregar la tarea al arreglo de tareas del día

            // Actualizar la lista de tareas mostradas
            mostrarTareasDelDia(selectedDate);
            document.getElementById('nuevaTarea').value = ''; // Limpiar el campo de nueva tarea
        } else {
            alert("Selecciona una fecha primero.");
        }
    };

    // Función para borrar una tarea
    window.borrarTarea = function (btn, tarea) {
        const tareas = tareasPorFecha[selectedDate]; // Obtener las tareas del día seleccionado
        const index = tareas.indexOf(tarea); // Buscar la tarea a eliminar
        if (index > -1) {
            tareas.splice(index, 1); // Eliminar la tarea del arreglo
        }
        // Actualizar la lista de tareas mostradas
        mostrarTareasDelDia(selectedDate);
    };

    // Función para manejar el cambio de estado del checkbox
    window.toggleCheckbox = function (checkbox, tarea) {
        // Guardar el estado del checkbox (marcado o desmarcado) en el objeto `estadosTareas`
        estadosTareas[selectedDate + tarea] = checkbox.checked;
    };

    // Función para mostrar las tareas del día seleccionado
    function mostrarTareasDelDia(fecha) {
        const tareasLista = document.getElementById('tareasLista');
        tareasLista.innerHTML = ''; // Limpiar la lista de tareas

        if (tareasPorFecha[fecha]) {
            // Recorrer las tareas del día seleccionado y mostrarlas
            tareasPorFecha[fecha].forEach(function (tarea) {
                const tareaItem = document.createElement('div');
                tareaItem.classList.add('tarea-item');
                const checked = estadosTareas[selectedDate + tarea] ? 'checked' : ''; // Comprobar si la tarea está marcada
                tareaItem.innerHTML = `
                    <input type="checkbox" ${checked} onchange="toggleCheckbox(this, '${tarea}')"> ${tarea} 
                    <button class="btn btn-sm btn-danger ms-2" onclick="borrarTarea(this, '${tarea}')">❌</button>
                `;
                tareasLista.appendChild(tareaItem); // Añadir la tarea a la lista
            });
        }
    }

    // Funcionalidad para el botón de cambiar entre modo oscuro y claro
    const modoSwitch = document.querySelector('.modo-switch');
    modoSwitch.addEventListener('click', function () {
        document.body.classList.toggle('modo-oscuro'); // Cambiar clase para el modo oscuro
        modoSwitch.textContent = document.body.classList.contains('modo-oscuro') ? "☀️ Modo Claro" : "🌙 Modo Oscuro";
    });
});


// Función para agregar emoción y enviarla al servidor
function agregarEmocion(emocion) {
    if (selectedDate) {
        fetch('guardar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                tipo: 'emocion',
                usuarioId: 1, // Cambia esto según el ID del usuario
                fecha: selectedDate,
                contenido: emocion
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log("Emoción guardada: " + emocion);
                } else {
                    alert("Error al guardar emoción");
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        alert("Selecciona una fecha primero.");
    }
}


// Función para agregar nota y enviarla al servidor
function agregarNota() {
    const nota = document.getElementById('nota').value;
    if (selectedDate && nota) {
        fetch('guardar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                tipo: 'nota',
                usuarioId: 1, // Cambia esto según el ID del usuario
                fecha: selectedDate,
                contenido: nota
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log("Nota guardada");
                    document.getElementById('nota').value = ''; // Limpiar el campo de la nota
                } else {
                    alert("Error al guardar la nota");
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        alert("Selecciona una fecha y escribe una nota");
    }
}


// Función para manejar el cambio de estado de la tarea y enviarlo al servidor
function toggleCheckbox(checkbox, tarea) {
    const completada = checkbox.checked;
    if (selectedDate) {
        fetch('guardar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                tipo: 'tarea',
                usuarioId: 1, // Cambia esto según el ID del usuario
                fecha: selectedDate,
                contenido: { tarea: tarea, completada: completada }
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log("Tarea actualizada");
                } else {
                    alert("Error al actualizar la tarea");
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        alert("Selecciona una fecha primero.");
    }
}

// Función para agregar tarea y enviarla al servidor
function agregarTarea() {
    const tarea = document.getElementById('nuevaTarea').value;
    if (selectedDate && tarea) {
        fetch('guardar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                tipo: 'tarea',
                usuarioId: 1, // Cambia esto según el ID del usuario
                fecha: selectedDate,
                contenido: { tarea: tarea, completada: false }
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log("Tarea agregada");
                    document.getElementById('nuevaTarea').value = ''; // Limpiar campo
                } else {
                    alert("Error al agregar la tarea");
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        alert("Selecciona una fecha primero.");
    }
}

/* funcion para las videos llamadas */
let api = null;

function iniciarVideollamada() {
    const domain = "meet.jit.si";
    const roomName = "TDATrackerSala123";
    const options = {
        roomName: roomName,
        width: "100%",
        height: "100%",
        parentNode: document.querySelector('#jitsiContainer'),
        configOverwrite: {
            disableDeepLinking: true,
        },
        interfaceConfigOverwrite: {
            SHOW_JITSI_WATERMARK: false
        },
        userInfo: {
            displayName: "Usuario"
        }
    };

    document.getElementById('jitsiContainer').innerHTML = "";
    api = new JitsiMeetExternalAPI(domain, options);

    api.addEventListener('participantJoined', () => {
        alert("¡Ya hay otra persona en la sala!");
    });

    const modal = new bootstrap.Modal(document.getElementById('modalVideollamada'));
    modal.show();

    document.getElementById('modalVideollamada').addEventListener('hidden.bs.modal', () => {
        if (api) {
            api.dispose();
            api = null;
            console.log("Llamada finalizada");
        }
    }, { once: true });
}

// Función para cerrar sesion
function cerrarSesion() {
    window.location.href = '../PHP/cerrar_sesion.php';
}

//Funcion para cambiar la contraseña
function cambiarContrasena() {
    window.location.href = '../PHP/cambiar_pass.php';
}