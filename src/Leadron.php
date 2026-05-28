<?php

namespace Leadron;

use Leadron\Http\Client as HttpClient;
use Leadron\Resources\Account;
use Leadron\Resources\Analytics;
use Leadron\Resources\Auth;
use Leadron\Resources\Commissions;
use Leadron\Resources\Documents;
use Leadron\Resources\Partners;
use Leadron\Resources\PhoneNumbers;
use Leadron\Resources\Leads;
use Leadron\Resources\Marketers;
use Leadron\Resources\Reports;
use Leadron\Resources\Sequences;
use Leadron\Resources\Sms;
use Leadron\Resources\Teams;
use Leadron\Resources\Webhooks;

class Leadron
{
    /** @var HttpClient */
    private $http;

    /** @var Auth|null */
    private $auth;

    /** @var Leads|null */
    private $leads;

    /** @var Partners|null */
    private $partners;

    /** @var Marketers|null */
    private $marketers;

    /** @var Commissions|null */
    private $commissions;

    /** @var Sequences|null */
    private $sequences;

    /** @var Sms|null */
    private $sms;

    /** @var PhoneNumbers|null */
    private $phoneNumbers;

    /** @var Teams|null */
    private $teams;

    /** @var Documents|null */
    private $documents;

    /** @var Webhooks|null */
    private $webhooks;

    /** @var Analytics|null */
    private $analytics;

    /** @var Reports|null */
    private $reports;

    /** @var Account|null */
    private $account;

    /**
     * @param array{api_key: string, base_url?: string, max_retries?: int} $config
     */
    public function __construct(array $config)
    {
        $apiKey = $config['api_key'] ?? $config['apiKey'] ?? '';
        $baseUrl = $config['base_url'] ?? $config['baseUrl'] ?? null;
        $maxRetries = $config['max_retries'] ?? $config['maxRetries'] ?? 3;
        $this->http = new HttpClient($apiKey, $baseUrl, $maxRetries);
    }

    public function auth(): Auth
    {
        if ($this->auth === null) {
            $this->auth = new Auth($this->http);
        }
        return $this->auth;
    }

    public function leads(): Leads
    {
        if ($this->leads === null) {
            $this->leads = new Leads($this->http);
        }
        return $this->leads;
    }

    public function partners(): Partners
    {
        if ($this->partners === null) {
            $this->partners = new Partners($this->http);
        }
        return $this->partners;
    }

    public function marketers(): Marketers
    {
        if ($this->marketers === null) {
            $this->marketers = new Marketers($this->http);
        }
        return $this->marketers;
    }

    public function commissions(): Commissions
    {
        if ($this->commissions === null) {
            $this->commissions = new Commissions($this->http);
        }
        return $this->commissions;
    }

    public function sequences(): Sequences
    {
        if ($this->sequences === null) {
            $this->sequences = new Sequences($this->http);
        }
        return $this->sequences;
    }

    public function sms(): Sms
    {
        if ($this->sms === null) {
            $this->sms = new Sms($this->http);
        }
        return $this->sms;
    }

    public function phoneNumbers(): PhoneNumbers
    {
        if ($this->phoneNumbers === null) {
            $this->phoneNumbers = new PhoneNumbers($this->http);
        }
        return $this->phoneNumbers;
    }

    public function teams(): Teams
    {
        if ($this->teams === null) {
            $this->teams = new Teams($this->http);
        }
        return $this->teams;
    }

    public function documents(): Documents
    {
        if ($this->documents === null) {
            $this->documents = new Documents($this->http);
        }
        return $this->documents;
    }

    public function webhooks(): Webhooks
    {
        if ($this->webhooks === null) {
            $this->webhooks = new Webhooks($this->http);
        }
        return $this->webhooks;
    }

    public function analytics(): Analytics
    {
        if ($this->analytics === null) {
            $this->analytics = new Analytics($this->http);
        }
        return $this->analytics;
    }

    public function reports(): Reports
    {
        if ($this->reports === null) {
            $this->reports = new Reports($this->http);
        }
        return $this->reports;
    }

    public function account(): Account
    {
        if ($this->account === null) {
            $this->account = new Account($this->http);
        }
        return $this->account;
    }

    /** Remaining requests in the current rate limit window (from last response). */
    public function getRateLimitStatus(): ?int
    {
        return $this->http->getRateLimitStatus();
    }
}
