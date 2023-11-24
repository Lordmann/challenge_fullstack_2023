<template>
   <div>
       <h2> Organism list ({{ totalOrganisms }}) </h2>

       <table class="pure-table pure-table-horizontal">
           <thead>
           <tr>
               <th>Genus</th>
               <th>Species</th>
               <th>#Samples</th>
           </tr>
           </thead>
           <tbody>
           <tr class="loader-wrap" v-show="loading">
               <td colspan="3">
                   <span class="loader"></span>
               </td>
           </tr>
           <template v-for="organism in organisms">
                <organism
                    :genus="organism.genus"
                    :species="organism.species"
                    :samples_count="organism.samples_count"
                ></organism>
           </template>
           </tbody>
       </table>

       <div class="pagination" :class="{disabled: loading}">
           <button @click="previousPage" :disabled="page === 1"> << </button>
           <input @change="changePage" type="number" v-model="page"/>
           <button @click="nextPage" :disabled="page === maxPages"> >> </button>
       </div>
   </div>
</template>


<script lang="ts">

import {Vue, Component} from 'vue-property-decorator';
import axios from 'axios';
import Organism from "./organism.vue";

/**
 * Type of the sample
 */
type OrganismT = {
    genus: string;
    species: string;
    samples_count: number;
}

@Component({
    components: {Organism}
})
export default class OrganismsVue extends Vue {

    organisms: OrganismT[] = [];
    page: number = 1;
    maxPages: number = 1;
    totalOrganisms: number = 0;
    loading: boolean = false;

    mounted(){
        this.fetchOrganisms();
    }

    changePage(){
        if(this.page < 1){
            this.page = 1;
        }else if(this.page > this.maxPages){
            this.page = this.maxPages;
        }
        this.fetchOrganisms();
    }

    previousPage(){
        if(this.page > 1){
            this.page--;
            this.fetchOrganisms();
        }
    }

    nextPage(){
        if(this.page !== this.maxPages){
            this.page++;
            this.fetchOrganisms();
        }
    }

    /**
     * Recieve the samples from the api endpoint
     */
    fetchOrganisms(){
        console.log(this.page);
        this.loading = true;
        axios.get('/api/organisms?page=' + this.page).then(response => {
            this.loading = false;
            this.totalOrganisms = response.data.total;
            this.maxPages = response.data.last_page;
            this.organisms = response.data.data;
        });
    }
}
</script>

<style lang="scss">
    .loader-wrap{
        text-align: center;

        .loader {
            box-shadow: 0 0 20px black;
            width: 48px;
            height: 48px;
            border: 5px solid #FFF;
            border-bottom-color: #FF3D00;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    }

    .pagination{
        display: flex;

        &.disabled{
            pointer-events: none;
            opacity: 0.5;
        }

        input{
            text-align: center;
        }

        button{
            background-color: rgb(0, 120, 231);
            color: #fff;

            &[disabled]{
                opacity: 0.5;
            }
        }
    }
</style>
