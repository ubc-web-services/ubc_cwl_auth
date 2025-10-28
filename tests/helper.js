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
