import { test, expect } from '@playwright/test';
import { doLogin } from './helper.js';

test.describe('Generic Test Suite - CWL and General access', () => {
  const password = 'test';
  const bodyMessage = 'Playwright body text';
  const generalpageURL = '/general';
  const cwlpageURL = '/cwl';

  test.beforeEach(async ({ page, baseURL }) => {
    await doLogin(page, baseURL);  
  });

  test('Create a basic page with General access', async ({ page }) => {
    await page.goto(generalpageURL);
    const titleMessage = 'Playwright Test Basic Page - General';
    const text = await page.locator('#block-olivero-page-title').textContent();

    if (text && text.includes(titleMessage)) {
        console.log('Page already exists. Skipping creation.');
    } else {
        await page.goto('/node/add/page');

        // Type title
        const titleInput = page.locator('[data-drupal-selector="edit-title-wrapper"] input');
        await titleInput.fill(titleMessage);
        await expect(titleInput).toHaveValue(titleMessage);

        // Locate CKEditor editable area by class + role
        const ckEditor = page.locator('#edit-body-wrapper .ck[role="textbox"]');

        // Focus and type text (equivalent to realClick + realType)
        await ckEditor.click();
        await page.keyboard.type(bodyMessage, { delay: 0 });

        // Verify CKEditor content using its API
        const editorData = await ckEditor.evaluate(el => el.ckeditorInstance.getData());
        await expect(editorData).toContain(bodyMessage);

        // Publish the content type
        const status = page.locator('[data-drupal-selector="edit-status-value"]');
        await status.check();

        // Check General Visibility
        await page.fill('[data-drupal-selector="edit-field-visibility-0-target-id"]', 'General');
        await expect(page.locator('#edit-field-visibility-0-target-id')).toHaveValue(/General/);

        // Add menu
        await page.locator('[data-drupal-selector="edit-menu"]').first().locator('summary').click();
        await page.check('input[type="checkbox"][name="menu[enabled]"]');

        // Add alias
        await page.locator('[data-drupal-selector="edit-path-0"]').click();
        await page.fill('[data-drupal-selector="edit-path-0-alias"]', generalpageURL);

        // Save changes
        await page.locator(' [data-drupal-selector="edit-submit"]').click();
    }

    // Verify the page content
    await expect(page.locator('#block-olivero-page-title')).toContainText(titleMessage);
    await expect(page.locator('#block-olivero-content')).toContainText(bodyMessage);
  });

  test('Create a basic page with CWL access', async ({ page }) => {
    await page.goto(cwlpageURL);
    const titleMessage = 'Playwright Test Basic Page - CWL';
    const text = await page.locator('#block-olivero-page-title').textContent();

    if (text && text.includes(titleMessage)) {
        console.log('Page already exists. Skipping creation.');
    } else {

        await page.goto('/node/add/page');

        // Type title
        const titleInput = page.locator('[data-drupal-selector="edit-title-wrapper"] input');
        await titleInput.fill(titleMessage);
        await expect(titleInput).toHaveValue(titleMessage);

        // Locate CKEditor editable area by class + role
        const ckEditor = page.locator('#edit-body-wrapper .ck[role="textbox"]');

        // Focus and type text (equivalent to realClick + realType)
        await ckEditor.click();
        await page.keyboard.type(bodyMessage, { delay: 0 });

        // Verify CKEditor content using its API
        const editorData = await ckEditor.evaluate(el => el.ckeditorInstance.getData());
        await expect(editorData).toContain(bodyMessage);

        // Publish the content type
        const status = page.locator('[data-drupal-selector="edit-status-value"]');
        await status.check();

        // Check General Visibility
        await page.fill('[data-drupal-selector="edit-field-visibility-0-target-id"]', 'CWL');
        await expect(page.locator('#edit-field-visibility-0-target-id')).toHaveValue(/CWL/);

        // Add menu
        await page.locator('[data-drupal-selector="edit-menu"]').first().locator('summary').click();
        await page.check('input[type="checkbox"][name="menu[enabled]"]');

        // Add alias
        await page.locator('[data-drupal-selector="edit-path-0"]').click();
        await page.fill('[data-drupal-selector="edit-path-0-alias"]', cwlpageURL);

        // Save changes
        await page.locator(' [data-drupal-selector="edit-submit"]').click();
    }

    // Verify the page content
    await expect(page.locator('#block-olivero-page-title')).toContainText(titleMessage);
    await expect(page.locator('#block-olivero-content')).toContainText(bodyMessage);
  });

  test('Create and test CWL user access', async ({ page }) => {
    await page.goto('/admin/people');
    const username = 'playwrightCWL';
    const usernames = await page.locator('#views-form-user-admin-people-page-1 .username').allTextContents();
    const exists = usernames.some(name => name.includes(username));

    if (exists) {
        console.log(`User "${username}" already exists. Skipping creation.`);
    } else {
        await page.goto('/admin/people/create');

        // Type username
        await page.fill('[data-drupal-selector="edit-name"]', username);

        // Type password
        await page.fill('[data-drupal-selector="edit-pass-pass1"]', password);
        await page.fill('[data-drupal-selector="edit-pass-pass2"]', password);

        // Check CWL Role
        await page.check('[data-drupal-selector="edit-roles-cwl"][name="roles[cwl]"]');

        // Save changes
        await page.locator(' [data-drupal-selector="edit-submit"]').click();

        // Verify the page content
        const primaryContent = page.locator('#message-status-title');
        await expect(primaryContent).toContainText('Status message');
    }

    // Log out
    await page.goto('/user/logout/confirm');
    await page.locator('[data-drupal-selector="edit-submit"][value="Log out"]').click();

    // Log in 
    await page.goto('/user');
    await page.fill('#edit-name', username);
    await page.fill('#edit-pass', password);
    await page.click('input#edit-submit[value="Log in"]');

    // CWL page
    await page.goto(cwlpageURL);
    
    // Verify the page content
    await expect(page.locator('#block-olivero-page-title')).toContainText('Playwright Test Basic Page - CWL');
    await expect(page.locator('#block-olivero-content')).toContainText(bodyMessage);

    // General page
    await page.goto(generalpageURL);
    
    // Verify the page content
    await expect(page.locator('#block-olivero-page-title')).toContainText('Playwright Test Basic Page - General');
    await expect(page.locator('#block-olivero-content')).toContainText(bodyMessage);
  });

  test('Create and test General user access', async ({ page }) => { 
    await page.goto('/admin/people');
    const username = 'playwrightGeneral';
    const usernames = await page.locator('#views-form-user-admin-people-page-1 .username').allTextContents();
    const exists = usernames.some(name => name.includes(username));

    if (exists) {
        console.log(`User "${username}" already exists. Skipping creation.`);
    } else {
        await page.goto('/admin/people/create');

        // Type username
        await page.fill('[data-drupal-selector="edit-name"]', username);

        // Type password
        await page.fill('[data-drupal-selector="edit-pass-pass1"]', password);
        await page.fill('[data-drupal-selector="edit-pass-pass2"]', password);

        // Save changes
        await page.locator(' [data-drupal-selector="edit-submit"]').click();

        // Verify the page content
        const primaryContent = page.locator('#message-status-title');
        await expect(primaryContent).toContainText('Status message');
    }

    // Log out
    await page.goto('/user/logout/confirm');
    await page.locator('[data-drupal-selector="edit-submit"][value="Log out"]').click();

    // Log in 
    await page.goto('/user');
    await page.fill('#edit-name', username);
    await page.fill('#edit-pass', password);
    await page.click('input#edit-submit[value="Log in"]');

    // CWL page
    await page.goto(cwlpageURL);
    
    // Verify the page content
    await expect(page.locator('#block-olivero-page-title')).toContainText('Page not found');

    // General page
    await page.goto(generalpageURL);
    
    // Verify the page content
    await expect(page.locator('#block-olivero-page-title')).toContainText('Playwright Test Basic Page - General');
    await expect(page.locator('#block-olivero-content')).toContainText(bodyMessage);
  });

});