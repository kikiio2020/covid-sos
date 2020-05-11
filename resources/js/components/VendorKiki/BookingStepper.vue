<template>
    <!-- booking-stepper  -->
    <div :style="bookingStepperStyle" class="container p-3 text-center">
        
        <transition name="flipper" mode="out-in">
            <div v-for="idx in numberOfSlots" v-if="step==idx" :key="idx">
                <slot :name="idx"></slot>
            </div>
        </transition>        
            
        <span id="backBtnWrapper">
            <b-button 
                id="backBtn"
                v-on:click="flipBackward"
                :style="stepperBtnStyleBackward"
                class="booking-stepper-btn rounded-circle m-3"
                title="Previous"
                :disabled="stepperBtnBackwardDisabled"
            >
                <b-icon-skip-backward-fill></b-icon-skip-backward-fill>
            </b-button>
        </span>
        <b-tooltip target="backBtnWrapper" title="Previous"></b-tooltip>

        <span id="nextBtnWrapper">
            <b-button 
                id="forwardBtn"
                v-on:click="flipForward"
                :style="stepperBtnStyleForward"
                class="booking-stepper-btn rounded-circle m-3"
                title="Next"
                :disabled="stepperBtnForwardDisabled"
            >
                <b-icon-play-fill></b-icon-play-fill>
            </b-button>
        </span>
        <b-tooltip target="nextBtnWrapper" title="Next"></b-tooltip>
        <div class="text-sm-right text-black-50"></div>
    </div>
</template>

<script>
    import { BButton, BTooltip, BIconSkipBackwardFill, BIconPlayFill, BIconSkipEndFill } from 'bootstrap-vue';

    export default {
        components: {
            BIconPlayFill, BIconSkipEndFill
        },
        props: {
            testProp: {
            	default: 1
            },
        	numSteps: {
                default: 1
            },
            value: {
                default: () => {
                	return {
                		nextStep: null
                	}
                } 
            },
            themeColor: {
            	default: '#73AD21'
            },
        },
        data: function () {
            return {
                step: this.value.nextStep ? this.value.nextStep : 1,
                stepped: [],
                numberOfSlots: Number(this.numSteps),
                okNext: true,
                okPrev: true,
                noMoreNext: false,
                noMorePrev: true,
                bookingStepperStyle: {
                	'border-radius': '25px',
                	'border': '2px solid '+ this.themeColor,
                	'padding': '20px',
                	'min-width': '320px',
                	'min-height': '150px',
                },
                bookingStepperBtnStyle: {
                	'border-color': this.themeColor,
                    'color': this.themeColor,
                },
            }
        },
        computed: {
        	stepperBtnBackwardDisabled () {
        		return !this.okPrev || this.noMorePrev;	
        	},
        	stepperBtnForwardDisabled () {
        		return !this.okNext || this.noMoreNext;	
        	},
        	stepperBtnStyleBackward() {
        		if (this.stepperBtnBackwardDisabled) {
        			return {
        				'border-color': '#acbc95',
                        'color': '#707a63',
        			}
        		}
        		return {
        			'border-color': this.themeColor,
                    'color': this.themeColor,	
        		}
        	},
        	stepperBtnStyleForward() {
        		if (this.stepperBtnForwardDisabled) {
        			return {
        				'border-color': '#acbc95',
                        'color': '#707a63',
        			}
        		}
        		return {
        			'border-color': this.themeColor,
                    'color': this.themeColor,	
        		}
        	},
        },
        methods: {
            flipForward() {
                this.stepped.push(this.step);
                if (this.value.nextStep) {
                    this.step = this.value.nextStep;
                    this.value.nextStep = null;
                }
                this.$emit('flip-forward');
                if (this.step >= this.getNumSlots()) {
                    console.log('no more forward');
                    this.noMoreNext = true;
                    return;
                }
                this.resetNavControls();
                this.step ++;
                if (this.step >= this.getNumSlots()) {
                    this.noMoreNext = true;
                }
            },
            flipBackward() {    
                this.step = this.stepped.pop();
                if (this.step <= 1) {
                    console.log('no more backward');
                    this.noMorePrev = true;
                    return;
                }
                this.resetNavControls();
            },
            getNumSlots: function() {
                return Object.keys(this.$slots).length;
            },
            resetNavControls: function() {
                this.noMoreNext = false;
                this.noMorePrev = false;
                this.okNext = true;
                this.okPrev = true;
            },
        },
        beforeCreate() {
            console.log('before create');
        },
        created() {
            console.log('BookingStepper created.'); 
        },
        mounted() {
            console.log('BookingStepper mounted.');
        }
    }
</script>

<style>

.flipper-enter-active, .flipper-leave-active {
    transition: transform 0.5s ease-in-out;
}
.flipper-enter, .flipper-leave-to {
    transform: rotateY(90deg);
    z-index: 0;
}

.booking-stepper {
    border-radius: 25px;
    border: 2px solid #73AD21;
    padding: 20px;
    min-width: 320px;
    min-height: 150px;
}
.booking-stepper-btn {
    border-width: medium;
    background-color: #ffffff;
}
.booking-stepper-btn:hover {
    background-color:rgba(0, 0, 0, 0.2);
}
.booking-stepper-btn:disabled {
    background-color: #e5e5e5;
    cursor: no-drop;
}

</style>