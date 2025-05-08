<?php

namespace App\Services\Accounting;

use App\Models\AccountingIntegration;
use App\Models\ChartOfAccountsMapping;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;

class QuickBooksService implements AccountingServiceInterface
{
    protected $dataService;
    protected $integration;

    public function __construct(AccountingIntegration $integration)
    {
        $this->integration = $integration;
        $this->initializeDataService();
    }

    protected function initializeDataService()
    {
        $credentials = $this->integration->credentials;
        
        $this->dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => $credentials['client_id'],
            'ClientSecret' => $credentials['client_secret'],
            'RedirectURI' => config('app.url') . '/accounting/callback',
            'scope' => 'com.intuit.quickbooks.accounting',
            'baseUrl' => $credentials['environment'] === 'production' ? 'production' : 'development',
        ]);
    }

    public function getAuthUrl()
    {
        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        return $OAuth2LoginHelper->getAuthorizationCodeURL();
    }

    public function handleCallback($code)
    {
        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code);
        
        $this->integration->update([
            'credentials' => array_merge($this->integration->credentials, [
                'access_token' => $accessToken->getAccessToken(),
                'refresh_token' => $accessToken->getRefreshToken(),
                'realm_id' => $accessToken->getRealmID(),
            ]),
            'is_active' => true,
        ]);

        return true;
    }

    public function syncAccounts()
    {
        $accounts = $this->dataService->Query("SELECT * FROM Account");
        
        foreach ($accounts as $account) {
            ChartOfAccountsMapping::updateOrCreate(
                [
                    'accounting_integration_id' => $this->integration->id,
                    'provider_account_id' => $account->Id,
                ],
                [
                    'provider_account_name' => $account->Name,
                    'system_account' => $this->mapAccountType($account->AccountType),
                ]
            );
        }
    }

    public function createInvoice($invoiceData)
    {
        $invoice = $this->dataService->getNewInvoice();
        
        // Map invoice data to QuickBooks format
        $invoice->Line = $this->mapInvoiceLines($invoiceData['lines']);
        $invoice->CustomerRef = $this->getCustomerRef($invoiceData['customer_id']);
        
        $result = $this->dataService->Add($invoice);
        
        if ($result) {
            return $result->Id;
        }
        
        return false;
    }

    protected function mapAccountType($quickbooksType)
    {
        $mapping = [
            'Bank' => 'bank',
            'Accounts Receivable' => 'accounts_receivable',
            'Other Current Asset' => 'current_asset',
            'Fixed Asset' => 'fixed_asset',
            'Other Asset' => 'other_asset',
            'Accounts Payable' => 'accounts_payable',
            'Credit Card' => 'credit_card',
            'Other Current Liability' => 'current_liability',
            'Long Term Liability' => 'long_term_liability',
            'Equity' => 'equity',
            'Income' => 'income',
            'Cost of Goods Sold' => 'cost_of_goods_sold',
            'Expense' => 'expense',
            'Other Income' => 'other_income',
            'Other Expense' => 'other_expense',
        ];

        return $mapping[$quickbooksType] ?? 'other';
    }

    protected function mapInvoiceLines($lines)
    {
        $quickbooksLines = [];
        
        foreach ($lines as $line) {
            $quickbooksLine = $this->dataService->getNewLine();
            $quickbooksLine->Amount = $line['amount'];
            $quickbooksLine->DetailType = 'SalesItemLineDetail';
            $quickbooksLine->SalesItemLineDetail = $this->mapLineDetail($line);
            $quickbooksLines[] = $quickbooksLine;
        }
        
        return $quickbooksLines;
    }

    protected function mapLineDetail($line)
    {
        $detail = $this->dataService->getNewSalesItemLineDetail();
        $detail->ItemRef = $this->getItemRef($line['item_id']);
        $detail->Qty = $line['quantity'];
        $detail->UnitPrice = $line['unit_price'];
        return $detail;
    }

    protected function getCustomerRef($customerId)
    {
        $ref = $this->dataService->getNewReferenceType();
        $ref->value = $customerId;
        return $ref;
    }

    protected function getItemRef($itemId)
    {
        $ref = $this->dataService->getNewReferenceType();
        $ref->value = $itemId;
        return $ref;
    }

    public function getInvoices()
    {
        // Implement QuickBooks API call to get invoices
        return [];
    }

    public function getPayments()
    {
        // Implement QuickBooks API call to get payments
        return [];
    }

    public function syncInvoice($invoice)
    {
        // Implement QuickBooks API call to sync invoice
    }

    public function syncPayment($payment)
    {
        // Implement QuickBooks API call to sync payment
    }
} 