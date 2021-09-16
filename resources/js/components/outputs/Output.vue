<template>
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
        {{ softland_user }}
        <h3>Salida ({{ output.DOCUMENTO_INV }})</h3>
      </div>
      <div class="col-sm-6">
        <!-- Print Report Button -->
        <form action="/reports" method="POST" class="d-inline float-right mx-2" target="_blank">
          <input type="hidden" name="_token" :value="csrf">
          <input type="hidden" name="report" value="/reports/StockSearch/outputs/output/output">
          <input type="hidden" name="documento_inv" :value="output.DOCUMENTO_INV">
          <input type="hidden" name="softland_user" :value="softland_user">
          <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="fas fa-print"></i></button>
        </form>

        <button type="button" class="btn btn-outline-secondary btn-sm float-right" v-if="!isLock" @click="toggleApprove('approve')"><i class="fas fa-check"></i> Aprobar</button>
        <button type="button" class="btn btn-outline-secondary btn-sm float-right" v-else @click="toggleApprove('disapprove')"><i class="fas fa-ban"></i> Desaprobar</button>
        
      </div>
    </div>
    <hr>
    <div class="row"> 
      <div class="col-sm-6">
      <table>
          <tbody>
            <tr>
              <td><strong>Documento No.: </strong></td>
              <td>{{ output.DOCUMENTO_INV }}</td>
            </tr>
            <tr>
              <td><strong>Fecha Doc.: </strong></td>
              <td>{{ output.FECHA_DOCUMENTO }}</td>
            </tr>
            <tr>
              <td><strong>Referencia</strong></td>
              <td>{{ output.REFERENCIA }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-sm-6">
        <table>
          <tbody>
          <tr>
              <td><strong>Usuario: </strong></td>
              <td>{{ output.USUARIO }}</td>
            </tr>
            <tr>
              <td><strong>Usuario Aprobaci&oacute;n: </strong></td>
              <td>{{ output.USUARIO_APRO }}</td>
            </tr>
            <tr>
              <td><strong>Fecha Aprobaci&oacute;n: </strong></td>
              <td>{{ output.FECHA_HORA_APROB }}</td>
            </tr>
          </tbody>
        </table>
        <!-- Output lines -->
      </div>
      <outputslines-component :documento_inv="output.DOCUMENTO_INV" :paquete_inv="output.PAQUETE_INVENTARIO" :softland_user="softland_user" :approved="output.APROBADO"></outputslines-component>
    </div>
  </div>
</template>

<script>
export default {
  /**
   * Vue cycle functions
   */
  created() {
    
  },
  /**
   * Data
   */
  data() {
    return {
      csrf: document.querySelector('meta[name=_token]') ?
        document.querySelector('meta[name=_token]').content : null
    }
  },
  /**
   * Computed Props
   */
  computed: {
    isLock() {
      return (this.output.APROBADO === 'S') ? true : false
    }
  },
  /**
   * Props
   */
  props: ['output', 'softland_user'],
  /**
   * Methods
   */
  methods: {
    async toggleApprove(action) {
      if (confirm('¿Ejecutar Acción?')) {
        try {
          let res = await axios.post('/outputsapprove/'+ this.output.DOCUMENTO_INV, {
            action: action,
            paquete_inv: this.output.PAQUETE_INVENTARIO,
            softland_user: this.softland_user
          })
          
          if (res.status === 200) {
            alert(res.data)
            location.reload()
          }
        } catch (error) {
          console.log(error)
        }
      }
    },
    // Print Output
    printOutput() {
      
    }
  }
}
</script>