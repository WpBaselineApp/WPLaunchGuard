const test = require('node:test');
const assert = require('node:assert/strict');

const { getPlanFeatures } = require('../services/api-worker/src/plan-features.js');

test('starter plan keeps client pdf but blocks zip and whitelabel', () => {
  assert.deepEqual(getPlanFeatures('starter'), {
    pdf_export: true,
    zip_export: false,
    whitelabel: false
  });
});

test('growth plan unlocks zip but not whitelabel', () => {
  assert.deepEqual(getPlanFeatures('growth'), {
    pdf_export: true,
    zip_export: true,
    whitelabel: false
  });
});

test('agency plan unlocks all report entitlements', () => {
  assert.deepEqual(getPlanFeatures('agency'), {
    pdf_export: true,
    zip_export: true,
    whitelabel: true
  });
});
