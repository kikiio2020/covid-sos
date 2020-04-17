<template>
    <b-container>
        <b-row>
            <b-col>
                <button-sos v-if="!isResponder" @click="onSosClick(arguments)" class="m-3"></button-sos>
                <button-invite class="m-3"></button-invite>
                <button-shoplist class="m-3"></button-shoplist>
            </b-col>
        </b-row>      
    </b-container> 
</template>
<script>
export default {
    components: {},
    props: [
        'isResponder',
    ],
    data() {
        return {
        }
    },
    methods: {
        onSosClick: function(events) {
            this.$emit('SosClick', events);
        },
    },
    computed: {},
    mounted() {
    }
}
</script>