<template>
   <tr>
       <td>{{ genus }}</td>
       <td>{{ species }}</td>
       <td>{{ samples_count }}</td>
   </tr>
</template>


<script lang="ts">

import { Component, Prop, Vue } from 'vue-property-decorator';

@Component
export default class OrganismVue extends Vue {
    @Prop({ type: String, required: true })
    private genus!: string;

    @Prop({ type: String, required: true })
    private species!: number;

    @Prop({ type: Number, required: true })
    private samples_count!: number;
}
</script>
