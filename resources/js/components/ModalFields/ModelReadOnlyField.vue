<template>
<div>
    <div><strong>{{placeholder}}:</strong> {{vModel}}</div>
</div>  
</template>
<script>
export default {
    props: {
        id: String,
        name: String, 
        caption: String, 
        placeholder: String, 
        value: {},
    },
    data() {
        return {
            vModel: this.value,
        }
    },
    methods: { 
    },
    computed: {
    },
    mounted() {
    }
}
</script>