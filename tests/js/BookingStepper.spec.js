import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

import { createLocalVue, mount } from '@vue/test-utils'
import { expect } from 'chai';
import BookingStepper from '../../resources/js/components/VendorKiki/BookingStepper.vue'

const localVue = createLocalVue()
localVue.use(BootstrapVue);
localVue.use(IconsPlugin);

describe('booking-stepper with 3 slots', () => {
	
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
	
	it('On first load the stepper can only go forward and contain the correct slot. ', () => {
	    // Assert
		expect(backBtn.attributes()).contain.key('disabled');
		expect(forwardBtn.attributes()).not.contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 1');
	}),
	
	it('Upon clicking Next the three slots stepper should have both buttons enabled and contain the correct slot.', async () => {
	    // Act
		await forwardBtn.trigger('click');
		
	    // Assert
		expect(backBtn.attributes()).not.contain.key('disabled');
		expect(forwardBtn.attributes()).not.contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 2');
	}),
	
	it('Upon clicking Next again reaching the last step the stepper should not be enabled to go anymore forward and should contain the correct slot.', async () => {
	    // Act
		await forwardBtn.trigger('click');
		
	    // Assert
		expect(backBtn.attributes()).not.contain.key('disabled');
		expect(forwardBtn.attributes()).contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 3');
	}),
	
	it('Clicking Back should see both buttons enabled again and contain the correct slot.', async () => {
	    // Act
		await backBtn.trigger('click');
		
		// Assert
		expect(backBtn.attributes()).not.contain.key('disabled');
		expect(forwardBtn.attributes()).not.contain.key('disabled');
		expect(wrapper.find('div').text()).equal('slot 2');
	})
})