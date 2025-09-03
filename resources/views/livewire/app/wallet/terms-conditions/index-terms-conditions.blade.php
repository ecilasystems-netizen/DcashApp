<div>
    <x-slot name="header">
        <!-- Header -->
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex items-center gap-4">
                <a href="#" class="p-2 rounded-full hover:bg-gray-800">
                    <i data-lucide="arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-white">Terms and Conditions</h1>
                    <p class="text-gray-400 text-sm">Last updated: August 29, 2025</p>
                </div>
            </div>
        </header>
    </x-slot>

    <!-- Terms Content -->
    <div class="p-4 lg:p-0 lg:py-8">
        <div class="bg-gray-800 p-6 sm:p-8 rounded-lg border border-gray-700 terms-content">
            <p>Please read these terms and conditions carefully before using Our Service.</p>

            <h2>Interpretation and Definitions</h2>
            <h3>Interpretation</h3>
            <p>The words of which the initial letter is capitalized have meanings defined under the following
                conditions. The following definitions shall have the same meaning regardless of whether they appear in
                singular or in plural.</p>
            <h3>Definitions</h3>
            <p>For the purposes of these Terms and Conditions:</p>
            <ul>
                <li><strong>Application</strong> means the software program provided by the Company downloaded by You on
                    any electronic device, named Wallet System.
                </li>
                <li><strong>Company</strong> (referred to as either "the Company", "We", "Us" or "Our" in this
                    Agreement) refers to Wallet System Inc.
                </li>
                <li><strong>Service</strong> refers to the Application.</li>
                <li><strong>You</strong> means the individual accessing or using the Service, or the company, or other
                    legal entity on behalf of which such individual is accessing or using the Service, as applicable.
                </li>
            </ul>

            <h2>Acknowledgment</h2>
            <p>These are the Terms and Conditions governing the use of this Service and the agreement that operates
                between You and the Company. These Terms and Conditions set out the rights and obligations of all users
                regarding the use of the Service.</p>
            <p>Your access to and use of the Service is conditioned on Your acceptance of and compliance with these
                Terms and Conditions. By accessing or using the Service You agree to be bound by these Terms and
                Conditions.</p>

            <h2>User Accounts</h2>
            <p>When You create an account with Us, You must provide Us information that is accurate, complete, and
                current at all times. Failure to do so constitutes a breach of the Terms, which may result in immediate
                termination of Your account on Our Service.</p>
            <p>You are responsible for safeguarding the password and PIN that You use to access the Service and for any
                activities or actions under Your password, whether Your password is with Our Service or a Third-Party
                Social Media Service.</p>

            <h2>Limitation of Liability</h2>
            <p>To the maximum extent permitted by applicable law, in no event shall the Company or its suppliers be
                liable for any special, incidental, indirect, or consequential damages whatsoever (including, but not
                to, damages for loss of profits, loss of data or other information, for business interruption, for
                personal injury, loss of privacy arising out of or in any way related to the use of or inability to use
                the Service).</p>

            <h2>Governing Law</h2>
            <p>The laws of Nigeria, excluding its conflicts of law rules, shall govern this Terms and Your use of the
                Application. Your use of the Application may also be subject to other local, state, national, or
                international laws.</p>

            <h2>Changes to These Terms and Conditions</h2>
            <p>We reserve the right, at Our sole discretion, to modify or replace these Terms at any time. If a revision
                is material We will make reasonable efforts to provide at least 30 days' notice prior to any new terms
                taking effect. What constitutes a material change will be determined at Our sole discretion.</p>

            <h2>Contact Us</h2>
            <p>If you have any questions about these Terms and Conditions, You can contact us by email:
                support@walletsystem.com</p>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-700 flex flex-col sm:flex-row gap-4">
                <button id="decline-btn"
                        class="w-full bg-gray-700 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                    Decline
                </button>
                <button id="accept-btn"
                        class="w-full brand-gradient text-white py-3 px-6 rounded-lg font-semibold hover:opacity-90 transition-colors">
                    Accept & Continue
                </button>
            </div>
        </div>
    </div>

</div>
