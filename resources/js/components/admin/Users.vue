<template>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Usuarios <span class="badge bg-label-primary me-1 cursor-pointer" @click="addUser">+</span></h4>
        <div class="row">
            <div class="col-md-4" v-for="user in users">
                <div class="card bg-primary text-white mb-3">   
                    <div class="card-body">
                        <h5 class="card-title text-white">{{ user.name }}</h5>
                        <div v-if="user.roles[0]">
                            <p>{{ user.roles[0].name }}</p>
                        </div>
                        <button v-if="user.id != 1" @click="deleteUser(user.id)" class="btn btn-danger btn-sm"><i class='bx bx-trash'></i></button>
                    </div>
                </div>
            </div>
        </div>
        <InsertUser @getAllUsers="getAllUsers"/>
    </div>
</template>
<script>
import InsertUser from './InsertUser.vue'

export default {
    components:{InsertUser},
    data(){
        return{
            users: []
        }
    },
    methods:{
        getAllUsers(){
            axios.get('/api/getAllUsers')
            .then(res =>{
                this.users = res.data
            })
            .catch(err =>{
                console.log(err)
            })
        },
        addUser(){
            $('#userModal').modal('show')
        },
        deleteUser(id){
            this.$swal({
                  title: '¿Deseas eliminar a este usuario?',
                  icon: 'question',
                  confirmButtonText: 'Si',
                  showCancelButton: true,
                  cancelButtonText: 'No'
            })
            .then((result)=>{
                if(result.isConfirmed){
                    axios.get(`/api/deleteUser/${id}`)
                    .then(res =>{
                        this.getAllUsers()
                    })
                    .catch(err =>{
                        console.log(err)
                    })
                }else{
                    this.$swal.close()
                }
            })
            
        }
    },
    mounted(){
        this.getAllUsers()
    }
}
</script>