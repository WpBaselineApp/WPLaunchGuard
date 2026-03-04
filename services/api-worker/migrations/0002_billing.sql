PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS tenant_billing (
  tenant_id TEXT PRIMARY KEY,
  plan_id TEXT NOT NULL,
  billing_status TEXT NOT NULL DEFAULT 'trial',
  stripe_customer_id TEXT,
  stripe_subscription_id TEXT,
  stripe_price_id TEXT,
  current_period_end TEXT,
  checkout_session_id TEXT,
  created_at TEXT NOT NULL,
  updated_at TEXT NOT NULL,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);

CREATE INDEX IF NOT EXISTS idx_tenant_billing_customer ON tenant_billing(stripe_customer_id);
CREATE INDEX IF NOT EXISTS idx_tenant_billing_subscription ON tenant_billing(stripe_subscription_id);
