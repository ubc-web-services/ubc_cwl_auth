import { test, expect } from '@playwright/test';
import { doLogin } from './helper.js';

test.describe('Generic Test Suite - confirm visibility taxonomy exists', () => {

  test.beforeEach(async ({ page, baseURL }) => {
    await doLogin(page, baseURL);
    await page.goto('/admin/structure/taxonomy');
  });

  test('Check visibility taxonomy exists', async ({ page }) => {
    await expect(page.locator('#taxonomy-overview-vocabularies')).toContainText('Visibility')
  });

  test('Check visibility taxonomy has CWL and General terms', async ({ page }) => {
    await page.goto('/admin/structure/taxonomy/manage/visibility/overview');
    await expect(page.locator('#taxonomy-overview-terms')).toContainText('CWL')
    await expect(page.locator('#taxonomy-overview-terms')).toContainText('General')
  });

});