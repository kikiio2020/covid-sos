<template>
    <b-container><b-row>
    <b-col></b-col>
    <b-col sm=8>
        <flipping-stepper num-steps="8" v-model="steps">
            <template v-slot:1>
                <question-have-symptoms></question-have-symptoms>
            </template>
            <template v-slot:2>
                <question-quarantine-high-risk-yes-no></question-quarantine-high-risk-yes-no>
            </template>
            <template v-slot:3>
                <question-can-help-yes-no></question-can-help-yes-no>
            </template>                        		
            <template v-slot:4>
                <conclusion-responder></conclusion-responder>
            </template>                        		
            <template v-slot:5>
                <conclusion-high-risk></conclusion-high-risk>
            </template>
            <template v-slot:6>
                <question-quarantine-when v-model="formData"></question-quarantine-when>
            </template>                        		
            <template v-slot:7>
                <conclusion-quarantine></conclusion-quarantine>
            </template>                        		
            <template v-slot:8>
                <conclusion-unknown></conclusion-unknown>
            </template>                        		
        </flipping-stepper>
    </b-col>
    <b-col></b-col>
    </b-row></b-container>
</template>
<script>
import FlippingStepper from '@kikiio2020/vue-flipping-stepper';

export default {
    components: {FlippingStepper},
    props: [],
    data() {
        return {
            steps: {
                nextStep: null,
            },
            formData: {
                quarantinedDate: '',
                status: 0,
            }
        }
    },
    methods: {},
    computed: {},
    mounted() {}
}
</script>