<template>
  <div class="container mt-4">
    <div class="row">
      <div class="col-sm-12">
      <div class="card">
        <div class="card-header bg-dark text-white">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="d-inline">Lineas De Documento</h5> <sup><span class="badge badge-secondary">{{ lines.length }}</span></sup>
            </div>
            <div class="col-sm-6">
              <div class="dropdown" v-if="!isLock">
                <button class="btn btn-secondary btn-sm dropdown-toggle float-right" type="button" id="dropdown_menu_id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-cog"></i> Opciones de Linea
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdown_menu_id">
                  <button type="button" class="dropdown-item" @click="showNewOutputModal"><i class="fas fa-plus-circle"></i> Nueva Linea</button>
                </div>
              </div>
              <div class="text-danger d-inline float-right" v-else>APROBADO</div>
              <button type="button" class="btn btn-link float-right" @click="toggleSearch"><i class="fas fa-search float-right" v-show="!searching"></i></button>
              <div class="input-group col-sm-7 fade-transition" v-show="searching">
                <input type="text" class="form-control form-control-sm" placeholder="Filtrar Linea" v-model="criteria">
                <div class="input-group-append">
                  <span class="input-group-text" @click="toggleSearch"><i class="fas fa-times"></i></span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <table class="table table-sm table-striped table-bordered m-0" v-if="lines.length > 0">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Descripci&oacute;n</th>
                    <th>Bodega</th>
                    <th>Art&iacute;culo</th>
                    <th>Localizaci&oacute;n</th>
                    <th>Cant. Almac&eacute;n</th>
                    <th v-if="!isLock"><i class="fas fa-cog"></i></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(line, index) in filteredLines" :key="index">
                    <td>{{ line.LINEA_DOC_INV }}</td>
                    <td>{{ line.DESCRIPCION }}</td>
                    <td>{{ line.BODEGA }}</td>
                    <td>{{ line.ARTICULO }}</td>
                    <td>{{ line.LOCALIZACION }}</td>
                    <td>{{ line.CANTIDAD }}</td>
                    <td v-if="!isLock">
                      <button type="button" class="btn btn-sm btn-danger" @click="deleteLine(line, index)"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            <div class="p-5 text-center" v-else><h2 class="text-muted">No hay Lineas</h2></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="new_line" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="#">Agregar Nueva Linea</h5>
            <loading-component />
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row">
                <div class="col-sm-12">
                  <errors-component></errors-component>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <!-- centro_costo -->
                  <div class="form-group">
                    <label for="centro_costo">Centro de Costo: </label>
                      <multi-select v-model="selectedCostCenter" :options="costCenters" label="DESCRIPCION"></multi-select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <!-- bodega -->
                  <div class="form-group">
                    <label for="bodega">Bodega: </label>
                    <multi-select v-model="selectedWarehouse" :options="warehouses" label="NOMBRE" :customLabel="customBodegaLabel" @input="fillLocations"></multi-select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <!-- localizacion -->
                  <div class="form-group">
                    <label for="bodega">Localizaci&oacute;n: </label>
                    <multi-select v-model="selectedLocation" :options="locations" label="DESCRIPCION"></multi-select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <!-- articulo -->
                  <div class="form-group">
                    <label for="articulo">Articulo: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="articulo" placeholder="Repuesto" disabled :value="selectedItem.DESCRIPCION">
                      <div class="input-group-append">
                        <button type="button" class="btn btn-secondary" @click="showSearchItem"><i class="fas fa-plus-circle"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="card" v-show="showFindItem">
                    <div class="card-header">
                      <div class="input-group">
                        <input type="text" class="form-control d-inline" v-model="itemCriteria" @keydown.enter="searchItem" placeholder="Buscar repuesto">
                        <div class="input-group-append">
                          <button type="text" class="btn btn-secondary d-inline" @click="searchItem"><i class="fas fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                    <div class="card-body m-0 p-0" style="max-height: 200px; overflow: scroll;">
                      <table class="table table-striped table-bordered table-sm">
                        <thead>
                          <tr>
                            <th>C&oacute;digo Softland</th>
                            <th>Descripci&oacute;n \ No Parte</th>
                            <th>Original / After Market</th>
                            <th><i class="fas fa-check"></i></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(item, index) in items" :key="index">
                            <td>{{ item.ARTICULO }}</td>
                            <td>{{ item. DESCRIPCION }}</td>
                            <td v-if="(item.CLASIFICACION_2 === '02-01')">Original</td>
                            <td v-else-if="(item.CLASIFICACION_2 === '02-02')">After Market</td>
                            <td v-else>No Definido</td>
                            <td><button type="button" class="btn btn-outline-secondary btn-sm" @click="itemSelection(item)"><i class="fas fa-check"></i></button></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <!-- cantidad -->
                  <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" class="form-control"  step=".000001" min="0" max="999999" v-model="quantity" placeholder="Cantidad">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="clearFields"><i class="fas fa-ban"></i> Cancelar</button>
            <button type="button" class="btn btn-secondary" @click="addLine"><i class="fas fa-check"></i> Agregar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import MultiSelect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'

export default {
  /** Components */
  components: {
    'multi-select': MultiSelect
  },
  /**
   * Vue Application flow functions
   */
  created() {
    this.fillLines()
    this.fillCostCenters()
    this.fillWareHouses()
  },
  /**
   * Data
   */
  data() {
    return {
      lines: [],
      costCenters: [],
      warehouses: [],
      locations: [],
      items: [],
      selectedCostCenter: '',
      selectedWarehouse: '',
      selectedLocation: '',
      selectedItem: {
        ARTICULO: '',
        DESCRIPCION: ''
      },
      quantity: 0,
      showFindItem: false,
      searching: false,
      itemCriteria: '',
      criteria: ''
    }
  },
  /**
   * Computed properties
   */
  computed: {
    // Filter line items
    filteredLines() {
      return this.lines.filter(fn => {
        return (fn.DESCRIPCION.trim().toLowerCase().indexOf(this.criteria.trim()
          .toLowerCase()) !== -1) ||
        (fn.LOCALIZACION.trim().toLowerCase().indexOf(this.criteria.trim()
          .toLowerCase()) !== -1) ||
        (fn.BODEGA.trim().toLowerCase().indexOf(this.criteria.trim()
          .toLowerCase()) !== -1) ||
        (fn.ARTICULO.trim().toLowerCase().indexOf(this.criteria.trim()
          .toLowerCase()) !== -1)
      })
    },
    // Check if document is locked
    isLock() {
      if (this.approved === 'S') {
        return true
      } else if (this.approved === null) {
        return false
      }
    }
  },
  /**
   * Props
   */
  props: ['documento_inv', 'paquete_inv', 'softland_user', 'approved'],
  /**
   * Methods
   */
  methods: {
    // Get Articulos
    async getArticulos() {
      let res = await axios.get('/articulos')
      if (res.status == 200) {
        this.articulos = res.data
      } else {
        alert('No data')
      }
    },
    // Get output lines
    async fillLines() {
      try {
        let res = await axios.get('/outputlines/'+ this.documento_inv)

        if (res.status == 200) {
          this.lines = res.data
        }
      } catch (er ) {
        console.log(er)
        alert(JSON.stringify(er))
      }
    },
    // Show modal
    showNewOutputModal() {
      $('#new_line').modal('show')
    },
    // Hide modal
    hideNewOutputModal() {
      $('#new_line').modal('hide')
    },
    // Show lines filter
    toggleSearch() {
      this.criteria = ''
      this.searching = !this.searching
    },
    // Add new Line
    async addLine() {
      try {
        let res = await axios.post('/outputlines', {
          paquete_inventario: this.paquete_inv,
          documento_inv: this.documento_inv,
          articulo: this.selectedItem.ARTICULO,
          bodega: this.selectedWarehouse.BODEGA,
          localizacion: this.selectedLocation.LOCALIZACION,
          cantidad: this.quantity,
          centro_costo: this.selectedCostCenter.CENTRO_COSTO,
          softland_user: this.softland_user
        })

        if (res.status === 200) {
          console.log(res.data)
          this.fillLines()
          this.clearFields()
          this.hideNewOutputModal()
        }
      } catch (error) {
        console.log(error)
      }
    },
    // Delete lines methods
    async deleteLine(item, index) {
      if (confirm('¿Desea borrar esta linea?')) {
        try {
          let res = await axios.delete('/outputlines', {
            data: {
              articulo: item.ARTICULO,
              bodega: item.BODEGA,
              cantidad: item.CANTIDAD,
              linea: item.LINEA_DOC_INV,
              documento_inv: item.DOCUMENTO_INV,
              localizacion: item.LOCALIZACION,
              paquete_inventario: item.PAQUETE_INVENTARIO
            }
          })

          if (res.status == 200) {
            this.lines.splice(index, 1);
          } else {
            alert(JSON.stringify(res.data))
          }
        } catch (error) {
          console.log(error)
        }
      }
    },
    // Fill Warehouse
    async fillWareHouses() {
      try {
        let res = await axios.get('/warehouses')
        if (res.status === 200) {
          this.warehouses = res.data
        }
      } catch (error) {
        console.log(error)
      }
    },
    // Fill Locations
    async fillLocations() {
      try {
        let res = await axios.get('/locations/'+ this.selectedWarehouse.BODEGA)

        if (res.status === 200) {
          this.locations = res.data
        }
      } catch (error) {
        console.log(error)
      }
    },
    // Fill cost centers
    async fillCostCenters() {
      try {
        let res = await axios.get('/costcenters/')

        if (res.status === 200) {
          this.costCenters = res.data
        }
      } catch (error) {
        console.log(error)
      }
    },
    // Show search and pick item
    showSearchItem() {
      this.itemCriteria = '',
      this.showFindItem = true
    },
    // Seatch item
    async searchItem() {
      try {
        let res = await axios.get('/articulos_custom_search', {
          params: {
            criteria: this.itemCriteria
          }
        })

        if (res.status === 200) {
          this.items = res.data
        }
      } catch (error) {
        console.log(error)
      }
    },
    // Select item after search
    itemSelection(item) {
      this.selectedItem = item
      this.showFindItem = false
      this.itemCriteria = ''
    },
    // Clear form fields
    clearFields() {
      this.selectedCostCenter = ''
      this.selectedWarehouse = ''
      this.selectedLocation = ''
      this.selectedItem = {
        ARTICULO: '',
        DESCRIPCION: ''
      }
      this.quantity = 0
      this.items = []
    },
    // Custom label for vue-multiselect bodega
    customBodegaLabel({BODEGA, NOMBRE}) {
      return `${BODEGA}. ${NOMBRE}`
    }
  }
}
</script>

<style>
  .fade-transition {
    transition: all 1s ease-in-out !important;
  }
</style>