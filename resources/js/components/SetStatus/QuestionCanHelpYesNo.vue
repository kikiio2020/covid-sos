<template>
<div>
    <b-jumbotron border-variant="success" bg-variant="light">
            <b-container>
                <b-row>
                    <b-col class="text-left font-weight-bold pb-3">
                        Are you able to help a nearby self-isolating neighbor with getting essentials when neeeded?
                    </b-col>
                </b-row>
                <b-row>
                    <b-col class="text-center py-1">
                        <b-form-group label="">
                            <b-form-radio-group
                                v-model="canHelp"
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
            canHelp: null,
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
            //If Can Help go to step 4 (conclusion-can-help)
            //else go to step 8 (conclusion-unknown)
            this.$parent.value.nextStep = this.canHelp?4:8;
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