<template>
    <b-container>
        <b-row><b-col>
            <strong>{{placeholder}}:</strong>
            <b-list-group v-bind="modalFieldProperties">
	  			<b-list-group-item 
	  				v-for="(item, key) in value"
	  				class="d-flex justify-content-between align-items-center"
	  				:key="item.id"
  				>
	    			{{ value.name }}
	    			<b-badge variant="primary" pill>{{ value.quantity }}</b-badge>
	  			</b-list-group-item>
			</b-list-group>
        </b-col></b-row>
    </b-container>
</template>
<script>
export default {
    components: {},
    props: {
    	name: '',
    	id: '',
    	placeholder: '',
    	value: {},
	    modalFieldProperties: {},
	    caption: '', 
	    modelId: Number,
	},
    data() {
        return {}
    },
    methods: {
    },
    computed: {
    },
    mounted() {
    	console.log(this.value);
    }
}
</script>