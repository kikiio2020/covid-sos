<template>
<div>
    <div>
        <strong>{{placeholder}}:</strong>
        <a :href="'https://maps.google.com/?q=' + vModel" target="_blank">{{vModel}}</a>
    </div>
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