<template>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold">Teams <span @click="changeVisibility" class="badge bg-label-primary me-1 cursor-pointer">+</span></h4>
        <input v-if="visible" class="form-control w-25 mb-3" type="text" placeholder="Nombre del equipo"/>
        <div class="row">
            <div class="col-md-4" v-for="team in teams">
                <div class="card bg-primary text-white mb-3">
                    <div class="card-body">
                        <h5 class="card-title text-white">Equipo {{team.name}}</h5>    
                        <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                            <div v-for="memoir in team.memoirs">
                                <li  class="avatar avatar-sm pull-up" :title="memoir.user.name">
                                <span class="avatar-initial rounded-circle bg-primary">{{ memoir.user.name[0] }}</span>
                                </li>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';

export default {
    data(){
        return{
            visible: false,
            teams: []
        }
    },
    methods:{
        changeVisibility(){
            this.visible =!this.visible;
        },
        getAllTeams(){
            axios.get('/api/getAllTeams')
            .then(res =>{
                this.teams = res.data
            })
            .catch(err =>{
                console.log(err)
            })
        }
    },
    mounted(){
        this.getAllTeams()
    }
}
</script>