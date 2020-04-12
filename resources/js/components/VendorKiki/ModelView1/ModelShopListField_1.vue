<template>
<div>
    <b-table striped hover :items="items" :fields="fields">
        <template v-slot:cell(desc)="row">
            <b-form-input></b-form-input>
        </template>   
        <template v-slot:cell(qty)="row">
            <b-form-input style="width:3em"></b-form-input>
        </template>   
        <template v-slot:cell(max_dollar)="row">
            <b-form-input style="width:3em"></b-form-input>
        </template>   
    </b-table>
</div>
</template>
<script>
export default {
    components: {},
    props: [],
    data() {
        return {
            // Note 'isActive' is left out and will not appear in the rendered table
            fields: [
                {
                    key: 'item',
                    sortable: true
                },
                {
                    key: 'desc',
                    label: 'Description'
                },
                {
                    key: 'qty',
                },
                {
                    key: 'max_dollar',
                    label: 'Max $ worth',
                },
                {
                    key: 'Action'
                },
            ],
            items: [
                { item: 'Orange',  desc: 'Small ones', qty: '3', max_dollar: '3' },
                { item: 'Apple',  desc: 'Big red ones', qty: '4', max_dollar: '4.5' },
                { item: 'Celery',  desc: 'Small ones, buy cheapest.', qty: '2', max_dollar: '1.25' },
            ]
        }
    },
    methods: {
    },
    computed: {},
    mounted() {
    }
}
</script>