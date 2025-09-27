import { resetModal } from '../helpers/resetModal'
import { cleanInputs } from '../helpers/cleanInputs';
import { showErrors } from '../helpers/showErrors';
import { enableIfChange } from '../helpers/enableIfChanged';

const form = document.querySelector('#editForm')
const url = form.action

let originalData = {}
let template
let directionsCounter = 0

//Desbloquear 'cambiar titular'
document.getElementById('changeTitular').addEventListener('click', () => {
    Swal.fire({
        title: "¿Estás seguro de cambiar los datos del titular?",
        text: "Se agregarán nuevos datos al historial de titulares",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Aceptar",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('edit-namesLastnames').removeAttribute('disabled')
            document.getElementById('edit-dni').removeAttribute('disabled')
        }
    });
})

//Obtener opciones para Barrios
document.addEventListener('DOMContentLoaded', () => {
    template = document.querySelector('#directionTemplate')
    const zoneSelect = template.content.querySelector('.edit-zoneSelect')

    if (zoneSelect) {
        axios.get('/getZones')
            .then(response => {
                const zones = response.data;
                zoneSelect.innerHTML = '<option value="" selected>Selecciona una zona</option>';
                zones.forEach(zone => {
                    const option = document.createElement('option');
                    option.value = zone.id;
                    option.textContent = zone.name;
                    zoneSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error al cargar zonas:', error));
    }
})

//Funcion para clonar el template, limpiar errores y values, eliminar fila con boton '-'
function cloneDirectionFields(){
    const directionsContainer = document.getElementById('editDirectionsContainer')
    if(!template) return null

    const fragment = template.content.cloneNode(true)
    const clone = fragment.firstElementChild

    directionsCounter++

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

    //Funcionalidad para quitar elemento
    if (removeButton) {
        removeButton.addEventListener('click', () => {
            clone.remove()
            directionsCounter--

            //verificar si esta vacio el form
            if(directionsCounter === 0){
                addIfEmpty()
            }
        })        
    }

    //Replicar la limpieza dinámica a los nuevos inputs
    cleanInputs(clone)

    //Activar boton si form cambia
    // enableIfChange(form)

    directionsContainer.appendChild(clone)

    return clone
}

//Agregar directionFields obligatorio si el counter de direcciones es 0
function addIfEmpty(){
    const directionsContainer = document.getElementById('editDirectionsContainer')
    if(!template) return null

    const fragment = template.content.cloneNode(true)
    const clone = fragment.firstElementChild

    directionsCounter++

    //Replicar la limpieza dinámica a los nuevos inputs
    cleanInputs(clone)

    directionsContainer.appendChild(clone)
}

//Funcionalidad de boton '+'
document.getElementById('editAddDirectionFieldsButton').addEventListener('click', () => {
    cloneDirectionFields()
})

//Asignar evento click a los botones de edición de la tabla
$(document).on('click', '.btnEdit', async function (e){
    directionsCounter = 0

    const clientId = this.getAttribute('data-id')
    const urlEdit = `clients/${clientId}`

    //agregar id en data-id del form
    form.setAttribute('data-id', clientId)

    axios.get(urlEdit)
        .then(response => {
            //DATOS TITULAR
            document.getElementById('edit-namesLastnames').value = response.data.current_titular.names_lastnames
            document.getElementById('edit-dni').value = response.data.current_titular.dni
            document.getElementById('edit-datebirth').value = response.data?.datebirth
            document.getElementById('edit-phoneNumber').value = response.data?.phone_number || ''
            document.getElementById('edit-grade').value = response.data?.grade || ''
            // ✅ Preseleccionar ocupación en Select2
            if (response.data.occupation) {
                let occupationName = response.data.occupation.name;
                let occupationId = response.data.occupation.id;

                // Crear y seleccionar la opción en Select2
                let option = new Option(occupationName, occupationId, true, true);
                $('#edit-occupation').append(option).trigger('change');
            } else {
                $('#edit-occupation').val(null).trigger('change'); // Si no hay ocupación, limpiar
            }

            originalData = {
                names_lastnames: response.data.current_titular.names_lastnames,
                dni: response.data.current_titular.dni,
                datebirth: response.data?.datebirth || '',
                phone_number: response.data?.phone_number || '',
                grade: response.data?.grade || '',
                occupation: response.data?.occupation?.id || ''
            }

            //DATOS DIRECCIONES
            const directionsContainer = document.getElementById('editDirectionsContainer')
            directionsContainer.innerHTML=''

            let directions = []

            response.data.directions.forEach((direction, index) => {
                const clone = cloneDirectionFields()                

                if(clone){
                    clone.querySelector('[name="direction_ids[]"]').value = direction.id
                    clone.querySelector('[name="directions[]"]').value = direction.name
                    clone.querySelector('[name="zone_id[]"]').value = direction.zone_id
                    clone.querySelector('[name="cant_beneficiaries[]"]').value = direction.cant_beneficiaries
                    clone.querySelector('[name="permanence[]"]').value = direction.permanence
                    clone.querySelector('[name="material[]"]').value = direction.material
                    clone.querySelector('[name="drains[]"]').value = direction.drains

                    directions.push({
                        id: direction.id,
                        name: direction.name,
                        zone_id: direction.zone_id,
                        cant_beneficiaries: direction.cant_beneficiaries,
                        permanence: direction.permanence,
                        material: direction.material,
                        drains: direction.drains,
                    })

                    // Deshabilitar botón si contracted === 1
                    if (direction.contracted === 1) {
                        const removeButton = clone.querySelector('.removeDirectionFieldsContainer .btn');
                        if (removeButton) {
                            removeButton.disabled = true;   // agrega el atributo disabled
                            removeButton.classList.add('opacity-50'); // opcional: estilo visual
                        }
                    }
                }
            })
            originalData = {
                ...originalData,
                directions: directions
            }
            console.log('originalData:', originalData)

            //Activar boton submit si form cambia
            enableIfChange(form)

            $('#editModal').modal('show')
        })
        .catch(error => {
            Swal.fire('Error', 'No se pudo cargar la información del cliente', 'error');
            console.error(error)
        })
})

//Enviar datos
form.addEventListener('submit', (e) => {
    e.preventDefault()
    const form = e.target
    const formData = new FormData(form);
    const clientId = form.dataset.id
    formData.append('_method', 'PATCH')

    Swal.fire({
        title: 'Guardando...',
        text: 'Por favor, espera mientras guardamos los datos.',
        allowOutsideClick: false,
        onOpen: () => {
            Swal.showLoading();
        }
    });

    axios.post(`${url}/${clientId}`, formData)
        .then(response => {
            Swal.close();            
            Swal.fire({
                title: 'Éxito',
                text: response.data.message, // Axios ya parsea JSON
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = url
            });
        })
        .catch(error => {
            Swal.close();

            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar los datos. Por favor, revisa los campos.',
                icon: 'error',
                confirmButtonText: 'OK'
            });

            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;

                showErrors(errors, 'edit')
            }
        });
});

//Limpiar inputs al ingresar datos
cleanInputs(form)

//Limpiar modal
$('#editModal').on('hidden.bs.modal', () => {
    //volver a bloquear datos de titular
    document.getElementById('edit-namesLastnames').disabled = true
    document.getElementById('edit-dni').disabled = true

    //volver a deshabilitar submit
    const submitBtn = form.querySelector('button[type="submit"]')
    submitBtn.disabled = true

    resetModal('editModal')

    //retornar stepper a 1 al cerrar modal
    stepper2.to(1)

    // Limpiar direcciones dinámicas: dejar solo el primer .directionFields
    const directionsContainer = document.getElementById('editDirectionsContainer');
    const allDirectionFields = directionsContainer.querySelectorAll('.directionFields');

    if (allDirectionFields.length > 1) {
        // Mantener el primero y eliminar el resto
        allDirectionFields.forEach((field, index) => {
            if (index > 0) field.remove();
        });
    }
});