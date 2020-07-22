<template>
    <ValidationProvider :name="modalFieldProperties.caption" :rules="modalFieldProperties.rules" v-slot="validationContext">
        
        <component 
            ref="dynComp"
            :is="modalFieldComponent" 
            v-bind="modalFieldComponentProperties"
            v-model="vModel"
            @input="$emit('input', vModel)"
            aria-describedby="feedback"
            :state="getValidationState(validationContext)"
            :model-id="modelId"
            :user-name="userName"
            :api="api"
            v-b-popover="modalFieldComponentProperties.vBPopover"
        >{{modalFieldComponentProperties.content}}</component>
    
        <b-form-invalid-feedback id="feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
    </ValidationProvider>
</template>
<script>
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';
import { messages } from 'vee-validate/dist/locale/en.json';

Object.keys(rules).forEach(rule => {
	extend(rule, {
    	...rules[rule],
	    message: messages[rule],
  	});
});

export default {
    components: {ValidationProvider, ValidationObserver},
    props: {
        value: {},
        modalFieldProperties: Object,
        caption: String, 
        rules: {
            default: ''
        },
        modelId: Number,
        userName: String,
        api: String,
        vSlot: String,
    },
    data() {
        return {
            vModel: this.value
        }
    },
    methods: {
        getValidationState({ dirty, validated, valid = null, invalid }) {
            return dirty || validated ? valid : null;
        },
        onContext(ctx) {
            this.context = ctx
        },
    },
    computed: {
        modalFieldComponent: function() {
            return this.modalFieldProperties.fieldType;
        },
        modalFieldComponentProperties: function() {
            return this.modalFieldProperties;
        }
    },
    mounted() {
    },
}
</script>