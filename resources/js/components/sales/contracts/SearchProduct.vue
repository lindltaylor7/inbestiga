<template>
    <h5 class="mb-4">{{ title }}</h5>
    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            <button class="btn btn-primary btn-lg" @click="selectOption(1)">Fragmentado</button>
            <button class="btn btn-primary btn-lg ms-3" @click="selectOption(2)">Paquete</button>
        </div>
    </div>
    <div class="row" v-show="optionSelection == 1">
        <div class="col-6" >
            <label class="form-label" for="basic-default-company">Nivel</label>
            <select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example" v-model="level">
                <option selected="">Seleccione un nivel</option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>
        <div class="col-6 mt-4">
            <input type="text" class="form-control mt-1" v-model="search" @keyup="searchNewProduct">
            <ul class="list-group">
                <li @click="addCart(newProduct)" class="list-group-item d-flex justify-content-between align-items-center" v-for="newProduct in newProductsByName">
                {{ newProduct.name }}
                <span class="badge bg-primary">S./ {{ newProduct.newPriceSelected.price }}</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="row" v-show="optionSelection == 2">
        <div class="col-6" >
            <label class="form-label" for="basic-default-company">Nivel</label>
            <select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example" v-model="level">
                <option selected="">Seleccione un nivel</option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <div class="col-6 mt-4">
            <input type="text" class="form-control mt-1" v-model="search" @keyup="searchNewProduct">
            <ul class="list-group">
                <li @click="addCart(newProduct)" class="list-group-item d-flex justify-content-between align-items-center cursor-pointer" v-for="newProduct in newProductsByName">
                {{ newProduct.name }}
                <span class="badge bg-primary">S./ {{ newProduct.newPriceSelected.price }}</span>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
    export default{
        emits:['filterNewProducts', 'callProductModal'],
        data(){
            return{
                optionSelection: 0,
                level: 0,
                search: '',
                newProductsByName:[]
            }
        },
        props:{
            title: String,
            newProductsByType: Array
        },
        methods:{
            selectOption(value){
                this.optionSelection = value
                this.$emit('filterNewProducts', value)
            },
            searchNewProduct(){
                if(this.search != ''){
                    this.newProductsByName = this.newProductsByType.filter(product => product.name.toLowerCase().includes(this.search))
                    this.newProductsByName.forEach((product)=>{
                        product.newPriceSelected = product.newprices.find(price => price.level == this.level)
                    })
                }else{
                    this.newProductsByName = []
                }
            },
            addCart(newProduct){
                newProduct.level = this.level
                this.$emit('callProductModal', newProduct)
                this.newProductsByName = []
                this.search = ''
            }
        }
    }
</script>