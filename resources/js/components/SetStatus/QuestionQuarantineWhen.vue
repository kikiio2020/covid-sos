<template>
<div>
    <b-jumbotron border-variant="success" bg-variant="light">
            <b-container>
                <b-row><b-col class="text-left">
                    <p>
                        You should self-isolate for 14 days and call your local 
                        <a target="_blank" href='https://www.canada.ca/en/public-health/services/diseases/2019-novel-coronavirus-infection/symptoms/provincial-territorial-resources-covid-19.html'>health authority.</a>                    
                    </p>
                    <p>
                        More info <a target="_blank" href="https://www.canada.ca/en/public-health/services/diseases/2019-novel-coronavirus-infection/symptoms.html">here</a>
                    </p>
                </b-col></b-row>
                <b-row>
                    <b-col class="text-left font-weight-bold">
                        Please tell us when did you start your self-isolation?
                    </b-col>
                </b-row>
                <b-row>
                    <b-col class="text-center">
                        <b-calendar 
                            v-model="value.quarantinedDate" 
                            @context="onContext" 
                            :min="min" 
                            :max="max"
                            locale="en-US"
                            hide-header
                            label-help=""
                        ></b-calendar>
                    </b-col>
                </b-row>
            </b-container>
    </b-jumbotron>
</div>
</template>
<script>
export default {
    components: {},
    props: ['value'],
    data() {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const maxDate = new Date(today);
        const minDate = new Date(today);
        minDate.setDate(minDate.getDate() - 13);

        return {
            min: minDate,
            max: maxDate
        }
    },
    methods: {
        onContext(ctx) {
            this.context = ctx;
            this.$parent.okNext = ctx.selectedDate !== null;
            window.scrollTo({ top:document.body.scrollHeight, behavior: 'smooth', });
        }
    },
    computed: {},
    mounted() {
        //Must go to step 7 (conclusion-quarantine)
        this.$parent.value.nextStep = 7;
        this.$parent.okNext = false;       
    }
}
</script>