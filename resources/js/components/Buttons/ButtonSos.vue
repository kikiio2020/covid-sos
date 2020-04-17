<template>
<b-button @click="$emit('click', arguments);" variant="outline-success">
    <span class="text-body">
    S<span class="text-danger">O</span>S!
    </span>
</b-button>
</template>
<script>
var bus = new Vue({});
export default {
    components: {},
    props: [],
    data() {
        return {
        }
    },
    methods: {
    },
    computed: {},
    mounted() {
    }
}
</script>