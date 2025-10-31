import { test, expect } from '@playwright/test';
import { doLogin, createGeneralPage, createCWLPage } from './helper.js';

test.describe('Generic Test Suite - CWL and General access', () => {
  const password = 'test';
  const titleMessage = 'Playwright Test Basic Page';
  const bodyMessage = 'Playwright body text';
  const generalpageURL = `/general-${Date.now()}`;
  const cwlpageURL = `/cwl-${Date.now()}`;

  test.beforeEach(async ({ page, baseURL }) => {
    await doLogin(page, baseURL); 
    await createGeneralPage(page, generalpageURL, titleMessage, bodyMessage);
    await createCWLPage(page, cwlpageURL, titleMessage, bodyMessage);
  });

  test('Create and test CWL user access', async ({ page }) => {
    // Check basic pages are created
    await page.goto(generalpageURL);
    await expect(page.locator('#block-olivero-page-title')).toContainText(titleMessage);
    await expect(page.locator('#block-olivero-content')).toContainText(bodyMessage);
    await expect(page.locator('.field--name-field-visibility .field__item')).toContainText('General');

    await page.goto(cwlpageURL);
    await expect(page.locator('#block-olivero-page-title')).toContainText(titleMessage);
    await expect(page.locator('#block-olivero-content')).toContainText(bodyMessage);
    await expect(page.locator('.field--name-field-visibility .field__item')).toContainText('CWL');

    // // Create CWL user
    // await page.goto('/admin/people');
    // const username = `testCWL-${Date.now()}`;
    // await page.goto('/admin/people/create');

    // // Type username
    // await page.fill('[data-drupal-selector="edit-name"]', username);

    // // Type password
    // await page.fill('[data-drupal-selector="edit-pass-pass1"]', password);
    // await page.fill('[data-drupal-selector="edit-pass-pass2"]', password);

    // // Check CWL Role
    // await page.check('[data-drupal-selector="edit-roles-cwl"][name="roles[cwl]"]');

    // // Save changes
    // await page.locator(' [data-drupal-selector="edit-submit"]').click();

    // // Verify the page content
    // const primaryContent = page.locator('#message-status-title');
    // await expect(primaryContent).toContainText('Status message');

    // // Log out
    // await page.goto('/user/logout/confirm');
    // await page.locator('[data-drupal-selector="edit-submit"][value="Log out"]').click();

    // // Log in 
    // await page.goto('/user');
    // await page.fill('#edit-name', username);
    // await page.fill('#edit-pass', password);
    // await page.click('input#edit-submit[value="Log in"]');

    // // Check both cwl and general pages can be accessed
    // await page.goto(cwlpageURL);
    // await expect(page.locator('#block-olivero-page-title')).toContainText(titleMessage);
    // await expect(page.locator('#block-olivero-content')).toContainText(bodyMessage);

    
    // await page.goto(generalpageURL);
    // await expect(page.locator('#block-olivero-page-title')).toContainText(titleMessage);
    // await expect(page.locator('#block-olivero-content')).toContainText(bodyMessage);
  });

  // test('Create and test General user access', async ({ page }) => { 
  //   // Check basic pages are created
  //   await page.goto(generalpageURL);
  //   await expect(page.locator('#block-olivero-page-title')).toContainText(titleMessage);
  //   await expect(page.locator('#block-olivero-content')).toContainText(bodyMessage);
  //   await expect(page.locator('.field--name-field-visibility .field__item')).toContainText('General');

  //   await page.goto(cwlpageURL);
  //   await expect(page.locator('#block-olivero-page-title')).toContainText(titleMessage);
  //   await expect(page.locator('#block-olivero-content')).toContainText(bodyMessage);
  //   await expect(page.locator('.field--name-field-visibility .field__item')).toContainText('CWL');

  //   // Create General user
  //   const username = `testGeneral-${Date.now()}`;
  //   await page.goto('/admin/people/create');

  //   // Type username
  //   await page.fill('[data-drupal-selector="edit-name"]', username);

  //   // Type password
  //   await page.fill('[data-drupal-selector="edit-pass-pass1"]', password);
  //   await page.fill('[data-drupal-selector="edit-pass-pass2"]', password);

  //   // Save changes
  //   await page.locator(' [data-drupal-selector="edit-submit"]').click();

  //   // Verify the page content
  //   const primaryContent = page.locator('#message-status-title');
  //   await expect(primaryContent).toContainText('Status message');


  //   // Log out
  //   await page.goto('/user/logout/confirm');
  //   await page.locator('[data-drupal-selector="edit-submit"][value="Log out"]').click();

  //   // Log in 
  //   await page.goto('/user');
  //   await page.fill('#edit-name', username);
  //   await page.fill('#edit-pass', password);
  //   await page.click('input#edit-submit[value="Log in"]');

  //   // Check only general page can be accessed
  //   await page.goto(cwlpageURL);
  //   await expect(page.locator('#block-olivero-page-title')).toContainText('Page not found');


  //   await page.goto(generalpageURL);
  //   await expect(page.locator('#block-olivero-page-title')).toContainText(titleMessage);
  //   await expect(page.locator('#block-olivero-content')).toContainText(bodyMessage);
  // });

});