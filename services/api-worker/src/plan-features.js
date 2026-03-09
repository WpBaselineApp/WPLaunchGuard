function normalizePlanId(value) {
  return String(value || '').trim().toLowerCase() || 'starter';
}

function getPlanFeatures(planId) {
  const normalized = normalizePlanId(planId);
  if (normalized === 'agency') {
    return {
      pdf_export: true,
      zip_export: true,
      whitelabel: true
    };
  }
  if (normalized === 'growth') {
    return {
      pdf_export: true,
      zip_export: true,
      whitelabel: false
    };
  }
  return {
    pdf_export: true,
    zip_export: false,
    whitelabel: false
  };
}

module.exports = {
  getPlanFeatures,
  normalizePlanId
};
