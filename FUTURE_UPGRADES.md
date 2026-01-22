# Future Upgrades

Short, practical ideas for the next iterations of the project. Keep scope focused and ship in phases.

## Operations & staff
- Shift scheduling with availability, swap requests, and approvals.
- Maintenance SLAs with reminders, escalation, and auto-assign based on skills.
- Daily checklist templates per role (opening, safety, ride checks, cleaning).
- Incident reporting workflow with photos and resolution tracking.

## Data & analytics
- KPI dashboard with time filters (revenue, occupancy, repeat guests, NPS).
- Cohort analysis for promotions and seasonal ticket bundles.
- Ride and room demand forecasting for staffing and pricing.
- Anomaly alerts for sudden drops in bookings or abnormal refund rates.

## Security & compliance
- Role-based access hardening (already added) plus MFA for admins.
- Audit logs with export and retention policies (already added).
- Sensitive data encryption at rest (PII, payment references).
- Privacy policy and data deletion workflow for compliance requests.

## Architecture upgrades
- Queue jobs for emails, notifications, and heavy analytics.
- Redis cache for hot dashboards and rate-limited APIs.
- Event-driven domain actions (bookings, maintenance, payments).
- API versioning strategy (v1 started) with deprecation schedule.

## UI/UX upgrades
- Unified design system (colors, type scale, spacing tokens).
- Mobile-first admin and staff views with quick actions.
- Kiosk mode for on-site ticketing and queue display.
- Accessibility pass for contrast, focus, and screen readers.

## Tech stack options
- Frontend: Vue + Inertia or React + Vite for rich dashboards.
- Mobile: Flutter or React Native for staff operations.
- Analytics: Metabase or Superset on a read replica.
- Realtime: Laravel Echo + WebSockets for live ops updates.
