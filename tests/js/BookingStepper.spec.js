import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

import { createLocalVue, mount } from '@vue/test-utils'
import { expect } from 'chai';
import BookingStepper from '../../resources/js/components/VendorKiki/BookingStepper.vue'

const localVue = createLocalVue()
localVue.use(BootstrapVue);
localVue.use(IconsPlugin);


describe('flipping-stepper with 2 slots', () => {
	//Arrange
	const wrapper = mount(BookingStepper, {
		localVue,
		propsData: {
			numSteps: 2,
		},
		slots: {
			1: '<div>slot 1</div>',
			2: '<div>slot 2</div>',
		}
	});
	const forwardBtn = wrapper.find('#forwardBtn');
	const backBtn = wrapper.find('#backBtn');
	
	it('On first load the stepper can only go forward and contain the correct slot.', () => {
		// Assert
		expect(backBtn.attributes()).contain.key('disabled');
		expect(forwardBtn.attributes()).not.contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 1');
	});
	
	it('Upon clicking next to the second and last slot the Forward Button should be disabled but Backward Button enabled', async () => {
		// Act
		await forwardBtn.trigger('click');
		
		// Assert
		expect(backBtn.attributes()).not.contain.key('disabled');
		expect(forwardBtn.attributes()).contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 2');
	});
	
	it('Upon clicking backward back to first slot the Backward Button should be disabled and Forward Button enabled again', async () => {
		// Act
		await backBtn.trigger('click');
		
		// Assert
		expect(backBtn.attributes()).contain.key('disabled');
		expect(forwardBtn.attributes()).not.contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 1');
	});
});

describe('flipping-stepper with 3 slots', () => {
	
	//Arrange
	const wrapper = mount(BookingStepper, {
		localVue,
		propsData: {
			numSteps: 3,
		},
		slots: {
			1: '<div>slot 1</div>',
			2: '<div>slot 2</div>',
			3: '<div>slot 3</div>',
		}
	});
	const forwardBtn = wrapper.find('#forwardBtn');
	const backBtn = wrapper.find('#backBtn');
	
	it('Upon clicking Next the three slots stepper should have both buttons enabled and contain the correct slot.', async () => {
	    // Act
		await forwardBtn.trigger('click');
		
	    // Assert
		expect(backBtn.attributes()).not.contain.key('disabled');
		expect(forwardBtn.attributes()).not.contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 2');
	});
	
	it('Upon clicking Next again reaching the last step the stepper should not be enabled to go anymore forward and should contain the correct slot.', async () => {
	    // Act
		await forwardBtn.trigger('click');
		
	    // Assert
		expect(backBtn.attributes()).not.contain.key('disabled');
		expect(forwardBtn.attributes()).contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 3');
	});
	
	it('Clicking Back should see both buttons enabled again and contain the correct slot.', async () => {
	    // Act
		await backBtn.trigger('click');
		
		// Assert
		expect(backBtn.attributes()).not.contain.key('disabled');
		expect(forwardBtn.attributes()).not.contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 2');
	});
});

describe('flipping-stepper with 6 slots', () => {
	//Arrange
	const wrapper = mount(BookingStepper, {
		localVue,
		propsData: {
			numSteps: 6,
		},
		slots: {
			1: '<div>slot 1</div>',
			2: '<div>slot 2</div>',
			3: '<div>slot 3</div>',
			4: '<div>slot 4</div>',
			5: '<div>slot 5</div>',
			6: '<div>slot 6</div>',
		}
	});
	const forwardBtn = wrapper.find('#forwardBtn');
	const backBtn = wrapper.find('#backBtn');
	
	it('Upon changing the next step to 3, slot 3 should be shown', async () => {
		//Act
		wrapper.setProps({ value: {nextStep: 3}});
		await forwardBtn.trigger('click');
		
		//Assert
		expect(backBtn.attributes()).not.contain.key('disabled');
		expect(forwardBtn.attributes()).not.contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 3');
	}),
	
	it('Upon changing the next step to 5, slot 5 should be shown', async () => {
		//Act
		wrapper.setProps({ value: {nextStep: 5}});
		await forwardBtn.trigger('click');
		
		//Assert
		expect(backBtn.attributes()).not.contain.key('disabled');
		expect(forwardBtn.attributes()).not.contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 5');
	}),
	
	it('Backward should follow the path where it came from back to slow 3', async () => {
		//Act
		await backBtn.trigger('click');
		
		//Assert
		expect(backBtn.attributes()).not.contain.key('disabled');
		expect(forwardBtn.attributes()).not.contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 3');
	}),
	
	it('Changing the next step should Not affect it going back where it came from back to slot 1', async () => {
		//Act
		wrapper.setProps({ value: {nextStep: 5}});
		await backBtn.trigger('click');
		
		//Assert
		expect(backBtn.attributes()).contain.key('disabled');
		expect(forwardBtn.attributes()).not.contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 1');		
	}),
	
	it('Without changing the next step the normal path should follow to slot 2', async () => {
		//Act
		await forwardBtn.trigger('click');
		
		//Assert
		expect(backBtn.attributes()).not.contain.key('disabled');
		expect(forwardBtn.attributes()).not.contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 2');
	})
});

describe('flipping-stepper emit events', () => {
	//Arrange
	const wrapper = mount(BookingStepper, {
		localVue,
		propsData: {
			numSteps: 2,
		},
		slots: {
			1: '<div>slot 1</div>',
			2: '<div>slot 2</div>',
		}
	});
	const forwardBtn = wrapper.find('#forwardBtn');
	const backBtn = wrapper.find('#backBtn');
	
	it('On forward the flip-forward event should be emitted', async () => {
		//Act
		await forwardBtn.trigger('click');
		
		//Assert
		expect(wrapper.emitted('flip-forward')).to.be.ok;
	});
	
	it('On Backward the flip-backward event should be emitted', async () => {
		//Act
		await backBtn.trigger('click');
		
		//Assert
		expect(wrapper.emitted('flip-backward')).to.be.ok;
	});
});