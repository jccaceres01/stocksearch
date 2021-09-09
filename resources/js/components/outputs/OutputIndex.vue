<template>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
      <div class="card">
        <div class="card-header bg-dark text-white">
          <div class="row">
            <div class="col-sm-6">
            <h5 class="d-inline">
              Paquete de Salidas:
              <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-secondary">{{ selectedPackage }}</button>
                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" v-for="(item, index) in outputPackages" :key="index" @click=pickPackage(item)>{{ item }}</a>
                </div>
              </div>
            </h5>
            </div>
            <div class="col-sm-6">
              <new-output-component :softland_user="softland_user"></new-output-component>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <ul class="nav nav-tabs" id="navtab-id" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab1-name" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-check-circle text-success"></i> Aprobadas <sup><span class="badge badge-secondary">{{ approvedOutputs.length }}</span></sup></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab2-name" role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-check-circle text-danger"></i> No Aprobadas <sup><span class="badge badge-secondary">{{ disapprovedOutputs.length }}</span></sup></a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab1-name" role="tabpanel" aria-labelledby="home-tab">
              <div v-if="approvedOutputs.length > 0">
                <!-- Add Content bellow -->
                <div class="card">
                  <div class="card-header bg-dark text-light rounded-0">
                    <div class="row">
                      <div class="col-sm-6">
                        <span class="text-muted">Lista de Salidas aprobadas</span>
                      </div>
                      <div class="col-sm-6">
                        <div class="dropdown float-right">
                          <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdown_aprobados_id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cog"></i> Opciones
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdown_aprobados_id">
                            <button class="dropdown-item" type="button" @click="toggleApprove('disapprove')">Desaprobar seleccionados</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body m-0 p-0">
                    <table class="table table-sm table-striped table-bordered m-0">
                      <thead>
                        <tr>
                          <th><i class="fas fa-check"></i></th>
                          <th>Estatus</th>
                          <th>Fecha Doc.</th>
                          <th>Documento</th>
                          <th>Usario</th>
                          <th>Referencia</th>
                          <th>Fecha de Aprovaci&oacute;n</th>
                          <th>Usuario Aprovaci&oacute;n</th>
                          <th><i class="fas fa-cog"></i></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(approved, index) in approvedOutputs" :key="index">
                          <td>
                            <input type="checkbox" :checked="isSelected(approved.SELECCIONADO)" @click="toggleSelect(approved)">
                          </td>
                          <td>
                            <span v-if="approved.APROBADO === null"> <i  class="fas fa-check-circle text-danger"></i> No Aprobado</span>
                            <span v-else-if="approved.APROBADO === 'S'"><i  class="fas fa-check-circle text-success"></i> Aprobado</span>
                            <span v-else><i class="fas fa-check-circle text-warning"></i> No Definido </span>
                          </td>
                          <td>{{ approved.FECHA_DOCUMENTO }}</td>
                          <td>{{ approved.DOCUMENTO_INV }}</td>
                          <td>{{ approved.USUARIO }}</td>
                          <td>{{ approved.REFERENCIA }}</td>
                          <td v-if="approved.USUARIO_APRO !== null">{{ approved.FECHA_HORA_APROB }}</td>
                          <td v-else>-</td>
                          <td v-if="approved.USUARIO_APRO !== null">{{ approved.USUARIO_APRO }}</td>
                          <td v-else>-</td>
                          <td>
                            <a :href="'/outputs/'+ approved.DOCUMENTO_INV" class="btn btn-secondary btn-sm" title="ver" data-toggle="tooltip" data-placement="top"><i class="fas fa-eye"></i></a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                
              </div>
              <div v-else class="p-5 text-center"><h2 class="text-muted">No hay documentos</h2></div>
            </div>
          
            <div class="tab-pane fade" id="tab2-name" role="tabpanel" aria-labelledby="profile-tab">
              <div v-if="disapprovedOutputs.length > 0">
                  <!-- Add Content bellow -->
                  <div class="card">
                    <div class="card-header bg-dark text-light rounded-0">
                      <div class="row">
                        <div class="col-sm-6">
                          <span class="text-muted">Lista de Salidas aprobadas</span>
                        </div>
                        <div class="col-sm-6">
                          <div class="dropdown float-right">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdown_desaprobados_id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fas fa-cog"></i> Opciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdown_desaprobados_id">
                              <button class="dropdown-item" type="button" @click="toggleApprove('approve')">Aprobar seleccionados</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-body m-0 p-0">
                      <table class="table table-sm table-striped table-bordered m-0">
                        <thead>
                          <tr>
                            <th><i class="fas fa-check"></i></th>
                            <th>Estatus</th>
                            <th>Fecha Doc.</th>
                            <th>Documento</th>
                            <th>Usario</th>
                            <th>Referencia</th>
                            <th>Fecha de Aprovaci&oacute;n</th>
                            <th>Usuario Aprovaci&oacute;n</th>
                            <th><i class="fas fa-cog"></i></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(disApproved, index) in disapprovedOutputs" :key="index">
                            <td>
                            <input type="checkbox" :checked="isSelected(disApproved.SELECCIONADO)" @click="toggleSelect(disApproved)">
                          </td>
                            <td>
                              <span v-if="disApproved.APROBADO === null"><i  class="fas fa-check-circle text-danger"></i> No Aprobado</span>
                              <span v-else-if="disApproved.APROBADO === 'S' "><i class="fas fa-check-circle text-success"></i> Aprobado</span>
                              <span v-else> <i  class="fas fa-check-circle text-warning"></i> No Definido </span>
                            </td>
                            <td>{{ disApproved.FECHA_DOCUMENTO }}</td>
                            <td>{{ disApproved.DOCUMENTO_INV }}</td>
                            <td>{{ disApproved.USUARIO }}</td>
                            <td>{{ disApproved.REFERENCIA }}</td>
                            <td v-if="disApproved.USUARIO_APRO !== null">{{ disApproved.FECHA_HORA_APROB }}</td>
                              <td v-else>-</td>
                              <td v-if="disApproved.USUARIO_APRO !== null">{{ disApproved.USUARIO_APRO }}</td>
                              <td v-else>-</td>
                            <td>
                              <a :href="'/outputs/'+ disApproved.DOCUMENTO_INV" class="btn btn-secondary btn-sm" title="ver" data-toggle="tooltip" data-placement="top"><i class="fas fa-eye"></i></a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              <div v-else class="p-5 text-center"><h2 class="text-muted">No hay documentos</h2></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  /**
   * Vue cycle methods
   */
  created() {
    // Fill outputs
    this.fillOutputsList()
  },
  /**
   * Data
   */
  data() {
    return {
      approvedOutputs: [],
      disapprovedOutputs: [],
      outputPackages: ['SAL', 'SAL1'],
      selectedPackage: 'SAL'
    }
  },
  /**
   * Properties
   */
  props: ['softland_user'],
  /**
   * Methods
   */
  computed: {

  },
  methods: {
    async fillOutputsList() {
      try {
        let res = await axios.get('/outputs/'+ this.selectedPackage)
        if (res.status === 200) {
          this.approvedOutputs = res.data.approvedOutputs
          this.disapprovedOutputs = res.data.disapprovedOutputs
        }
      } catch (error) {
        console.log(error)
      }
    },
    // Pick a package
    pickPackage(item) {
      this.selectedPackage = item
      this.fillOutputsList()
    },
    // Checked comversor
    isSelected(value) {
      if (value === 'S') {
        return true
      } else if (value === 'N') {
        return false
      }
    },
    // Toggle selection
    async toggleSelect(output) {

      try {
        let res = await axios.post('/outputs/'+ output.DOCUMENTO_INV +'/toggle_selection', {
          documento_inv: output.DOCUMENTO_INV
        })

        if (res.status === 200) {
          output.SELECCIONADO = res.data
        }
      } catch (error) {
        console.log(error)
      }
    },
    // Toggle Approve
    async toggleApprove(action) {
      try {
        let res = await axios.post('outputsapprove', {
          action: action,
          paquete_inv: this.selectedPackage,
          softland_user: this.softland_user.USUARIO
        })
        
        if (res.status === 200) {
          alert(res.data)
          this.fillOutputsList()
        }
      } catch (error) {
        console.log(error)
      }
    }
  }
}
</script>