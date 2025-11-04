import { exec } from 'child_process';
import util from 'util';
// import dotenv from 'dotenv';
// dotenv.config();

const execAsync = util.promisify(exec);

/**
 * Runs drush command via DDEV
 */
export async function drush(command) {
  const { stdout } = await execAsync(`ddev drush ${command}`);
  console.log(stdout);
  return stdout.trim();
}

/**
 * Logs into admin through the UI
 */
export async function doLogin(page, baseURL) {
  await page.goto(baseURL + "/user");
  await page.fill('#edit-name', process.env.USERNAME);
  await page.fill('#edit-pass', process.env.PASSWORD);
  await page.click('input#edit-submit[value="Log in"]');
  await page.goto('/');
}

/**
 * Creates a basic page - general
 */
export async function createGeneralPage(page, generalpageURL, titleMessage, bodyMessage) {
  await page.goto('/node/add/page');

  // Type title
  const titleInput = page.locator('[data-drupal-selector="edit-title-wrapper"] input');
  await titleInput.fill(titleMessage);

  // Locate CKEditor editable area by class + role
  const ckEditor = page.locator('#edit-body-wrapper .ck[role="textbox"]');

  // Type body text
  await ckEditor.click();
  await page.keyboard.type(bodyMessage, { delay: 0 });

  // Check General Visibility
  // const visibilityInput = page.locator('[data-drupal-selector="edit-field-visibility-wrapper"] input');
  // await visibilityInput.waitFor({ state: 'visible', timeout: 10000 });
  // Verify the field is visible and editable
  const visibilityInput = page.locator('[data-drupal-selector="edit-field-visibility-0-target-id"]');
  await visibilityInput.fill('1');

  // Add menu
  await page.locator('[data-drupal-selector="edit-menu"]').first().locator('summary').click();
  await page.check('input[type="checkbox"][name="menu[enabled]"]');

  // Add alias
  await page.locator('[data-drupal-selector="edit-path-0"]').click();
  await page.fill('[data-drupal-selector="edit-path-0-alias"]', generalpageURL);

  // Save changes
  await page.locator(' [data-drupal-selector="edit-submit"]').click();
}

/**
 * Creates a basic page - CWL
 */
export async function createCWLPage(page, cwlpageURL, titleMessage, bodyMessage) {
  await page.goto('/node/add/page');

  // Type title
  const titleInput = page.locator('[data-drupal-selector="edit-title-wrapper"] input');
  await titleInput.fill(titleMessage);

  // Locate CKEditor editable area by class + role
  const ckEditor = page.locator('#edit-body-wrapper .ck[role="textbox"]');

  // Type body text
  await ckEditor.click();
  await page.keyboard.type(bodyMessage, { delay: 0 });

  // Check General Visibility
  const visibilityInput = page.locator('[data-drupal-selector="edit-field-visibility-wrapper"] input');
  // await visibilityInput.waitFor({ state: 'visible', timeout: 50000 });
  await visibilityInput.fill('2');

  // Add menu
  await page.locator('[data-drupal-selector="edit-menu"]').first().locator('summary').click();
  await page.check('input[type="checkbox"][name="menu[enabled]"]');

  // Add alias
  await page.locator('[data-drupal-selector="edit-path-0"]').click();
  await page.fill('[data-drupal-selector="edit-path-0-alias"]', cwlpageURL);

  // Save changes
  await page.locator(' [data-drupal-selector="edit-submit"]').click();
}