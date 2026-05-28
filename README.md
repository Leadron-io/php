# Leadron PHP SDK

Official PHP SDK for the [Leadron](https://leadron.io) API — lead management and partner commission platform.

## Install

```bash
composer require leadron/sdk
```

## Usage

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Leadron\Leadron;

$client = new Leadron([
    'api_key' => getenv('LEADRON_API_KEY'),
    'base_url' => 'https://api.leadron.io', // optional
]);

// Auth
$valid = $client->auth()->validate();
$scopes = $client->auth()->getScopes();

// Leads
$lead = $client->leads()->create([
    'email' => 'jane@example.com',
    'firstName' => 'Jane',
    'lastName' => 'Doe',
]);
$list = $client->leads()->list(['status' => 'qualified', 'limit' => 20]);
foreach ($list['autoPaginate'] as $item) {
    echo $item['email'] . "\n";
}

// Partners & commissions
$partner = $client->partners()->get($partnerId);
$client->commissions()->approve($commissionId);

// Webhook signature verification (client-side)
$isValid = $client->auth()->verifyWebhookSignature($rawBody, $signature, $secret);
$event = $client->webhooks()->constructEvent($rawBody, $signature, $secret);

// Optional: idempotency and request ID
$client->leads()->create($data, [
    'idempotency_key' => 'unique-key-123',
    'request_id' => 'my-request-id',
]);

// Rate limit (from last response)
$remaining = $client->getRateLimitStatus();
```

## API surface

- **auth** — validate, getScopes, verifyWebhookSignature
- **leads** — create, get, update, delete, list (with autoPaginate), assign, updateStatus, addNote, getNotes, getTimeline, markConverted, bulkCreate, bulkAssign, bulkUpdateStatus, search, filter
- **partners** — create, get, update, list, deactivate, getReferralTree, getUpline, getReferralLink, getStats, getLeaderboard, getTopPerformers, invite, resendInvite, getOnboardingStatus, sendAgreement, getSignedDocuments, getAgreementStatus
- **commissions** — create, get, list, approve, reject, markPaid, getRules, createRule, updateRule, deleteRule, getPayoutSummary, getWalletBalance, requestPayout, getPayoutHistory, getSummary, getByPartner, getTotalOwed, getTotalPaid
- **sequences** — create, get, list, update, delete, activate, pause, enrollLead, enrollBulk, unenrollLead, getEnrolledLeads, addStep, updateStep, deleteStep, reorderSteps
- **sms** — send, getInbox, getOutbox, getConversation, getUsage
- **phoneNumbers** — search, list, get, release, assignToTeam, unassignFromTeam, getUsage, get10DLCStatus
- **teams** — create, get, list, update, delete, addMember, removeMember, getMembers, assignLead, assignPhoneNumber, getStats, getLeaderboard
- **documents** — templates (create, list, get, update, delete), send, sendToPartner, get, list, getStatus, download, getAuditTrail, void, resend
- **webhooks** — create, list, get, update, delete, test, getLogs, retry, constructEvent
- **analytics** — getOverview, getLeadMetrics, getCommissionMetrics, getPartnerMetrics, getConversionRate, getSmsMetrics
- **reports** — leads, commissions, partners, export
- **account** — get, update, getBranding, updateBranding, apiKeys (list, create, revoke), getUsage, getPlan, getLimits

## Webhook events

See [Leadron Events](https://docs.leadron.io/events) for the full list of webhook event types. Verify payloads with `$client->auth()->verifyWebhookSignature($payload, $signature, $secret)` before processing.

## API docs

Full API reference: [Leadron Docs](https://docs.leadron.io).
