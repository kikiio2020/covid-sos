import { Role, ClientFunction } from 'testcafe';

/**
 * 
 * E2E road map:
 * 
 * - Requestor creates an SOS
 *   - modal doesn't close
 *   - empty shop list appears
 * 
 * - Requestor Adds shop list items
 *   - Requestor opens an existing SOS
 *   - Requestor clicks insert button
 *   - Shop list modal shows up
 *   - Requestor type a letter to the search box
 *   - Shop list choices show up
 *   - Requestor selects an item
 *   - Requestor clicks save and modal closes
 *   - Item appears on the table
 *   
 * - Requestor make request
 *   - Requestor make a request from an existing SOS
 *   - Requestor clicks save and gets navigated to Pending tab
 *   - Item shows up on Pending tab
 * 
 * - Faraway helper does not see new request
 *   - Farawar helper does not see new request on Nearby tab
 * 
 * - Near helper pledge request
 *   - Near helper sees new request on nearby tab
 *   - Helper makes pledge to request
 *   - Helper gets navigated to In Progress tab
 *   - Helper sees item on In Progress tab
 *   - Helper navigates back to Nearby tab and does not see item anymore
 *   - Another near helper cannot see item on Nearby tab anymore
 *   - Notification gets sent to requestor
 *   - Open standalone In Progress page shows with no error
 *     
 * - Chat box
 *   - Helper opens up chat in In Progress tab and enter message
 *   - Helper clicks save and modal is closed
 *   - Helper opens up modal again and see message
 *   
 * - Complete Request
 *   - Helper completes the request
 *   - Helper confirms and Complete button is disabled
 *   - Request remains in In Progress tab
 *   - Notification is sent to Requestor
 *   
 * - Requestor Approves Request
 *   - Requestor clicks Complete 
 *   - Requestor confirms and gets navigated to History tab
 *   - Requestor sees request in History tab
 *   - Notification is sent to Helper
 *   - Open standalone In Progress page shows with no error
 * 
 */


//https://devexpress.github.io/testcafe/documentation/guides/advanced-guides/authentication.html
	
const recepient = Role('http://localhost', async t => {
    await t
        .typeText('#email', '123@123.com')
        .typeText('#password', '123123123123')
        .click('#login');
});

fixture('Index page').page('http://localhost');

const getPageUrl = ClientFunction(() => window.location.href);

test('User 123 can login', async t => {
  
	await t
		.useRole(recepient)
		.expect(getPageUrl()).contains('home'); 

	
	
	/*
	const emailField = await new Selector('input[id="email"]');
	const passwordField = await new Selector('input[id="password"]');
	const submitBtn = await new Selector('button[type="submit"]');
  
	await t
	  	.typeText(emailField, "123@123.com")
	  	.typeText(passwordField, "123123123123")
	  	.click(submitBtn)
	  	.expect()
  	*/
});
