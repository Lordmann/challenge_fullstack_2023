<template>


   <div>
        <h2> Add New Organism </h2>

        <form class="pure-form pure-form-stacked" @submit.prevent="">
            <fieldset class="pure-group">
                <input
                    type="text"
                    v-model="genus"
                    placeholder="Genus"
                    class="pure-input-1"
                >
                <input
                    type="text"
                    v-model="species"
                    placeholder="Species"
                    class="pure-input-1"
                >
            </fieldset>

            <p>{{ msg }}</p>
            <ul v-if="errors.length">
                <li v-for="err in errors">
                    {{ err }}
                </li>
            </ul>

            <button
                class="pure-button pure-button-primary pure-input-1"
                @click="onClick"
            > Create </button>

        </form>

   </div>


</template>


<style lang="scss" scoped >

</style>


<script lang="ts">

import {Vue, Component, Prop, Watch} from 'vue-property-decorator';
import axios from 'axios';




@Component({})
export default class NewOrganismVue extends Vue {

    genus: string = ''
    species: string = '';
    msg: string = '';
    errors: string[] = [];

    validate(){
        let validationSuccess = true;

        if(!this.genus){
            validationSuccess = false;
            this.errors.push("The genus field is required.");
        }

        if(!this.species){
            validationSuccess = false;
            this.errors.push("The species field is required.");
        }

        return validationSuccess;
    }

    async onClick(){

        this.msg = '';
        this.errors = [];

        if(!this.validate()){
            this.msg = 'Errors:';
            return false;
        }

        const data = {
            genus: this.genus,
            species: this.species,
        }

        this.msg = 'Adding new organism';

        try {
            const response = await axios.post('/api/organisms/', data);
            this.msg = 'Success';
        } catch (e:any){
            if (e.response.status === 422) {
                this.msg = 'Errors:';
                for (const [key, error] of Object.entries(e.response.data.errors)) {
                    this.errors = this.errors.concat(error);
                }
            } else {
                this.msg = 'Unexpected error.'
            }
        }


    }

}
</script>
