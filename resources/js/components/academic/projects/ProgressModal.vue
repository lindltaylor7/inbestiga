<template>
    <div class="modal fade" id="progressModal" tabindex="-1" aria-hidden="true" ref="progressModal">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">Registro de Progreso de {{ activity.title }}</h5>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
                id="close-progress-modal"
            ></button>
            </div>
            <div class="modal-body">
                <form @submit="updateProgress">
                    <div class="row">
                        <p>Agrega las indicaciones iniciales para iniciar el proyecto</p>
                        <div class="col mb-3">
                        <label for="nameBasic" class="form-label">Indicaciones</label>
                        <input type="text" v-model="comment" class="form-control" required/>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Confirmar"/>
                </form>
            </div>    
            <div class="modal-footer">
            </div>
        </div>
        </div>
    </div>
</template>
<script>
    export default {
        props:{
            activity: Object,
            project_selected: Object
        },
        data(){
            return{
                comment: ''
            }
        },
        methods:{  
            updateProgress(e){
                e.preventDefault()
                const fd = new FormData()
                fd.append('id', this.activity.id)
                fd.append('comment',this.comment)
                fd.append('project_id', this.project_selected.id)

                axios.post('/api/updateProgress', fd)
                .then(res =>{
                    console.log(res)
                    this.$emit('getActivities')
                    document.getElementById('close-progress-modal').click()
                    this.comment = ''
                })
                .catch(err =>{
                    if(err.response){
                        console.log(err.response.data)
                    }
                })
            }
        }
    }
</script>