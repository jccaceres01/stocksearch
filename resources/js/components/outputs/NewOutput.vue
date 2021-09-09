<template>
  <div>
    <!-- Toggle Modal button -->
    <button type="button" class="btn btn-secondary btn-sm float-right" title="Nueva Salida" data-toggle="tooltip" data-placement="top" id="showModal" @click="showDialog"><i class="fas fa-plus-circle"></i></button>
    <!-- Modal -->
    <div class="modal fade" id="new_sal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-dark d-inline">Nueva Salida <loading-component></loading-component></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Error -->
            <errors-component></errors-component>
            <!-- referencia -->
            <div class="form-group">
              <label for="referencia" class="text-dark">Referencia (N&uacute;mero Interno)</label>
              <input type="text" class="form-control form-control-sm" id="ni" v-model="newOutput.referencia" placeholder="N.I" style="text-transform: uppercase;" required>
            </div>
            <!-- paquete_inventario -->
            <div class="form-group">
              <label for="paquete_inventario" class="text-dark">Paquete de Salida</label>
              <select v-model="newOutput.paquete_inventario" class="form-control form-control-sm" required>
                <option value="SAL">SAL</option>
                <option value="SAL1">SAL1</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="clearInputs" data-dismiss="modal"><i class="fas fa-window-close"></i> Cancelar</button>
            <button type="submit" class="btn btn-secondary" @click="newOutputCreate"><i class="fas fa-plus-circle"></i> Crear Salida</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Inputmask  from 'inputmask'

export default {
  /**
   * Vue lifecycle functions
   */
  mounted() {
    let niEl = document.getElementById('ni')
    Inputmask({mask: '**-***'}).mask(niEl)
  },
  /**
   * Data
   */
  data() {
    return {
      newOutput: {
        referencia: '',
        paquete_inventario: '',
        softland_user: this.softland_user
      }
    }
  },
  /**
   * Props
   */
  props: ['softland_user'],
  /** Methods */
  methods: {
    // Clear new output fields
    clearInputs() {
      this.newOutput.referencia = ''
      this.newOutput.paquete_inventario = ''
    },
    showDialog() {
      $('#new_sal').modal('show')
    },
    // Create new output method
    async newOutputCreate() {
      if (this.newOutput.paquete_inventario.trim() !== '' || 
        this.newOutput.referencia.trim() !== '') {
          try {
            let res = await axios.post('/outputs', this.newOutput)
            if (res.status === 200) {
              window.location = '/outputs/'+ res.data.DOCUMENTO_INV
            }
          } catch (error) {
            console.log(error)
          }
        } else {
          alert('Debe llenar los campos')
        }
    }
  }
}
</script>