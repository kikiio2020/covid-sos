<template>
<div id="container">
    <b-jumbotron border-variant="success" bg-variant="light">
            <b-container>
                <b-row><b-col class="text-left pb-3">Thank you for registering with us! Please tell us about the following:</b-col></b-row>
                <b-row>
                    <b-col class="text-left font-weight-bold">
                        Are you currently showing or been in contact with someone with one or more of the symptoms?
                    </b-col>
                </b-row>
                <b-row>
                    <b-col class="text-left">
                        <p>
                            <ul>
                                <li>fever</li>
                                <li>cough</li>
                                <li>difficulty breathing</li>
                                <li>pneumonia in both lungs</li>
                            </ul>
                        </p>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col class="text-center py-1">
                        <b-form-group label="">
                            <b-form-radio-group
                                v-model="haveSymptoms"
                                :options="options"
                                buttons
                                button-variant="outline-primary"
                                size="lg"
                                name="question1"
                                @input="onSelect"
                            ></b-form-radio-group>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col class="text-left">
                        <small>
                            <h6>Reference:</h6>
                            <ul>
                                <li>
                                    <a href="https://www.canada.ca/en/public-health/services/diseases/2019-novel-coronavirus-infection/symptoms.html" target="_blank">
                                    https://www.canada.ca/en/public-health/services/diseases/2019-novel-coronavirus-infection/symptoms.html
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.cdc.gov/coronavirus/2019-ncov/symptoms-testing/symptoms.html" target="_blank">
                                    https://www.cdc.gov/coronavirus/2019-ncov/symptoms-testing/symptoms.html
                                    </a>
                                </li>
                            </ul>
                        </small>
                    </b-col>
                </b-row>
            </b-container>
    </b-jumbotron>
</div>
</template>
<script>
export default {
    components: {},
    props: {},
    data() {
        return {
            haveSymptoms: null,
            options: [
                { text: 'Yes', value: true },
                { text: 'No', value: false },
            ],
        }
    },
    methods: {
        onSelect(){
            //If have symptoms go to step 6 (quarantine-when)
            //else go to step 2 (high-risk-yes-no)
            console.log(this.haveSymptoms);
            this.$parent.value.nextStep = this.haveSymptoms?5:1;
            console.log(this.$parent.value.nextStep);
            this.$parent.okNext = null !== this.haveSymptoms;  
            window.scrollTo({ top:document.body.scrollHeight, behavior: 'smooth', });
        }
    },
    computed: {},
    mounted() {
        this.$parent.okNext = false;       
    }
}
</script>