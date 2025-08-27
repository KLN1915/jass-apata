import { resetModal } from '../helpers/resetModal'
import { cleanInputs } from '../helpers/cleanInputs';
import { showErrors } from '../helpers/showErrors';

const form = document.querySelector('#createForm')
const url = form.action

//Obtener opciones para Barrios
document.addEventListener('DOMContentLoaded', () => {
    const firstZoneSelect = document.querySelector('.zoneSelect');

    if (firstZoneSelect) {
        axios.get('/getZones')
            .then(response => {
                const zones = response.data;
                firstZoneSelect.innerHTML = '<option value="" selected>Selecciona una zona</option>';
                zones.forEach(zone => {
                    const option = document.createElement('option');
                    option.value = zone.id;
                    option.textContent = zone.name;
                    firstZoneSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error al cargar zonas:', error));
    }
});

//Agregar y quitar campos para direcciones
document.getElementById('addDirectionFieldsButton').addEventListener('click', () => {
    const directionsContainer = document.getElementById('directionsContainer')
    const directionFields = document.getElementsByClassName('directionFields')[0]

    if (directionFields) {
        const clone = directionFields.cloneNode(true)
        // Limpiar inputs y selects
        clone.querySelectorAll('input, select, textarea').forEach(el => {
            el.value = '' // Vacía los demás campos
            el.classList.remove('is-invalid')
        })

        //Limpiar spans con errores
        clone.querySelectorAll('span.errors').forEach(span => {
            span.textContent  = '' // Vacía los demás campos
        })

        // Quitar d-none del botón eliminar en el clon
        const removeButton = clone.querySelector('.removeDirectionFieldsContainer .btn')
        if (removeButton) {
            removeButton.classList.remove('d-none')
        }
        directionsContainer.appendChild(clone)

        //Funcionalidad para quitar elemento
        if (removeButton) {
            removeButton.addEventListener('click', () => {
                clone.remove()
            })
        }

        //Replicar la limpieza dinámica a los nuevos inputs
        cleanInputs(clone)
    }
})

//Enviar datos
form.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    Swal.fire({
        title: 'Guardando...',
        text: 'Por favor, espera mientras guardamos los datos.',
        allowOutsideClick: false,
        onOpen: () => {
            Swal.showLoading();
        }
    });

    axios.post(url, formData)
        .then(response => {
            Swal.close();

            Swal.fire({
                title: 'Éxito',
                text: response.data.message, // Axios ya parsea JSON
                type: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = url;
            });
        })
        .catch(error => {
            Swal.close();

            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar los datos. Por favor, revisa los campos.',
                type: 'error',
                confirmButtonText: 'OK'
            });

            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;

                showErrors(errors)
            }
        });
});

//Limpiar inputs al ingresar datos
cleanInputs(form)

//Limpiar modal
$('#createModal').on('hidden.bs.modal', () => {
    resetModal('createModal')
    //retornar stepper a 1 al cerrar modal
    window.stepper.to(1)

    // Limpiar direcciones dinámicas: dejar solo el primer .directionFields
    const directionsContainer = document.getElementById('directionsContainer');
    const allDirectionFields = directionsContainer.querySelectorAll('.directionFields');

    if (allDirectionFields.length > 1) {
        // Mantener el primero y eliminar el resto
        allDirectionFields.forEach((field, index) => {
            if (index > 0) field.remove();
        });
    }
});