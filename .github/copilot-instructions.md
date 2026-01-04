# GitHub Copilot Instructions for Wallet Platform (Laravel)

## Project Overview
This is a Laravel-based financial platform facilitating multi-currency wallets, deposits, local bank transfers, currency exchange, and utility bill payments using multiple payment gateways. Accuracy, atomicity, and security are paramount.

## Technical Stack
- **Framework:** Laravel 11/12+
- **PHP Version:** PHP 8.3+ (Strict typing enabled)
- **Database:** MySQL (Innodb engine)
- **Financial Precision:** Must use `DECIMAL(16, 4)` for all currency amounts to avoid floating-point errors. Store amounts in the smallest unit (e.g., cents/kobo) and convert on display to avoid potential errors.
- **Architecture:** Service Pattern for business logic, Action classes for single-responsibility tasks.

## Coding Standards & Patterns
- **Strict Typing:** Always use `declare(strict_types=1);` and leverage return types and property types.
- **Database Atomicity:** All financial transactions MUST be wrapped in a `DB::transaction()` to ensure data integrity.
- **Validation:** Use Laravel Form Requests for all validation. Ensure "insufficient balance" checks happen before initiating transfers.
- **Error Handling:** Use custom Exceptions (e.g., `InsufficientFundsException`, `ApiConnectionException`) and handle them via the Laravel Handler.

## API Integration Logic (SafeHaven, Flutterwave, Paystack, NoOnes)
- **Service Layer:** Abstract all API interactions behind a consistent interface within a dedicated Service layer to easily switch providers or add new ones.
- **API Specifics:**
    - **SafeHaven:** Ensure account name inquiries (`name enquiry endpoint`) are called and validated before initiating transfers. Use their sandbox for testing.
    - **Flutterwave:** Use backend SDKs or standard API calls. For real-time FX conversions, leverage their endpoints or integrated services like CurrencyScoop. Verify transactions post-payment using the verify endpoint.
    - **Paystack:** Create reusable `Transfer Recipients` for beneficiaries to streamline future transfers. Amounts for NGN should be handled in kobo (integers).
    - **NoOnes:** Utilize their RESTful API for wallet and trading automation. Ensure API keys are stored securely.
- **Historical Data:** Store historical exchange rates in the database with timestamps or effective date ranges for accurate auditing and reporting.

## Testing
- Write robust Pest or PHPUnit tests for every Service and API integration class.
- Prioritize "Happy Path" and "Edge Case" tests for all balance deductions and API call failures.
