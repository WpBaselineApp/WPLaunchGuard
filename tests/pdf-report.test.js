const test = require('node:test');
const assert = require('node:assert/strict');

const { buildPdfBranding, buildPdfReportModel, renderPdfHtml } = require('../reporting/generate-pdf-report.js');

test('pdf report model computes sitewide lighthouse averages by unique URL', () => {
  const results = [
    {
      url: 'https://example.com/',
      status: 'FAIL',
      lighthousePerformance: '90',
      lighthouseAccessibility: '80',
      lighthouseBestPractices: '70',
      lighthouseSEO: '60',
      failReasons: 'forms'
    },
    {
      url: 'https://example.com/',
      status: 'FAIL',
      lighthousePerformance: '70',
      lighthouseAccessibility: '60',
      lighthouseBestPractices: '50',
      lighthouseSEO: '40',
      failReasons: 'forms'
    },
    {
      url: 'https://example.com/about',
      status: 'PASS',
      lighthousePerformance: '60',
      lighthouseAccessibility: '90',
      lighthouseBestPractices: '80',
      lighthouseSEO: '70',
      failReasons: ''
    }
  ];
  const summary = [
    { Issue: 'Broken forms', Count: '3', Category: 'forms', Severity: 'critical', Recommendation: 'Fix handlers' }
  ];
  const model = buildPdfReportModel('example', results, summary, { generatedAt: '2026-03-09T10:00:00.000Z', state: 'complete' }, 'Example Site');

  assert.equal(model.lighthouseAverages.performance, 70);
  assert.equal(model.lighthouseAverages.accessibility, 80);
  assert.equal(model.lighthouseAverages.bestPractices, 70);
  assert.equal(model.lighthouseAverages.seo, 60);
  assert.equal(model.lighthouseAverages.pageCounts.performance, 2);
  assert.equal(model.totalUrls, 2);
});

test('pdf report html stays client-safe without screenshot sections', () => {
  const model = buildPdfReportModel(
    'example',
    [{ url: 'https://example.com/', status: 'FAIL', lighthousePerformance: '80' }],
    [{ Issue: 'Broken links', Count: '2', Category: 'seo', Severity: 'major', Recommendation: 'Fix links' }],
    { generatedAt: '2026-03-09T10:00:00.000Z', state: 'complete' },
    'Example Site'
  );
  const html = renderPdfHtml(model);

  assert.match(html, /Sitewide Lighthouse Averages/);
  assert.match(html, /Top Issue Families/);
  assert.doesNotMatch(html, /Screenshots/i);
});

test('pdf branding defaults to Baseline when client branding is not enabled', () => {
  delete process.env.REPORT_BRAND_NAME;
  delete process.env.REPORT_BRAND_LOGO_URL;
  delete process.env.REPORT_HIDE_BASELINE_BRANDING;

  const branding = buildPdfBranding();

  assert.equal(branding.reportDisplayName, 'Baseline');
  assert.equal(branding.logoUrl, '');
});

test('pdf branding switches to client brand when hide baseline branding is enabled', () => {
  process.env.REPORT_BRAND_NAME = 'Eden Legal';
  process.env.REPORT_BRAND_LOGO_URL = 'https://example.com/logo.png';
  process.env.REPORT_HIDE_BASELINE_BRANDING = 'true';
  process.env.REPORT_BRAND_FOOTER_TEXT = 'Custom footer';

  const model = buildPdfReportModel(
    'example',
    [{ url: 'https://example.com/', status: 'PASS', lighthousePerformance: '90' }],
    [],
    { generatedAt: '2026-03-09T10:00:00.000Z', state: 'complete' },
    'Example Site'
  );
  model.branding = buildPdfBranding();
  const html = renderPdfHtml(model);

  assert.equal(model.branding.reportDisplayName, 'Eden Legal');
  assert.match(html, /Eden Legal/);
  assert.match(html, /https:\/\/example\.com\/logo\.png/);
  assert.match(html, /Custom footer/);

  delete process.env.REPORT_BRAND_NAME;
  delete process.env.REPORT_BRAND_LOGO_URL;
  delete process.env.REPORT_HIDE_BASELINE_BRANDING;
  delete process.env.REPORT_BRAND_FOOTER_TEXT;
});
