<template>
    <div class="col-3 rounded">
        <p class="text-dark fw-bold" style="font-size: 18px;"> <span class="text-primary">●</span> {{ title }}
            <span class="fw-bold text-primary bg-label-primary py-0 px-1 rounded-1" style="font-size: 12px;">{{
                entities.length
                }}</span>
        </p>
        <div :id="'draggableArea' + status" class="container-cards overflow-auto vh-100 w-100" @drop="drop"
            @dragenter.prevent @dragover.prevent>
            <template v-if="entities.length != 0">
                <template v-for="(entitie, index) in entities" :key="index">
                    <CardEntitie :entitie="entitie" :status="status" @showModalUpdateData="showModalUpdateData"
                        @updateInBd="updateInBd" />
                </template>
            </template>
        </div>
    </div>
</template>
<script>
import UpdateCom from '../prelead/UpdateCom.vue'
import CardCustomer from '../prelead/CardCustomer.vue'
import CardQuotation from './CardQuotation.vue'
import CardEntitie from './CardEntitie.vue'

export default {
    components: { CardCustomer, UpdateCom, CardQuotation, CardEntitie },
    data() {
        return {
            visible: false,
            comunication: {},
            customer: {}
        }
    },
    props: {
        title: String,
        bg: String,
        status: Number,
        entities: Array
    },
    methods: {
        updateInBd(quotationId, status) {
            this.$parent.updateInBd(quotationId, status);
        },
        getCustomers() {
            this.$emit('getAllCustomers');
        },
        updateStatusPrelead(customerId) {
            console.log(customerId, this.status);
            this.$emit('updateStatusPrelead', customerId, this.status)
        },
        transformLead(customerId) {
            console.log('transform in dragarea', customerId);
            this.$router.push({ name: 'home-quotation', params: { idCustomer: customerId } });
        },
        showModalFunnelCustomer(customer) {
            this.$emit('showModalFunnelCustomer', customer)
        },
        showModalQuotationFunnel(quotation) {
            this.$emit('showModalQuotationFunnel', quotation)
        },
        cleanLead(id) {
            var leadSelected = this.customers.find(customer => customer.id == id)
            leadSelected.status = 3
        },
        convertLead(id) {
            this.$emit('convertLead', id)
        },
        showModalUpdateCom(com) {
            this.$emit('callModal', com)
        },
        showModalUpdateData(customer) {
            this.$emit('showModalUpdateData', customer)
        },
        hide(e) {
            e.preventDefault()
            e.target.classList.remove('pt-20')
        },
        allowDrop(e) {
            e.preventDefault()
            e.target.classList.add('bg-hover')
        },
        drop(e) {
            e.preventDefault()

            var quotation = e.dataTransfer.getData("quot")
            var customer = e.dataTransfer.getData("customerId")
            var quotationId = e.dataTransfer.getData("quotationId")
            if (this.status == 5) {
                this.$emit('transformQuotation', customer)
            } else {
                if (customer) {
                    this.$emit('updateStatusSpace', customer, this.status)
                } else {
                    this.$emit('updateStatusSpace', quotationId, this.status)
                }
            }
        },
        updateStatusSpace(quotationId) {
            console.log('calling in draggablearea', quotationId)
            this.$emit('updateStatusSpace', quotationId, this.status)
            /* const index = this.customers.findIndex(c => c.id == id)
            this.customers[index].status = status
            axios.get(`/api/updateCustomerGrade/${id}/${status}`)
            .then(res =>{
                console.log(res.data)
            })
            .catch(err =>{
                console.log(err)
            }) */
        },
        updateStatus(id) {
            axios.get(`/api/updateCustomerGrade/${id}/${this.status}`)
                .then(res => {
                    console.log(res.data)
                })
                .catch(err => {
                    console.log(err)
                })
        },
        setProject(id) {
            console.log(id)

            const fd = new FormData()
            fd.append('id_customer', id)
            fd.append('emisor_id', this.store.authUser[0].id)
            axios.post(`/api/setProject`, fd)
                .then(res => {
                    console.log(res)
                })
                .catch(err => {
                    this.$swal(err.response.data.msg)
                })
        }
    }
}
</script>
<style scoped>
.pt-20 {
    padding-top: 20%;
}

.h-90 {
    height: 90%;
}

/* width */
::-webkit-scrollbar {
    width: 5px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #f1f1f1;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: rgba(67, 89, 113, 0.075);
    border-radius: 5px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: rgba(67, 89, 113, .7);
}
</style>
