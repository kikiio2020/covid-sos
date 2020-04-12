<template>
<div>
    <b-jumbotron border-variant="success" bg-variant="light">
            <b-container>
                <b-row>
                    <b-col class="text-left font-weight-bold pb-3">
                        Are you among the high risk demographics and are self-isolating?
                    </b-col>
                </b-row>
                <b-row>
                    <b-col class="text-left">
                        If you are:
                    </b-col>
                </b-row>
                <b-row>
                    <b-col class="text-left">
                        <p>
                            <ul>
                                <li>Aged 65 years and older</li>
                                <li>
                                    Or with underlying health conditions: 
                                    <ul>
                                        <li>Blood disorders: such as sickle cell disease or taking blood thinners</li>
                                        <li>Chronic kidney disease</li>
                                        <li>Chronic liver disease</li>
                                        <li>Compromised immune system: including undergoing cancer treatment such as chemotherapy or radiation, having received an organ or bone marrow transplant, or taking high doses of corticosteroids or other immunosuppressant medications, and HIV or AIDS</li>
                                        <li>Current or recent pregnancy in the last two weeks</li>
                                        <li>Endocrine disorders, such as diabetes</li>
                                        <li>Metabolic disorders</li>
                                        <li>Heart disease</li>
                                        <li>Lung disease, including asthma</li>
                                        <li>Neurological and neurologic and neurodevelopment conditions</li>
                                    </ul>
                                </li>
                            </ul>
                        </p>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col class="text-center py-1">
                        <b-form-group label="">
                            <b-form-radio-group
                                v-model="isHighRisk"
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
                                    <a href="https://www.cdc.gov/coronavirus/2019-ncov/specific-groups/high-risk-complications.html" target="_blank">
                                    https://www.cdc.gov/coronavirus/2019-ncov/specific-groups/high-risk-complications.html
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.cdc.gov/coronavirus/2019-ncov/specific-groups/people-at-higher-risk.html" target="_blank">
                                    https://www.cdc.gov/coronavirus/2019-ncov/specific-groups/people-at-higher-risk.html
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.prevention.com/health/a31245792/coronavirus-high-risk-groups/#" target="_blank">
                                    https://www.prevention.com/health/a31245792/coronavirus-high-risk-groups/#
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.canada.ca/coronavirus" target="_blank">
                                    https://www.canada.ca/coronavirus
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
            isHighRisk: null,
            options: [
                { text: 'Yes', value: true },
                { text: 'No', value: false },
            ],
            steps: {
                nextStep: null,
            }
        }
    },
    methods: {
        onSelect(){
            //If in High Risk go to step 5 (conclusion-high-risk)
            //else go to step 3 (can-help-yes-no)
            console.log(this.isHighRisk);
            this.$parent.value.nextStep = this.isHighRisk?4:2;
            console.log(this.$parent.value.nextStep);
            this.$parent.okNext = null !== this.isHighRisk;       
            window.scrollTo({ top:document.body.scrollHeight, behavior: 'smooth', });    
        }
    },
    computed: {},
    mounted() {
        this.$parent.okNext = false;       
    }
}
</script>