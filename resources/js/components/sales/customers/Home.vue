<template>
    <div class="container-xxl flex-grow-1 container-p-y">
      <h4 class="fw-bold py-3 mb-4"> Clientes <span class="badge bg-label-primary me-1 cursor-pointer" @click="openCustomerModal(1)">+</span></h4>
      <div class="row">
        <div class="col-xl-12 col-lg-12">
          <div class="card">
            <h5 class="card-header">Directorio de Clientes</h5>
            <div class="table-responsive text-nowrap">
              <table class="table">
                <thead>
                  <tr>
                    <th>Nombre</th>
                      <th>Celular</th>
                      <th>Correo</th>
                      <th>Universidad</th>
                      <th>Carrera</th>
                      <th>Estado</th>
                      <th>Opciones</th>
                  </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  <tr v-for="customer in customersPag">
                    <td><strong>{{customer.name}}</strong></td>
                      <td>{{customer.cell}}</td>
                      <td>{{customer.email}}</td>
                      <td>{{customer.university}}</td>
                      <td>{{customer.career}}</td>
                      <td>{{ status[customer.status] }}</td>
                      <td>
                        <button v-if="customer.status == null" @click="reactivateCustomer(customer.id)" class="btn btn-success btn-sm me-1">
                          <i class='bx bx-recycle'></i>
                        </button>
                        <button @click="openCustomerModal(2, customer)" class="btn btn-success btn-sm">
                          <i class='bx bx-edit'></i>
                        </button>
                      </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="row ps-4 pt-3">
                <ul class="pagination">
                  <!-- <li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="previous" tabindex="0" class="page-link">Anterior</a></li> -->
                  <li class="paginate_button page-item" :id="`li`+index" v-for="(chunk, index) in customersChunked"><a @click="stepPag(index)" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0" class="page-link">{{ index+1 }}</a></li>
                  <!-- <li class="paginate_button page-item next" id="DataTables_Table_0_next"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="next" tabindex="0" class="page-link">Siguiente</a></li> -->
                </ul>
              </div>
          </div>
        </div>
      </div>
      <customerModal :customer="customer_selected" :action="action" @getAllCustomers="getAllCustomers"/>
    </div>
</template>
<script>
import customerModal from './customerModal.vue'

  export default{
    components:{ customerModal },
    data(){
      return{
        customers:[],
        customersPag: [],
        customersChunked: [],
        customer_selected:{},
        action: 1,
        status:{
          0: 'No atendido',
          1: 'Atendido',
          2: 'Comunicaci??n establecida',
          3: 'Lead',
          4: 'Interesado',
          5: 'Altamente Interesado',
          6: 'Cliente',
          null: 'Stand By'
        }
      }
    },
    methods:{
      stepPag(i){
        this.customersPag = this.customersChunked[i]
        document.querySelectorAll('.paginate_button').forEach(el =>{
          el.classList.remove('active')
        })
        document.getElementById('li'+i).classList.add('active')
      },
      openCustomerModal(action, customer){
        this.action = action
        this.customer_selected = customer
        $('#customerModal').modal('show')
      },
      reactivateCustomer(id){
        axios.get('/api/reactivateCustomer/'+id)
        .then(res => {
          this.customers = res.data
          this.getAllCustomers()
        })
      },
      getAllCustomers(){
        axios.get('/api/getAllCustomers')
        .then(res => {
          this.customers = res.data
          var i = 0
          var cant = 10
          this.customers.forEach(customer => {
            if(this.customers.slice(i, i+cant).length > 0){
              this.customersChunked.push(this.customers.slice(i, i+cant))
            }
            
            i = i+cant
          })
         
          this.customersPag  = this.customersChunked[0]
        })
      }
    },
    mounted(){
      this.getAllCustomers()
    }
  }
</script>