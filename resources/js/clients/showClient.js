$(document).on('click', '.btnShow', async function(e) {
    // e.stopPropagation()
    const clientId = this.getAttribute('data-id')
    const urlShow = `clients/${clientId}`

    axios.get(urlShow)
        .then(response => {
            document.getElementById('show_names_lastnames').innerText = response.data.current_titular.names_lastnames
            document.getElementById('show_dni').innerText = response.data.current_titular.dni || '--'
            document.getElementById('show_phone_number').innerText = response.data?.phone_number || '--'
            document.getElementById('show_datebirth').innerText = response.data?.datebirth || '--'
            document.getElementById('show_grade').innerText = response.data?.grade || '--'
            document.getElementById('show_occupation').innerText = response.data.occupation?.name || '--'

            if(response.data.other_titulars.length > 0){
                const showHistoryTitulars = document.getElementById('showModalTitulars')
                showHistoryTitulars.innerHTML = `
                    <hr>
                    <h5 class="text-center">Historial de titulares</h5>
                    <div style="overflow-x: auto">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Nombres y apellidos</th>
                                    <th scope="col">DNI</th>
                                    <th scope="col">Fecha de reasignación</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${response.data.other_titulars.map(extitular => `
                                    <tr>
                                        <td class="text-center">${extitular.names_lastnames}</td>
                                        <td class="text-center">${extitular.dni || '--'}</td>
                                        <td class="text-center">${extitular.updated_at}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `
            }else{
                document.getElementById('showModalTitulars').innerHTML = ''
            }

            if(response.data.directions.length > 0){
                const showDirecciones = document.getElementById('showModalDirections')
                showDirecciones.innerHTML = `
                    <hr>
                    <h5 class="text-center">Direcciones</h5>
                    <div style="overflow-x: auto">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Dirección</th>
                                    <th scope="col"># Benef.</th>
                                    <th scope="col">Barrio</th>
                                    <th scope="col">Con contrato</th>
                                    <th scope="col">Permanencia</th>
                                    <th scope="col">Material</th>
                                    <th scope="col">Sumideros</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${response.data.directions.map(direction => `
                                    <tr>
                                        <td class="text-center">${direction.name}</td>
                                        <td class="text-center">${direction.cant_beneficiaries || '--'}</td>
                                        <td class="text-center">${direction.zone.name}</td>
                                        <td class="text-center">
                                            ${direction.contracted ? 
                                                '<span class="badge bg-success mb-1">SI</span>' 
                                                : 
                                                '<span class="badge bg-danger mb-1">NO</span>'
                                            }
                                        </td>
                                        <td class="text-center">${
                                            direction.permanence
                                                ? direction.permanence + (direction.permanence < 2 ? ' año' : ' años')
                                                : '--'
                                            }
                                        </td>
                                        <td class="text-center">${direction.material || '--'}</td>
                                        <td class="text-center">
                                            ${
                                                direction.drains === true
                                                    ? '<span class="badge bg-success mb-1">SI</span>'
                                                    : direction.drains === false
                                                        ? '<span class="badge bg-danger mb-1">NO</span>'
                                                        : '--'
                                            }
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }else{
                document.getElementById('showModalDirections').innerHTML = '';
            }

            $('#showModal').modal('show');
        })
        .catch(error => {
            Swal.fire('Error', 'No se pudo cargar la información del cliente', 'error');
            console.error(error)
        })
});